@extends('back.template')

@section('content')
<div class="row pt-2">
<div class="col-3">
<ul id="draggable">
	<li class="draggable ui-state-highlight section">
		<i class="fa fa-arrows handle float-left"></i>
		<span class="new">New Section</span>
		<span class="input" style="display:none;">
			<i class="fa fa-edit float-right" onclick="editQA(this);"></i>
			<i class="fa fa-trash float-right" onclick="deleteQA(this);"></i>

		<form method="post" action="faq/create-section-faq" data-id="" onsubmit="return false;">
			<label>Nom de la section : </label><input name="name" style="float:left;width:100%;" type="text" value="" /><br/>
			<label>Slug : </label><input name="slug" style="float:left;width:100%;" type="text" value="" /><br/>
			<button type="button" class="btn btn-primary btn-save" onclick="saveSection(this);">Save</button>
			<button type="button" class="btn btn-warning btn-save" onclick="closeSection(this);">Cancel</button>
		</form>
		<div class="clearfix"></div>
		</span>
	</li>
	<li class="draggable ui-state-highlight question_answer">
		<i class="fa fa-arrows handle float-left"></i>
		<span class="preview"></span>
		<span class="form">
			<span class="new">New Q&amp;A</span>
			<span class="input" style="display:none;">
			<i class="fa fa-edit float-right" onclick="editQA(this);"></i>
			<i class="fa fa-trash float-right" onclick="deleteQA(this);"></i>
			<form method="post" action="faq/create-question-answer" data-id="" onsubmit="return false;">
				<label>Question : </label><input name="question" style="float:left;width:100%;" type="text" value="" /><br/>
				<label>Slug : </label><input name="slug" style="float:left;width:100%;" type="text" value="" /><br/>
				<label>Answer : </label><br/><textarea name="answer" style="width:90%;" onkeyup="auto_grow(this)"></textarea>
				<button type="button" class="btn btn-primary btn-save" onclick="saveQA(this);">Save</button>
				<button type="button" class="btn btn-warning btn-save" onclick="closeQA(this);">Cancel</button>
			</form>
			<div class="clearfix"></div>
			</span>
		</span>
	</li>
</ul>
</div>
<div class="col-6">

</div>
</div>
<div class="clearfix"></div>

<button id="toggle_answers" class="btn btn-primary btn-sm float-left" onclick="toggleAnswers();">show answers</button>
<h1>Faq</h1>
<ul id="sortable">
@foreach($sections as $section)
	<li class="section">
		<i class="fa fa-arrows handle float-left"></i>
		<span class="glyphicon glyphicon-sort handle float-left"></span>
		<span class="input">
			<i class="fa fa-edit float-right" onclick="editSection(this);"></i>
			<i class="fa fa-trash float-right" onclick="deleteSection(this);"></i>
		<form method="post" style="display:none;" action="faq/create-section-faq" data-id="{{ $section->id }}" onsubmit="return false;">
			<label>Nom de la section : </label><input name="name" style="float:left;width:100%;" type="text" value="{{ $section->name }}" /><br/>
			<label>Slug : </label><input name="slug" style="float:left;width:100%;" type="text" value="{{ $section->slug }}" /><br/>
			<button type="button" class="btn btn-primary btn-save" onclick="saveSection(this);">Save</button>
			<button type="button" class="btn btn-warning btn-save" onclick="closeSection(this);">Cancel</button>
		</form>
		<h4 class="preview_section" id="preview_section{{ $section->id }}">{{ $section->name }}</h4>
		<div class="clearfix"></div>
		</span>
	</li>
	@foreach($section->question_answers as $question_answer)
	<li class="question_answer">

		<span class="preview"></span>
		<span class="form">
			<span class="input">
			<i class="fa fa-edit float-right" onclick="editQA(this);"></i>
			<i class="fa fa-trash float-right" onclick="deleteQA(this);"></i>
			<form method="post" style="display:none;" action="faq/create-question-answer" data-id="{{ $question_answer->id }}" onsubmit="return false;">
				<label>Question : </label><input name="question" style="float:left;width:100%;" type="text" value="{{ $question_answer->question }}" /><br/>
				<label>Slug : </label><input name="slug" style="float:left;width:100%;" type="text" value="{{ $question_answer->slug }}" /><br/>
				<label>Answer : </label><br/><textarea name="answer" style="width:90%;"  onkeyup="auto_grow(this)">{{ $question_answer->answer }}</textarea><br/>
				<button type="button" class="btn btn-primary btn-save" onclick="saveQA(this);">Save</button>
				<button type="button" class="btn btn-warning btn-save" onclick="closeQA(this);">Cancel</button>
			</form>
			<dl class="preview_qa" id="preview_qa{{ $question_answer->id }}">
				<i class="fa fa-arrows handle float-left"></i>
				<dt class="preview_question">{{ $question_answer->question }}</dt>
				<dd class="preview_answer">{!! $question_answer->answer !!}</dd>
			</dl>
			<div class="clearfix"></div>
			</span>
		</span>
	</li>
	@endforeach
@endforeach
</ul>

@stop

@section('style')
{!! Html::style('css/jquery-ui.css') !!}
<style>
.fa {
	margin:0.5rem;
}
  ul { list-style-type: none; margin: 0; padding: 0; margin-bottom: 10px; }
  #draggable li.draggable { margin: 5px; padding: 5px; width: 150px; }
  #sortable {min-height:200px;border:solid 1px grey;}
  #sortable .li {width:100%;height:auto;}
dd {
    margin: 0;
    padding-left: 40px;
}
dt {
    margin: 0;
    padding-left: 20px;
}
dd, dt {
	margin:10px 0;
}
dl {
	padding-left: 15px;
}
h4 {
	font-size: 22px;
}
textarea {
    resize: none;
    overflow: hidden;
    min-height: 50px;
    max-height: 100px;
}
</style>
@stop
@section('scripts')
  <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script>
	var index_qa = 0;
	var answers_hidden = true;
	function nl2br (str, is_xhtml) {
	    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
	}
	function deleteSection(element){
		if(confirm('Delete section ?')) {
			var section_id = $(element).closest('li').find("form").attr("data-id");

			if(section_id){
				if($(element).closest('li').next('li').hasClass('question_answer'))
					alert("Une section non-vide ne peut pas être supprimée.");
				else{
					$(element).closest('li').remove();
					$.post( base_url+'faq/delete-section-faq', {id: section_id});
				}
			} else {
				$(element).closest('li').remove();
			}
		}
	}
	function editSection(element){
		$(element).closest('li').addClass("ui-state-highlight");
		var form = $(element).closest('li').find("form");
		form.show();
		$(element).closest('li').find(".preview_section").hide();
		form.children("input[name='name']").keyup(function() {
			form.children("input[name='slug']").val(slugify($(this).val()));
		});
	}
	function deleteQA(element){
		if(confirm('Delete Question/Answer ?')) {
			$(element).closest('li').remove();
			var qa_id = $(element).closest('li').find("form").attr("data-id");
			if(qa_id)
				$.post( base_url+'faq/delete-question-answer', {id: qa_id});
		}
	}
	function editQA(element){
		$(element).closest('li').addClass("ui-state-highlight");
		var form = $(element).closest('li').find("form");
		form.show();
		$(element).closest('li').find(".preview_qa").hide();
		form.children("input[name='question']").keyup(function() {
			form.children("input[name='slug']").val(slugify($(this).val()));
		});
	}

	function saveSection(element){
		var form = $(element).closest('form');
		$(element).closest('li').removeClass("ui-state-highlight");
		var section_id = form.attr("data-id");
		var name = form.children("input[name='name']").val();
		var slug = form.children("input[name='slug']").val();
		$.post( base_url+form.attr("action"), {name : name,slug : slug,id: section_id}, function( data ) {
			if(section_id==""){
				form.after("<h4 class='preview_section' id='preview_section"+data.id+"'>"+name+"</h4>");
				form.attr("data-id",data.id);
			} else {
				$("#preview_section"+section_id).html(name).show();
			}

	      	form.hide();
	      	$(element).closest('li').find(".preview_section").show();
			saveSectionsOrder();
			saveQaOrder();
		}).fail(function(response) {
			var message = response.responseJSON.message+' : \r\n';
			for (var error in response.responseJSON.errors) {
				message+=response.responseJSON.errors[error]+'\r\n';
			}
			alert(message);
  		});

	}

	function closeSection(element){
		var form = $(element).closest('form');
		$(element).closest('li').removeClass("ui-state-highlight");
		var section_id = form.attr("data-id");
		if(section_id==''){
			$(element).closest('li').remove();
		} else {
			$("#preview_section"+section_id).show();
	      	form.hide();
	      	$(element).closest('li').find(".preview_section").show();
		}
	}
	function closeQA(element){
		var form = $( element ).closest('form');
		$(element).closest('li').removeClass("ui-state-highlight");

		var qa_id = form.attr("data-id");

		if(qa_id==""){
			$(element).closest('li').remove();
		} else {
			$("#preview_qa"+qa_id).show();
			$(element).closest('li').find(".preview_qa").show();
	      	form.hide();
		}
	}

	function saveSectionsOrder(){
		var sections = [];
        $( "#sortable li.section").each(function() {
			var index = $( "#sortable li" ).index( this );
			var  section_id = $(this).find("form").attr("data-id");
			if(section_id!=""){
				var section = {id:section_id,order:(index + 1)};
				sections.push(section);
			}
        });
        if(sections.length>0)
        	$.post( base_url+'faq/update-order-sections', {sections:sections});
	}

	function saveQaOrder(){
		var question_answers = [];
        $( "#sortable li.question_answer").each(function() {
			var index = $( "#sortable li" ).index( this );
			var  section_id = $(this).prevAll("li.section").first().find("form").attr("data-id");
			var  qa_id = $(this).find("form").attr("data-id");
			if(qa_id!=""){
				var qa = {id:qa_id, section_id:section_id, order:(index + 1)};
				question_answers.push(qa);
			}
        });
        if(question_answers.length>0)
        	$.post( base_url+'faq/update-order-question-answer', {question_answers:question_answers});
	}

	function saveQA(element){
		var form = $( element ).closest('form');
		$(element).closest('li').removeClass("ui-state-highlight");
		var question = form.children("input[name='question']").val();
		var answer = form.children("textarea[name='answer']").val();
		var slug = form.children("input[name='slug']").val();
		var qa_id = form.attr("data-id");
		$.post( base_url+form.attr("action"), {question : question,answer:answer,slug:slug,id:qa_id}, function( data ) {
			if(qa_id==""){
				form.after('<dl class="preview_qa" id="preview_qa'+data.id+'"><dt>'+question+'<dt><dd class="preview_answer">'+nl2br(answer)+'<dd></dl>');
				form.attr("data-id",data.id);
			} else {
				$("#preview_qa"+qa_id).html("<dt>"+question+'<dt><dd class="preview_answer">'+nl2br(answer)+"<dd>").show();
			}
			$(element).closest('li').find(".preview_qa").show();
	      	form.hide();
			saveQaOrder();
		}).fail(function(response) {
			var message = response.responseJSON.message+' : \r\n';
			for (var error in response.responseJSON.errors) {
				message+=response.responseJSON.errors[error]+'\r\n';
			}
			alert(message);
  		});;
	}
	function toggleAnswers(){
		if(answers_hidden){
			$('.preview_answer').show();
			$('#toggle_answers').html("hide answers");
			answers_hidden = false;
		} else {
			$('.preview_answer').hide();
			$('#toggle_answers').html("show answers");
			answers_hidden = true;
		}
	}
	function auto_grow(element) {
		element.style.height = "5px";
		element.style.height = (element.scrollHeight)+"px";
	}

	function slugify(str){
		str = str.replace(/^\s+|\s+$/g, ''); // trim
		str = str.toLowerCase();

		// remove accents, swap ñ for n, etc
		var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
		var to   = "aaaaeeeeiiiioooouuuunc------";

		for (var i=0, l=from.length ; i<l ; i++)
		str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));


		str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
		.replace(/\s+/g, '-') // collapse whitespace and replace by -
		.replace(/-+/g, '-').replace(/^-+|-+$/g, ''); // collapse dashes

		return str;
	}

  $( function() {
    $( "#sortable" ).sortable({
    	handle: ".handle",
        revert: true,
	      start: function(event, ui) {
	      	$(ui.item).addClass("draggable");
	      	$(ui.item).addClass("ui-state-highlight");
	      },
	      stop: function(event, ui) {
	      	$(ui.item).removeClass("draggable");
	      	$(ui.item).removeClass("ui-state-highlight");
	      	$(ui.item).css({'width':'100%','height':'auto'});
	      	$(this).find("span.input").show();
	      	$(this).find("span.new").hide();
	      	$(this).find("form").attr("id",index_qa++);
	      	saveQaOrder();
	    }
    });
    $( ".draggable" ).draggable({
      connectToSortable: "#sortable",
      helper: "clone",
      revert: "invalid"
    });
  	if(answers_hidden){
  		$('.preview_answer').hide();
  	} else {
  		$('.preview_answer').show();
  	}
  } );
  </script>
@stop