<?php 

namespace Gwaps4nlp\FaqManager\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSectionFaq extends FormRequest {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required|max:500',
			'slug' => 'required|max:100',
			'id' => 'nullable|exists:section_faqs'
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