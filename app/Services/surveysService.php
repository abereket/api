<?php
namespace App\Services;


use App\Models\Job;
use App\Models\Surveys;
use App\Models\User;

class surveysService extends Base{

    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
        $valError =$this->validateCreate($request->json()->get('job_id'),$request->json()->get('user_id'));
        if($valError){
           $valError = $this->failureMessage($valError,parent::HTTP_404);
           return $valError;
        }
        $survey = Surveys::create(['job_id'=>$request->json()->get('job_id'),'user_id'=>$request->json()->get('user_id'),
                                    'name'=>$request->json()->get('name')]);
        $survey = $this->buildCreateSuccessMessage('success',$survey);
        return $survey;
    }

    /**
     * @param $request
     * @param $id
     * @return array
     */
    public function update($request,$id){
        $survey = Surveys::find($id);
        $valError = $this->validateUpdate($survey,$request->json()->get('job_id'),$request->json()->get('user_id'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $survey->job_id   =  $request->json()->get('job_id');
        $survey->user_id  =  $request->json()->get('user_id',$survey->user_id);
        $survey->name     =  $request->json()->get('name',$survey->name);
        $survey->save();

        $survey = $this->buildUpdateSuccessMessage('success',$survey);
        return $survey;
    }

    /**
     * @param $request
     * @return Surveys|array
     */
    public function retrieve($request){

        $limit   = ($request->input('per_page'))?$request->input('per_page'):15;
        $jobId   =  $request->input('job_id');
        $userId  =  $request->input('user_id');
        $name    =  $request->input('name');
        $orderBy = ($request->input('order_by'))?$request->input('order_by'):'updated_at';

        $survey = new Surveys();
        if($jobId){
          $survey = $survey->where('job_id', 'like','%'.$jobId.'%');
        }
        if($userId){
            $survey = $survey->where('user_id', 'like','%'.$userId.'%');
        }
        if($name){
            $survey = $survey->where('name','like','%'.$name.'%');
        }
        $survey = $survey->orderby($orderBy)->paginate($limit);
        $survey = $this->buildRetrieveResponse($survey->toArray());
        $survey = $this->buildRetrieveSuccessMessage('success',$survey);
        return $survey;
    }

    /**
     * @param $id
     * @return array|string
     */
    public function retrieveOne($id){
        $survey = Surveys::find($id);
        $valError = $this->validateRetrieveOne($survey);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $survey = $this->buildRetrieveOneSuccessMessage('success',$survey);
        return $survey;
    }

    /**
     * @param $id
     * @return array|string
     */
    public function delete($id){
        $survey = Surveys::find($id);
        $valError = $this->validateDelete($survey);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $survey->delete();
        $survey = $this->buildDeleteSuccessMessage('success');
        return $survey;
    }
    /**
     * @param $jobId
     * @param $userId
     * @return array|string
     */
    protected function validateCreate($jobId,$userId){
        $errors = array();
        $job = Job::find($jobId);
        if(!$job){
           $errors = 'please enter a valid job_id';
        }
        $user = User::find($userId);
        if(!$user){
            $errors = 'please enter a valid user_id';
        }
        return $errors;
    }

    /**
     * @param $survey
     * @param $jobId
     * @param $userId
     * @return array|string
     */
    protected function validateUpdate($survey,$jobId,$userId){
        $errors = array();
        if(!$survey){
            $errors = 'The survey you are looking is not found.Please enter a valid survey id';
        }
        $job = Job::find($jobId);
        if(!$job){
            $errors = 'please enter a valid job_id';
        }
        if($userId || empty($userId)){
            $user = User::find($userId);
            if(!$user){
                $errors = 'please enter a valid user_id';
            }
        }
        return $errors;
    }

    /**
     * @param $survey
     * @return array|string
     */
    protected function validateRetrieveOne($survey){
        $errors = array();
        if(!$survey){
            $errors = 'The survey you are looking is not found.Please enter a valid survey id';
        }
        return $errors;
    }

    /**
     * @param $survey
     * @return array|string
     */
    protected function validateDelete($survey){
        $errors = array();
        if(!$survey){
            $errors = 'The survey you are looking is not found.Please enter a valid survey id';
        }
        return $errors;
    }
}