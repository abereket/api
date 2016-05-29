<?php
namespace App\Services;

use App\Models\Reference;
use App\Models\User;

class ReferencesService extends Base{
    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
        $user = User::find($request->json()->get('user_id'));
        $candidate = User::find($request->json()->get('candidate_id'));
        $valError = $this->validateCreate($user,$candidate);
        if($valError){
           $valError = $this->failureMessage($valError,parent::HTTP_404);
           return $valError;
        }
        $reference = Reference::create(['user_id'=>$request->json()->get('user_id'),
                                        'candidate_id'=>$request->json()->get('candidate_id'),
                                        'first_name'=>$request->json()->get('first_name'),
                                        'last_name'=>$request->json()->get('last_name'),
                                        'email'=>$request->json()->get('email'),
                                        'company_with_candidate'=>$request->json()->get('company_with_candidate'),
                                        'position'=>$request->json()->get('position'),
                                        'relationship'=>$request->json()->get('relationship'),
                                        'contact_mobile'=>$request->json()->get('contact_mobile')]);

        $user = User::find($reference->candidate_id);
        $invitedBy = $user->first_name." ".$user->last_name;
        $orgName =$reference->first_name." ".$reference->last_name;
        $emailVerification = new EmailVerificationsService();
        $code = $emailVerification->create($user->type,$user->id);

        //Send user activation email
        $emailService = new EmailsService();
        $from         = "info@zemployee.com";
        $subject      = " ";
        $body         = "Please click the link below to active your account " . $code;
        $templateId   = "4b1bc046-6ba1-45e2-afec-11ec6ad50846";
        $emailService->send($reference->email, $from, $subject, $body,$invitedBy,$code,$templateId,$orgName);

        $reference = $this->buildCreateSuccessMessage("success",$reference);
        return $reference;
    }

    /**
     * @param $request
     * @param $id
     * @return array
     */
    public function update($request,$id){
        $reference = Reference::find($id);
        $valError = $this->validateUpdate($reference,$request->json()->get('user_id'),$request->json()->get('candidate_id'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $reference->user_id=($request->json()->get('user_id'))?$request->json()->get('user_id'):$reference->user_id ;
        $reference->candidate_id=($request->json()->get('candidate_id'))?$request->json()->get('candidate_id'):$reference->candidate_id;
        $reference->first_name=($request->json()->get('first_name'))?$request->json()->get('first_name'):$reference->first_name;
        $reference->last_name=($request->json()->get('last_name'))?$request->json()->get('last_name'):$reference->last_name;
        $reference->email=($request->json()->get('email'))?$request->json()->get('email'):$reference->email;
        $reference->company_with_candidate=($request->json()->get('company_with_candidate'))?$request->json()->get('company_with_candidate'):$reference->company_with_candidate;
        $reference->position=($request->json()->get('position'))?$request->json()->get('position'):$reference->position;
        $reference->relationship=($request->json()->get('relationship'))?$request->json()->get('relationship'):$reference->relationship;
        $reference->contact_mobile=($request->json()->get('contact_mobile'))?$request->json()->get('contact_mobile'):$reference->contact_mobile;
        $reference->save();

        $reference = $this->buildUpdateSuccessMessage("success",$reference);
        return $reference;

    }

    /**
     * @param $request
     * @return Reference|array
     */
    public function retrieve($request){
        $limit          =   ($request->input('per_page'))?$request->input('per_page'):15;
        $userId         =    $request->input('user_id');
        $candidateId    =    $request->input('candidate_id');
        $orderBy        =   ($request->input('order_by'))?$request->input('order_by'):'created_at';
        $sortBy         =   ($request->input('sort_by'))?$request->input('sort_by'):'DESC';
        $search         =    $this->searchValueExists($userId,$candidateId);
        if($search){
            $valError = $this->buildEmptyErrorResponse(parent::HTTP_404);
            return $valError;
        }
        $reference = new Reference();
        if($userId){
          $reference = $reference->where('user_id','=',$userId);
        }
        if($candidateId){
           $reference = $reference->where('candidate_id','=',$candidateId);
        }
        $reference = $reference->orderby($orderBy,$sortBy)->paginate($limit);
        $reference = $this->buildRetrieveResponse($reference->toArray());
        $reference = $this->buildRetrieveSuccessMessage("success",$reference);
        return $reference;
    }

    /**
     * @param $id
     * @return array
     */
    public function retrieveOne($id){
        $reference = Reference::find($id);
        $valError= $this->validateRetrieveOne($reference);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
         }
         $reference = $this->buildRetrieveOneSuccessMessage("success",$reference);
         return $reference;
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id){
         $reference = Reference::find($id);
         $valError = $this->validateDelete($reference);
         if($valError){
             $valError = $this->failureMessage($valError,parent::HTTP_404);
             return $valError;
         }
        $reference->delete();
        $reference = $this->buildDeleteSuccessMessage("success");
        return $reference;
    }

    /**
     * @param $user
     * @param $candidate
     * @return array
     */
    protected function validateCreate($user,$candidate){
        $errors = array();
        if(!$user){
            $errors['user_id'] = "please provide a valid user id";
        }
        if(!$candidate){
            $errors['candidate_id'] = "please provide a valid candidate id";
        }
        return $errors;
    }

    /**
     * @param $reference
     * @param string $userId
     * @param string $candidateId
     * @return array
     */
    protected function validateUpdate($reference,$userId='',$candidateId=''){
        $errors = array();
        if(!$reference){
            $errors['reference_id'] = "The reference you want to update does not exist.Please enter a valid reference id";
        }
        if($userId){
            $user = User::find($userId);
            if(!$user){
                $errors['user_id'] = "please provide a valid user id";
            }
        }
        if($candidateId){
            $user = User::find($candidateId);
            if(!$user){
                $errors['candidate_id'] = "please provide a valid candidate id";
            }
        }
        return $errors;
    }

    /**
     * @param $reference
     * @return array
     */
    protected function validateRetrieveOne($reference){
        $errors = array();
        if(!$reference){
            $errors['reference_id'] = "The reference you are looking does not exist.Please enter a valid reference id";
        }
        return $errors;
    }

    /**
     * @param $reference
     * @return array
     */
    protected function validateDelete($reference){
        $errors = array();
        if(!$reference){
            $errors['reference_id'] = "The reference you want to update does not exist.Please enter a valid reference id";
        }
        return $errors;
    }
}