<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;

class AppUserController extends Controller
{
    //

    private function getAppDataObject ($project_id, $user_id) {

        $user = Auth::user();
        try {
        $projectAppUser = $user->projects()
            ->where('project_id', $project_id)->first()
            ->users()->find($user_id);

        return $projectAppUser;

        }catch(\Exception $e) {
            return response('not found', 404);
        }
    }

    public function setAppDataEntry ($project_id, $user_id, Request $request) {

        $request->validate(['key'=>'required', 'value' => 'required']);
        $projectAppUser = $this->getAppDataObject($project_id, $user_id);

        $appData = (array) json_decode($projectAppUser->app_data);
        $appData[$request->key] = $request->value;
        $projectAppUser->app_data = json_encode($appData);
        $projectAppUser->save();

        return response('success', 200);


    }

    public function getAppDateEntry ($project_id, $user_id, $field) {

        $projectAppUser = $this->getAppDataObject($project_id, $user_id);

        $appData = (array) json_decode($projectAppUser->app_data);

        if ($appData[$field])
            return response(json_encode($appData[$field]));
        else
            return response('No such data found', 404);


    }

    public function deleteAppDataEntry ($project_id, $user_id, $field) {


        $projectAppUser = $this->getAppDataObject($project_id, $user_id);

        $appData = (array) json_decode($projectAppUser->app_data);

        unset($appData[$field]);
        $projectAppUser->app_data = json_encode($appData);

        return response('success', 200);

    }

    public function clearUserAppData ($project, $user_id) {

        $projectAppUser = $this->getAppDataObject($project_id, $user_id);
        $projectAppUser->app_data = '{}';
        $projectAppUser->save();

        return response('success');
    }

    public function destroyUser ($project, $user_id) {
        $projectAppUser = $this->getAppDataObject($project_id, $user_id);
        $projectAppUser->delete();

        return response ('success');

    }


}
