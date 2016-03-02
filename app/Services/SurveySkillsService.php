<?php
namespace App\Services;

use App\Models\Surveys;
use App\Models\SurveySkills;
use App\Models\User;

class SurveySkillsService extends Base{

    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
        $valError = $this->validateCreate($request->json()->get('user_id'),$request->json()->get('survey_id'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $surveySkills = SurveySkills::create(['user_id'=>$request->json()->get('user_id'),'survey_id'=>$request->json()->get('survey_id'),
                                              'skill_name'=>$request->json()->get('skill_name')]);

        $surveySkills = $this->buildCreateSuccessMessage('success',$surveySkills);
        return $surveySkills;
    }

    /**
     * @param $request
     * @param $id
     * @return array
     */
    public function update($request,$id){
       $surveySkills = SurveySkills::find($id);
       $valError = $this->validateUpdate($surveySkills,$request->json()->get('user_id'),$request->json()->get('survey_id'));
       if($valError){
           $valError = $this->failureMessage($valError,parent::HTTP_404);
           return $valError;
       }
       $surveySkills->user_id    =($request->json()->get('user_id'))?$request->json()->get('user_id'):$surveySkills->user_id;
       $surveySkills->survey_id  =($request->json()->get('survey_id'))?$request->json()->get('survey_id'):$surveySkills->survey_id;
       $surveySkills->skill_name =($request->json()->get('skill_name'))?$request->json()->get('skill_name'):$surveySkills->skill_name;
       $surveySkills->save();

       $surveySkills = $this->buildUpdateSuccessMessage('success',$surveySkills);
       return $surveySkills;
    }

    /**
     * @param $request
     * @return SurveySkills|array
     */
    public function retrieve($request){
        $limit      =   ($request->input('per_page'))?$request->input('per_page'):15;
        $userId     =   $request->input('user_id');
        $surveyId   =   $request->input('survey_id');
        $orderBy    =   ($request->input('order_by'))?$request->input('order_by'):'created_at';
        $sortBy     =   ($request->input('sort_by'))?$request->input('sort_by'):'DESC';

        $surveySkills = new SurveySkills();
        if ($userId) {
            $surveySkills = $surveySkills->where('user_id' , 'like', '%'.$userId.'%');
        }
        if ($surveyId) {
            $surveySkills = $surveySkills->where('survey_id',  'like', '%'.$surveyId.'%');
        }
        $surveySkills= $surveySkills->orderby($orderBy,$sortBy)->paginate($limit);

        $surveySkills = $this->buildRetrieveResponse($surveySkills->toArray());
        $surveySkills = $this->buildRetrieveSuccessMessage("success",$surveySkills);
        return $surveySkills;
    }
    public function retrieveOne($id){
        $surveySkills = SurveySkills::find($id);
        $valError = $this->validateRetrieveOne($surveySkills);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $surveySkills = $this->buildRetrieveOneSuccessMessage('success',$surveySkills);
        return $surveySkills;
    }

    /**
     * @param $id
     * @return array|string
     */
    public function delete($id){
        $surveySkills = SurveySkills::find($id);
        $valError = $this->validateDelete($surveySkills);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $surveySkills->delete();
        $surveySkills = $this->buildDeleteSuccessMessage('success');
        return $surveySkills;

    }
    /**
     * @param $userId
     * @param $surveyId
     * @return array|string
     */
    protected function validateCreate($userId,$surveyId){
        $errors = array();
        $user = User::find($userId);
        if(!$user){
            $errors = 'Please enter a valid user_id';
        }
        $survey = Surveys::find($surveyId);
        if(!$survey){
            $errors = 'Please enter a valid survey_id';
        }
        return $errors;
    }

    /**
     * @param $survey
     * @param $userId
     * @param $surveyId
     * @return array|string
     */
    protected function validateUpdate($survey,$userId,$surveyId){
        $errors = array();
        if(!$survey){
           $errors = 'The survey skills you are looking is not found please enter a valid id';
        }
        if($userId){
            $user = User::find($userId);
            if(!$user){
                $errors = 'Please enter a valid user_id';
            }
        }
        if($surveyId){
            $survey = Surveys::find($surveyId);
            if(!$survey){
                $errors = 'Please enter a valid survey_id';
            }
        }
        return $errors;
    }

    /**
     * @param $surveySkills
     * @return array|string
     */
    protected function validateRetrieveOne($surveySkills){
        $errors = array();
        if(!$surveySkills){
            $errors = 'The survey skills you are looking is not found.Please enter a valid id';
        }
        return $errors;
    }

    /**
     * @param $surveySkills
     * @return array|string
     */
    protected function validateDelete($surveySkills){
        $errors = array();
        if(!$surveySkills){
            $errors = 'The survey skills you want to delete is not found.Please enter a valid id';
        }
        return $errors;
    }
}