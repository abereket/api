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
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function update($request){
       $valError = $this->validateUpdate($request->json()->get('user_id'),$request->json()->get('survey_id'));
       if($valError){
           $valError = $this->failureMessage($valError,parent::HTTP_404);
           return $valError;
       }
       $existingSkills = $this->getAllCurrentSkillsForThisSurvey($request->json()->get('survey_id'));
       if(!$existingSkills->count()){
           $currentSkills = array();
       }
       for($i=0;$i<count($existingSkills);$i++){
            $currentSkills[] = $existingSkills[$i]->skill_name;
       }
       $skillNames = $request->json()->get('skill_names');
       foreach ($skillNames as $skills) {
           foreach ($skills as $skill) {
               $updatedSkills[] = $skill;
           }
       }
        $newSkills = array_diff($updatedSkills,$currentSkills);
        foreach($newSkills as $skill){
             $this->upsert($request->json()->get('user_id'), $request->json()->get('survey_id'), $skill);
        }
        $surveySkills = $this->getAllCurrentSkillsForThisSurvey($request->json()->get('survey_id'));
        $surveySkills = $this->buildUpdateSuccessMessage('success',$surveySkills);
        return $surveySkills;
    }

    /**
     * @param $request
     * @return SurveySkills|array
     */
    public function retrieve($request){
        $limit      =   ($request->input('per_page'))?$request->input('per_page'):15;
        $userId     =    $request->input('user_id');
        $surveyId   =    $request->input('survey_id');
        $orderBy    =   ($request->input('order_by'))?$request->input('order_by'):'created_at';
        $sortBy     =   ($request->input('sort_by'))?$request->input('sort_by'):'DESC';
        $search     =    $this->searchValueExists($userId,$surveyId);
        if($search){
            $valError = $this->buildEmptyErrorResponse(parent::HTTP_404);
            return $valError;
        }
        $surveySkills = new SurveySkills();
        if ($userId) {
            $surveySkills = $surveySkills->where('user_id' , '=', $userId);
        }
        if ($surveyId) {
            $surveySkills = $surveySkills->where('survey_id',  '=', $surveyId);
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
        $surveySkill = SurveySkills::create(['user_id'=>$userId,'survey_id'=>$surveyId,'skill_name' => $skillName]);
        return $surveySkill;
    }

    /**
     * @param $userId
     * @param $surveyId
     * @return array
     */
    protected function validateUpdate($userId,$surveyId){
        $errors = array();
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
     * @param $surveyId
     * @return mixed
     */
    protected function getAllCurrentSkillsForThisSurvey($surveyId){
        $surveySkills = SurveySkills::where('survey_id',$surveyId)
                                      ->get();
        return $surveySkills;
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