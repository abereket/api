<?php
namespace App\Services;

use App\Models\Surveys;
use App\Models\SurveySkills;
use App\Models\User;

class SurveySkillsService extends Base{

    /**
     * @param $request
     * @return array
     */
    public function create($request){
        $valError = $this->validateCreate($request->json()->get('user_id'),$request->json()->get('survey_id'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $skill_names = $request->json()->get('skill_names');
        for($i=0;$i<count($skill_names);$i++) {
            $surveySkills[] = $this->upsert($request->json()->get('user_id'),$request->json()->get('survey_id'),$skill_names[$i]['skill_name']);
        }
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
            $errors['user_id'] = 'Please enter a valid user_id';
        }
        $survey = Surveys::find($surveyId);
        if(!$survey){
            $errors['survey_id'] = 'Please enter a valid survey_id';
        }
        return $errors;
    }

    /**
     * @param $userId
     * @param $surveyId
     * @param $skillName
     * @return static
     */
    protected function upsert($userId,$surveyId,$skillName){
        $surveySkill = SurveySkills::where('skill_name',$skillName)
                                     ->where('survey_id',$surveyId)
                                     ->first();
        if(!$surveySkill){
            $surveySkill = SurveySkills::create(['user_id'=>$userId,'survey_id'=>$surveyId,'skill_name' => $skillName]);
        }
        return $surveySkill;
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
           $errors['survey_skills_id'] = 'The survey skills you are looking is not found please enter a valid id';
        }
        if($userId){
            $user = User::find($userId);
            if(!$user){
                $errors['user_id'] = 'Please enter a valid user_id';
            }
        }
        if($surveyId){
            $survey = Surveys::find($surveyId);
            if(!$survey){
                $errors['survey_id'] = 'Please enter a valid survey_id';
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
            $errors['survey_skills_id'] = 'The survey skills you are looking is not found.Please enter a valid id';
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
            $errors['survey_skills_id'] = 'The survey skills you want to delete is not found.Please enter a valid id';
        }
        return $errors;
    }
}