<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

    protected $fillable = ['user_id', 'channel', 'description'];
    //
}
