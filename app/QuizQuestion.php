<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $guarded = [];

    public function quizOptions(){
        return $this->hasMany('App\QuizOption');
    }
}
