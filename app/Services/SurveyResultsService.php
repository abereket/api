<?php
namespace App\Services;

use App\Models\Job;
use App\Models\SurveyResults;
use App\Models\Surveys;
use App\Models\User;

class SurveyResultsService extends Base{
    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
        $valError = $this->validateCreate($request->json()->get('user_id'),$request->json()->get('job_id'),$request->json()->get('survey_id'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $surveyResults = SurveyResults::create(['user_id'=>$request->json()->get('user_id'),'job_id'=>$request->json()->get('job_id'),
                                               'survey_id'=>$request->json()->get('survey_id'),'rating'=>$request->json()->get('rating'),
                                               'years_of_experience'=>$request->json()->get('years_of_experience')]);
        $surveyResults = $this->buildCreateSuccessMessage('success',$surveyResults);
        return $surveyResults;
    }

    /**
     * @param $request
     * @param $id
     * @return array
     */
    public function update($request,$id){
        $surveyResult = SurveyResults::find($id);
        $valError = $this->validateUpdate($surveyResult,$request->json()->get('user_id'),$request->json()->get('job_id'),
                                          $request->json()->get('survey_id'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $surveyResult->user_id               =    $request->json()->get('user_id',$surveyResult->user_id);
        $surveyResult->job_id                =    $request->json()->get('job_id',$surveyResult->job_id);
        $surveyResult->survey_id             =    $request->json()->get('survey_id',$surveyResult->survey_id);
        $surveyResult->rating                =    $request->json()->get('rating',$surveyResult->rating);
        $surveyResult->years_of_experience   =    $request->json()->get('years_of_experience',$surveyResult->years_of_experience);
        $surveyResult->save();

        $surveyResult = $this->buildUpdateSuccessMessage('success',$surveyResult);
        return $surveyResult;
    }

    /**
     * @param $request
     * @return SurveyResults
     */
    public function retrieve($request){
        $limit              =   ($request->input('per_page'))?($request->input('per_page')) : 15;
        $userId             =   $request->input('user_id');
        $jobId              =   $request->input('job_id');
        $surveyId           =   $request->input('survey_id');
        $rating             =   $request->input('rating');
        $yearsOfExperience  =   $request->input('years_of_experience');
        $createdAt          =   $request->input('created_at');
        $updatedAt          =   $request->input('updated_at');
        $deletedAt          =   $request->input('deleted_at');
        $order_by           =   ($request->input('order_by'))? ($request->input('order_by')) : 'updated_at';

        $surveyResult = new SurveyResults();
        if ($userId) {
            $surveyResult = $surveyResult->where('name' , 'like', '%'.$userId.'%');
        }
        if ($jobId) {
            $surveyResult = $surveyResult->where('description',  'like', '%'.$jobId.'%');
        }
        if ($surveyId) {
            $surveyResult = $surveyResult->where('name' , 'like', '%'.$surveyId.'%');
        }
        if ($rating) {
            $surveyResult = $surveyResult->where('description',  'like', '%'.$rating.'%');
        }
        if ($yearsOfExperience) {
            $surveyResult = $surveyResult->where('name' , 'like', '%'.$yearsOfExperience.'%');
        }
        if ($createdAt) {
            $surveyResult = $surveyResult->where('description',  'like', '%'.$createdAt.'%');
        }
        if ($updatedAt) {
            $surveyResult= $surveyResult->where('name' , 'like', '%'.$updatedAt.'%');
        }
        if ($deletedAt) {
            $surveyResult = $surveyResult->where('description',  'like', '%'.$deletedAt.'%');
        }
        $surveyResult = $surveyResult->orderby($order_by)->paginate($limit);

        $surveyResult= $this->buildRetrieveResponse($surveyResult->toArray());
        $surveyResult= $this->buildRetrieveSuccessMessage("success",$surveyResult);
        return $surveyResult;
    }

    /**
     * @param $id
     * @return array|string
     */
    public function retrieveOne($id){
        $surveyResult =  SurveyResults::find($id);
        $valError = $this->validateRetrieveOne($surveyResult);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $surveyResult = $this->buildRetrieveOneSuccessMessage('success',$surveyResult);
        return $surveyResult;
    }

    /**
     * @param $id
     * @return array|string
     */
    public function delete($id){
        $surveyResult = SurveyResults::find($id);
        $valError = $this->validateDelete($surveyResult);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $surveyResult->delete();
        $surveyResult = $this->buildDeleteSuccessMessage('success');
        return $surveyResult;
    }
    /**
     * @param $userId
     * @param $jobId
     * @param $surveyId
     * @return array|string
     */
    protected function validateCreate($userId,$jobId,$surveyId){
        $errors = array();
        $user = User::find($userId);
        if(!$user){
           $errors = 'Please enter a valid user_id';
        }
        $job = Job::find($jobId);
        if(!$job){
            $errors = 'Please enter a valid job_id';
        }
        $survey = Surveys::find($surveyId);
        if(!$survey){
            $errors = 'Please enter a valid survey_id';
        }
        return $errors;
    }

    /**
     * @param $surveyResult
     * @param $userId
     * @param $jobId
     * @param $surveyId
     * @return array|string
     */
    protected function validateUpdate($surveyResult,$userId,$jobId,$surveyId){
        $errors = array();
        if(!$surveyResult){
            $errors = 'The survey result you are looking is not found.Please enter a valid survey result id';
        }
        if($userId || empty($userId)){
            $user = User::find($userId);
            if(!$user){
                $errors = 'Please enter a valid user_id';
            }
        }
        if($jobId || empty($surveyId)){
            $job = Job::find($jobId);
            if(!$job){
                $errors = 'Please enter a valid job_id';
            }
        }
        if($surveyId || empty($surveyId)){
           $survey = Surveys::find($surveyId);
            if(!$survey){
               $errors = 'Please enter a valid survey_id';
            }
        }
        return $errors;
    }

    /**
     * @param $surveyResult
     * @return array|string
     */
    protected function validateRetrieveOne($surveyResult){
        $errors = array();
        if(!$surveyResult){
            $errors = "The validate result you are looking is not found.Please enter a valid validate result id";
        }
        return $errors;
    }

    /**
     * @param $surveyResult
     * @return array|string
     */
    protected function validateDelete($surveyResult){
        $errors = array();
        if(!$surveyResult){
            $errors = "The validate result you want to delete is not found.Please enter a valid validate result id";
        }
        return $errors;
    }
}