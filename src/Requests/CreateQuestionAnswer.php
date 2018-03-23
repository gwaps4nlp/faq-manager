<?php 

namespace Gwaps4nlp\FaqManager\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionAnswer extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'question' => 'required|max:2000',
			'answer' => 'required|max:2000',
			'slug' => 'required|max:100',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
	    return true;
	}

}