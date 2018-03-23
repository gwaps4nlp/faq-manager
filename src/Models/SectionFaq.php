<?php

namespace Gwaps4nlp\FaqManager\Models;

use Illuminate\Database\Eloquent\Model;
use Gwaps4nlp\Scopes\LanguageScope;

class SectionFaq extends Model
{

	protected $fillable = ['name','slug','order','language_id'];
    
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LanguageScope);
    }

	/**
	 * hasMany relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function question_answers()
	{
	  return $this->hasMany('Gwaps4nlp\FaqManager\Models\QuestionAnswer')->orderBy('order');
	}

}
