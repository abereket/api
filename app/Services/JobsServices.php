<?php
namespace App\Services;

use App\Models\Job;
use App\Models\User;

class JobsServices extends Base{

    /**
     * @param $request
     * @return array|static
     */
    public function create($request){

    $valError = $this->validateCreate($request->json()->get('userId'));
    if($valError){
        $valError = $this->failureMessage($valError,parent::HTTP_404);
        return $valError;
    }
    $job = Job::create(['user_id'=>$request->json()->get('userId'),'tittle'=>$request->json()->get('tittle'),
               'company_name'=>$request->json()->get('companyName'),'type'=>$request->json()->get('type'),
               'link'=>$request->json()->get('link'),'city'=>$request->json()->get('city'),
               'state'=>$request->json()->get('state'),'zip_code'=>$request->json()->get('zipCode')]);

    $job = $this->buildCreateSuccessMessage('success',$job);
    return $job;

    }

    /**
     * @param $request
     * @param $id
     * @return array
     */
    public function update($request,$id){
        $job = Job::find($id);
        $valError = $this->validateUpdate($job,$request->json()->get('userId'));
        if($valError){
            $valError=$this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $job->user_id      = ($request->json()->get('useId'))?$request->json()->get('useId'):$job->user_id;
        $job->tittle       = ($request->json()->get('tittle'))?$request->json()->get('tittle'):$job->tittle;
        $job->company_name = ($request->json()->get('companyName'))?$request->json()->get('companyName'):$job->company_name;
        $job->type         = ($request->json()->get('type'))?$request->json()->get('type'):$job->type;
        $job->link         = ($request->json()->get('link'))?$request->json()->get('link'):$job->link;
        $job->city         = ($request->json()->get('city'))?$request->json()->get('city'):$job->city;
        $job->state        = ($request->json()->get('state'))?$request->json()->get('state'):$job->state;
        $job->zip_code     = ($request->json()->get('zipCode'))?$request->json()->get('zipCode'):$job->zip_code;
        $job->save();

        $job = $this->buildUpdateSuccessMessage('success',$job);
        return $job;

    }

    /**
     * @param $id
     * @return array|string
     */
    public function delete($id){
        $job = Job::find($id);
        $valError = $this->validateDelete($job);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $job->delete();
        $job = $this->buildDeleteSuccessMessage('success');
        return $job;
    }

    public function retrieveOne($id){

    }
    /**
     * @param $userId
     * @return array|string
     */
    protected function validateCreate($userId){
        $errors = array();
        $user = User::find($userId);
        if(!$user){
            $errors = "The user id you have entered not exists.Please enter a valid user id";
        }
        return $errors;
    }

    /**
     * @param $job
     * @param $userId
     * @return array|string
     */
    protected function validateUpdate($job,$userId){
        $errors = array();
        if(!$job){
            $errors = "The job you are looking for not exists.Please use a valid job id";
        }
        $user = User::find($userId);
        if(!$user){
            $errors="The user id you have entered not exists.Please enter a valid user id";
        }
        return $errors;
    }

    /**
     * @param $job
     * @return array|string
     */
    protected function validateDelete($job){
       $errors = array();
        if(!$job){
            $errors = "The job you are looking for not exists.Please use a valid job id";
        }
        return $errors;
    }
}