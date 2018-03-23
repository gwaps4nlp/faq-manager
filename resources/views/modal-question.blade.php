<div class="modal fade" id="modalReport" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="text-center">Poser une question</h3>
                  {!! Form::open(['url' => 'report/send', 'method' => 'post', 'role' => 'form', 'id'=>'form-report']) !!} 
                    <div class="form-group text-center">
                        <textarea id="freeReportArea" class="free-report-textarea" name="message[]" type="text" style="padding:7px;overflow: hidden; word-wrap: break-word; height: 60px;width: 90%;color:#3C3C3C" placeholder="Pose ta question ici, nous l'ajouterons à la FAQ ou te répondrons par email."></textarea>
                    </div>
                    <div class="form-group">
                        <input class="checkboxReport" id="email" name="answer_email" type="checkbox" value="Je souhaite recevoir une réponse par email à l'adresse :">
                        <label class="report-label" for="original">Je souhaite recevoir une réponse par email à l'adresse :</label>
                        @if(Auth::check())
                            <input id="email" name="email" type="text" value="{{ Auth::user()->email }}" placeholder="Adresse email"  style="padding:0 7px;margin-left: 20px;overflow: hidden; word-wrap: break-word;width: 90%;color:#3C3C3C">
                        @else
                            <input id="email" name="email" type="text" value="" placeholder="Adresse email"  style="padding:0 7px;margin-left: 20px;overflow: hidden; word-wrap: break-word;width: 90%;color:#3C3C3C">
                        @endif
                    </div>
                    <div class="text-center">
                        <button type="submit" disabled="disabled" class="btn btn-success" id="submitReport">{{ trans('site.submit') }}</button>
                        <button type="submit" class="btn btn-danger btn-default" data-dismiss="modal" id="cancelReport">{{ trans('site.cancel') }}</button>
                    </div>
                  {!! Form::close() !!}                                                 
            </div>        
        </div>
    </div>
</div>