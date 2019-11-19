<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = ['user_id', 'project_id', 'storage_type', 'access_url'
        , 'file_cloud_name', 'file_native_name', 'parent_folder_path', 'size'];
    //
}
