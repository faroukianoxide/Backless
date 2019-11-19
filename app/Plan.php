<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'projects' , 
    'storage_limit', 'store_limit'];

    function subscribers () {
        return $this->hasMany('App\User');
    }
}
