@extends('front.template')

@section('main')
<div class="row" id="faq">
	<div class="col-lg-2 d-none d-lg-block">
		<nav id="nav-left" style="position:relative;top:10px;height:250px;width:180px;">
		<ul class="summary">
		<li>{{ trans('site.summary') }}</li>
		@foreach($sections as $section)
			<li>
				<a href="#{{ $section->slug }}" class="scroll">{{ $section->name }}</a>
			</li>
		@endforeach
		</ul>
		</nav>
	</div>
	<div class="col-lg-9 col-12">
	<div class="float-right text-center m-2 p-2" style="border: solid 1px white;border-radius:9px; max-width: 200px;">
		{{ trans('site.question-not-in-the-faq') }}
		<br/>
		<button id="question-faq" class="btn btn-success">{{ trans('site.ask-a-question') }}</button>
	</div>
	<h1>Faq</h1>
	@foreach($sections as $section)
		<h4>{{ $section->name }}</h4>
		<ul>
		@foreach($section->question_answers as $question_answer)
		  <li><a href="#{{ $question_answer->slug }}" class="scroll">{{ $question_answer->question }}</a></li>
		@endforeach
		</ul>
	@endforeach

	@foreach($sections as $section)
		<h2 id="{!! $section->slug !!}">{{ $section->name }}</h2>
		<dl>
		@foreach($section->question_answers as $question_answer)
		  <?php
		  // $nb_messages = ($question_answer->discussion)? $question_answer->discussion->messages()->count():0;
		  $nb_messages = 0;
		  ?>
		  <dt id="{{ $question_answer->slug }}">{{ $question_answer->question }}</dt>
		  <dd id="answer_{{ $question_answer->id }}">{!! $question_answer->answer !!}</dd>
		  @if(Auth::check() && Auth::user()->isAdmin())
		  	<a href="#" class="edit-question" data-qa-id="{{ $question_answer->id }}">Edit</a> -
		  @endif
		  	<span style="margin-right:20px;position:relative;" id="discussion_{{ $question_answer->id }}"><a style="position:relative;" data-id="{{ $question_answer->id }}" href="#" data-type="{{ get_class($question_answer) }}" class="message-button">Commentaires ({{ $nb_messages }})</span></a></span>
		  	<div id="thread_{{ $question_answer->id }}" style="display:none;" class="thread"></div>
		@endforeach
		</dl>
	@endforeach
	</div>
</div>
@include('faq-manager::modal-question')
@stop

@section('css')
<style>
#faq dt {
    font-size: 120%;
}
#faq dt, #faq dd {
    margin: 16px 0;
}
#faq dd, #faq ol, #faq ul {
    margin: 0;
    padding-left: 40px;
}
#nav-left {
	font-size: 14px;
}
#nav-left ul {
	padding-left: 10px;
}
#faq p {
	margin-bottom:0;
}
#faq span.sentence {
	background: #9BC5AA none repeat scroll 0% 0%;
	padding: 5px 8px;
	border-radius: 3px;
}
#faq li {
	padding: 5px 0px;
	list-style-type: disc;
}
#faq ul.summary li {
	padding: 0px;
	list-style-type: none;
}
#faq ul.messages li {
	list-style-type: none;
}
</style>
@stop


@section('scripts')

<script>

@if(true)
	// $('.message-button[data-id=27]').trigger('click');
@endif
</script>


@if(Auth::check() && Auth::user()->isAdmin())

<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/ckeditor.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/adapters/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
var route_prefix = "{{ url(config('lfm.prefix')) }}";
var ckeditor_instance = 0;
$(".edit-question").click(function(event){
    event.preventDefault();
    ckeditor_instance++;
	var qa_id = $(event.target).attr('data-qa-id');
	var answer = $('#answer_'+qa_id);
	var answer_text = answer.html();
	var textarea = $('<textarea>'+answer_text+'</textarea>');
	var button = $('<button class="btn btn-success">Save</button>');
	$(event.target).after(textarea);
	$(textarea).after(button);
    textarea.ckeditor({
      allowedContent: true,
      height: 100,
      filebrowserImageBrowseUrl: route_prefix + '?type=Images',
      filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
      filebrowserBrowseUrl: route_prefix + '?type=Files',
      filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
    });
    button.click(function(event){
    	var content = textarea.val();
		$.post( base_url+'faq/create-question-answer', {answer:content,id:qa_id,from_ck:1}, function( data ) {
			answer.html(content);
			var editor = 'CKEDITOR.instances.editor'+ckeditor_instance+'.destroy()';
			eval(editor);
	      	$(textarea).remove();
	      	$(button).remove();
		});

    });
});
</script>
@endif
<script>
$(document).ready(function() {

	var parser = document.createElement('a');
	parser.href = window.location.href;
	var trgt = parser.hash;

	if(trgt){
		var hash = trgt.split('?');
		var target_offset = $(hash[0]).offset();
		var target_top = target_offset.top-75;
		$('html, body').animate({scrollTop:target_top}, 500, 'easeInSine');
	}
});

$(document).on('click','#question-faq', function(){
    $("#form-report")[0].reset();
    $('#submitReport').attr("disabled","disabled");
    $('body').append($('#modalReport'));
    $('#modalReport').modal("show");

});

$(".scroll").click(function(event){
    event.preventDefault();
    var parser = document.createElement('a');
	parser.href = event.target.href;
	var trgt = event.target.hash;
	var target_offset = $(trgt).offset();
    var target_top = target_offset.top-75;
    $('html, body').animate({scrollTop:target_top}, 500, 'easeInSine');
});

$( function() {
	$(window).scroll(function() {
		var offset = $('#nav-left').offset();
		var scroll_top = $(window).scrollTop();
		if(scroll_top>90){
			$('#nav-left').css({position:'fixed', top:'75px'});
		} else {
			$('#nav-left').css({position:'relative', top:'0px'});
		}

	});

});
</script>
@stop
