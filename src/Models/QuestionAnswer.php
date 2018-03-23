<?php

namespace Gwaps4nlp\FaqManager\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    protected $visible = ['question','answer','slug','section_faq_id','order'];


    public function discussion()
    {
        return $this->morphOne('App\Models\Discussion', 'entity');
    }
        
}
