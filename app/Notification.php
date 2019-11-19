<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['name', 'user_id', 'device_id', 'data_point', 'channel'];
    //
}
