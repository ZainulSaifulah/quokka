<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];

    public function userQuizResults(){
        return $this->hasMany('App\UserQuizResult');
    }
}
