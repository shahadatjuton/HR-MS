<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function job(){
        return $this->belongsTo('App\Job');
    }
}
