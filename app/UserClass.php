<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserClass extends Model
{
    protected $guarded = [];

    public function class(){
        return $this->belongsTo('App\Classroom');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
