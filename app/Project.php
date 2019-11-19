<?php

namespace App;

use App\Http\Controllers\StorageController;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //

    public function data () {
        return $this->hasMany('\App\Data');
    }

    public function auths () {
        return $this->hasMany('\App\Auth');
    }

    public function channels () {
        return $this->hasMany('\App\Channel');
    }

    public function assets () {
        return $this->hasMany('\App\Asset');
    }

    public function folders () {
        return $this->hasMany('\App\Folder');
    }

    public function indexes () {
        return $this->hasMany('\App\Index');
    }

    public function stats () {
        return $this->hasMany('\App\Stat');
    }

    public function delete () {

        $this->data()->delete();
        $this->auths()->delete();
        $this->channels()->delete();
        $this->indexes()->delete();

        $folders = $this->folders();
        
        $storageController = new StorageController();
        $storageController->destroyFolder('root', $this->id);
        foreach ($folders as $folder) {
            $storageController->destroyFolder($folder->parent, $this->id);
        }
        $this->folders()->delete();
        parent::delete();

    }

    
}
