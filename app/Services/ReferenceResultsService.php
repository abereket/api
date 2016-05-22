<?php
namespace App\Services;

use App\Models\Reference;
use App\Models\ReferenceResult;
use App\Models\SurveySkills;
use App\Models\User;

class ReferenceResultsService extends Base{
    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
     $user = User::find($request->json()->get('user_id'));
     $reference = Reference::find($request->json()->get('reference_id'));
     $surveySkill = SurveySkills::find($request->json()->get('skill_id'));
     $valError = $this->validateCreate($user,$reference,$surveySkill);
     if($valError){
         $valError = $this->failureMessage($valError,parent::HTTP_404);
         return $valError;
     }
     $referenceResult = ReferenceResult::create(['user_id'=>$request->json()->get('user_id'),
                                                 'reference_id'=>$request->json()->get('reference_id'),
                                                 'skill_id'=>$request->json()->get('skill_id'),
                                                 'comments'=>$request->json()->get('comments'),
                                                 'skill_value'=>$request->json()->get('skill_value')]);
     $referenceResult = $this->buildCreateSuccessMessage("success",$referenceResult);
     return $referenceResult;
    }

    /**
     * @param $request
     * @param $id
     * @return array
     */
    public function update($request,$id){
     $referenceResult = ReferenceResult::find($id);
     $valError = $this->validateUpdate($referenceResult,$request->json()->get('user_id'),$request->json()->get('reference_id'),$request->json()->get('skill_id'));
     if($valError){
         $valError = $this->failureMessage($valError,parent::HTTP_404);
         return $valError;
     }
     $referenceResult->user_id = ($request->json()->get('user_id'))?$request->json()->get('user_id'):$referenceResult->user_id;
     $referenceResult->reference_id = ($request->json()->get('reference_id'))?$request->json()->get('reference_id'):$referenceResult->reference_id;
     $referenceResult->skill_id = ($request->json()->get('skill_id'))?$request->json()->get('skill_id'):$referenceResult->skill_id;
     $referenceResult->comments = ($request->json()->get('comments'))?$request->json()->get('comments'):$referenceResult->comments;
     $referenceResult->skill_value = ($request->json()->get('skill_value'))?$request->json()->get('skill_value'):$referenceResult->skill_value;

     $referenceResult->save();
     $referenceResult = $this->buildUpdateSuccessMessage("success",$referenceResult);
     return $referenceResult;
    }

    /**
     * @param $request
     * @return ReferenceResult|array
     */
    public function retrieve($request){
        $limit          =   ($request->input('per_page'))?$request->input('per_page'):15;
        $userId         =    $request->input('user_id');
        $referenceId    =    $request->input('reference_id');
        $orderBy        =   ($request->input('order_by'))?$request->input('order_by'):'created_at';
        $sortBy         =   ($request->input('sort_by'))?$request->input('sort_by'):'DESC';
        $search         =    $this->searchValueExists($userId,$referenceId);
        if($search){
            $valError = $this->buildEmptyErrorResponse(parent::HTTP_404);
            return $valError;
        }
        $referenceResult = new ReferenceResult();
        if($userId){
            $referenceResult = $referenceResult->where('user_id','=',$userId);
        }
        if($referenceId){
            $referenceResult = $referenceResult->where('reference_id','=',$referenceId);
        }
        $referenceResult = $referenceResult->orderby($orderBy,$sortBy)->paginate($limit);

        $referenceResult = $this->buildRetrieveResponse($referenceResult->toArray());
        $referenceResult = $this->buildRetrieveSuccessMessage('success',$referenceResult);

        return $referenceResult;
    }

    /**
     * @param $id
     * @return array
     */
    public function retrieveOne($id){
        $referenceResult = ReferenceResult::find($id);
        $valError = $this->validateRetrieveOne($referenceResult);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $referenceResult = $this->buildRetrieveOneSuccessMessage("success",$referenceResult);
        return $referenceResult;
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id){
        $referenceResult = ReferenceResult::find($id);
        $valError = $this->validateDelete($referenceResult);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $referenceResult->delete();
        $referenceResult = $this->buildDeleteSuccessMessage('success');
        return $referenceResult;
    }

    /**
     * @param $user
     * @param $reference
     * @param $skill
     * @return array
     */
    protected function validateCreate($user,$reference,$skill){
        $errors = array();
        if(!$user){
            $errors['user_id'] = 'Please provide a valid user id';
        }
        if(!$reference){
            $errors['reference_id'] = 'Please provide a valid reference id';
        }
        if(!$skill){
            $errors['skill_id'] = 'Please provide a valid referenec id';
        }
        return $errors;
    }

    /**
     * @param $referenceResult
     * @param $userId
     * @param $referenceId
     * @param $skillId
     * @return array
     */
    protected function validateUpdate($referenceResult,$userId,$referenceId,$skillId){
        $errors = array();
        if(!$referenceResult){
           $errors['reference_result_id'] = 'Please provide a valid reference result id';
        }
        if($userId){
            $user = User::find($userId);
            if(!$user){
                $errors['user_id'] = 'Please provide a valid user id';
            }
        }
        if($referenceId){
            $reference = Reference::find($referenceId);
            if(!$reference){
                $errors['reference_id'] = 'Please provide a valid reference id';
            }
        }
        if($skillId){
            $skill = SurveySkills::find($skillId);
            if(!$skill){
                $errors['skill_id'] = 'Please provide a valid survey skill id';
            }
        }
        return $errors;
    }

    /**
     * @param $referenceResult
     * @return array
     */
    protected function validateRetrieveOne($referenceResult){
        $errors = array();
        if(!$referenceResult){
            $errors['reference_result_id'] = 'Please provide a valid reference result id';
        }
        return $errors;
    }

    /**
     * @param $referenceResult
     * @return array
     */
    protected function validateDelete($referenceResult){
        $errors = array();
        if(!$referenceResult){
            $errors['reference_result_id'] = 'Please provide a valid reference result id';
        }
        return $errors;
    }
}