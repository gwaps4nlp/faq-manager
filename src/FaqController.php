<?php

namespace Gwaps4nlp\FaqManager;

use Gwaps4nlp\FaqManager\Models\SectionFaq;
use Gwaps4nlp\FaqManager\Models\QuestionAnswer;
use Gwaps4nlp\FaqManager\Requests\CreateQuestionAnswer;
use Gwaps4nlp\FaqManager\Requests\CreateSectionFaq;
use Gwaps4nlp\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Response,App;

class FaqController extends BaseController
{
    
    /**
     * Show the FAQ
     *
     * @return Illuminate\Http\Response
     */
    public function getIndex()
    {
        $sections = SectionFaq::orderBy('order')->get();
        return view('faq-manager::index',compact('sections'));

    }
    
    /**
     * Show the admin index
     *
     * @return Illuminate\Http\Response
     */
    public function getAdminIndex()
    {
        $sections = SectionFaq::orderBy('order')->get();
        return view('faq-manager::admin-index',compact('sections'));

    } 
    
    /**
     * Create a new Question-Answer
     *
     * @param  Illuminate\Http\Request $request     
     * @return Illuminate\Http\Response
     */
    public function postCreateQuestionAnswer(CreateQuestionAnswer $request)
    {
        if($request->has('id') && $request->input('id')!='')
            $question_answer = QuestionAnswer::findOrFail($request->input('id'));
        else
            $question_answer = new QuestionAnswer;
        if($request->has('question'))
            $question_answer->question = $request->input('question');
        if($request->has('answer')){
            if($request->has('from_ck'))
                $question_answer->answer = $request->input('answer');
            else
                $question_answer->answer = nl2br($request->input('answer'));
        }
        $question_answer->slug = $request->input('slug');
        $question_answer->save();
        return Response::json(['id'=>$question_answer->id]);
    }

    /**
     * Delete a Question-Answer
     *
     * @param  Illuminate\Http\Request $request     
     * @return Illuminate\Http\Response
     */
    public function postDeleteQuestionAnswer(Request $request)
    {
        $section = QuestionAnswer::findOrFail($request->input('id'));
        $section->delete();
        return Response::json(['id'=>'']);
    }

    /**
     * Create a new section
     *
     * @param  App\Http\Requests\CorpusCreateRequest $request     
     * @return Illuminate\Http\Response
     */
    public function postCreateSectionFaq(CreateSectionFaq $request)
    {
        if($request->has('id') && $request->input('id')!='')
            $section = SectionFaq::findOrFail($request->input('id'));
        else
            $section = new SectionFaq;
        $section->name = $request->input('name');
        $section->slug = $request->input('slug');
        $section->language_id = Language::getIdBySlug(App::getLocale());     
        $section->save();
        return Response::json(['id'=>$section->id]);
    }

    /**
     * Delete a Section
     *
     * @param  App\Http\Requests\CorpusCreateRequest $request     
     * @return Illuminate\Http\Response
     */
    public function postDeleteSectionFaq(SectionFaq $section)
    {
        // $section = SectionFaq::findOrFail($request->input('id'));
        $section->delete();
        return Response::json(['id'=>'']);
    }

    /**
     * Update the order of the sections of the faq
     *
     * @param  App\Http\Requests\CorpusCreateRequest $request     
     * @return Illuminate\Http\Response
     */
    public function postUpdateOrderSections(Request $request)
    {
        foreach($request['sections'] as $section){
            $section_faq = SectionFaq::findOrFail($section['id']);
            $section_faq->order = $section['order'];
            $section_faq->save();
        }
        return Response::json(['id'=>0]);
    }

    /**
     * Update the order of the Questions/Answers of the faq
     *
     * @param  App\Http\Requests\CorpusCreateRequest $request     
     * @return Illuminate\Http\Response
     */
    public function postUpdateOrderQuestionAnswer(Request $request)
    {
        foreach($request['question_answers'] as $qa){
            $question_answer = QuestionAnswer::findOrFail($qa['id']);
            $question_answer->order = $qa['order'];
            $question_answer->section_faq_id = $qa['section_id'];
            $question_answer->save();
        }
        return Response::json(['id'=>0]);
    }    

}
