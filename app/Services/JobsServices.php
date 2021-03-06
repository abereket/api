<?php
namespace App\Services;

use App\Models\Job;
use App\Models\User;

class JobsServices extends Base{

    /**
     * @param $request
     * @return mixed
     */
    public function create($request){
    $valError = $this->validateCreate($request->json()->get('user_id'),$request->json()->get('external_id'));
    if($valError){
        $valError = $this->failureMessage($valError,parent::HTTP_404);
        return $valError;
    }
    $job = Job::create(['user_id'=>$request->json()->get('user_id'),'external_id'=>$request->json()->get('external_id'),
                        'title'=>$request->json()->get('title'),'company_name'=>$request->json()->get('company_name'),
                        'link'=>$request->json()->get('link'), 'is_fulfilled'=>$request->json()->get('is_fulfilled',0),
                        'is_closed'=>$request->json()->get('is_closed',0),'is_active'=>$request->json()->get('is_active',0),
                        'city'=>$request->json()->get('city'),'state'=>$request->json()->get('state'),
                        'zip_code'=>$request->json()->get('zip_code')]);

    list($job->is_closed,$job->is_fulfilled,$job->is_active)=$this->assignBoolean($job->is_closed,$job->is_fulfilled,$job->is_active);
    $job = $this->buildCreateSuccessMessage('success',$job);
    return $job;

    }

    /**
     * @param $request
     * @param $id
     * @return mixed
     */
    public function update($request,$id){
        $job = Job::find($id);
        $valError = $this->validateUpdate($job,$request->json()->get('user_id'),$request->json()->get('external_id'));
        if($valError){
            $valError=$this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $job->user_id      =  $request->json()->get('user_id');
        $job->external_id  =  ($request->json()->get('external_id'))?$request->json()->get('external_id'):$job->external_id;
        $job->title        =  ($request->json()->get('title'))?$request->json()->get('title'):$job->title;
        $job->company_name =  ($request->json()->get('company_name'))?$request->json()->get('company_name'):$job->company_name;
        $job->link         =  ($request->json()->get('link'))?$request->json()->get('link'):$job->link;
        $job->is_fulfilled =  ($request->json()->get('is_fulfilled'))?$request->json()->get('is_fulfilled'):$job->is_fulfilled;
        $job->is_closed    =  ($request->json()->get('is_closed'))?$request->json()->get('is_closed'):$job->is_closed;
        $job->is_active    =  ($request->json()->get('is_active'))?$request->json()->get('is_active'):$job->is_active;
        $job->city         =  ($request->json()->get('city'))?$request->json()->get('city'):$job->city;
        $job->state        =  ($request->json()->get('state'))?$request->json()->get('state'):$job->state;
        $job->zip_code     =  ($request->json()->get('zip_code'))?$request->json()->get('zip_code'):$job->zip_code;
        $job->save();

        list($job->is_closed,$job->is_fulfilled,$job->is_active)=$this->assignBoolean($job->is_closed,$job->is_fulfilled,$job->is_active);
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

    /**
     * @param $id
     * @return array|string
     */
    public function retrieveOne($id){
        $job = Job::find($id);
        $valError = $this->validateRetrieveOne($job);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        list($job->is_closed,$job->is_fulfilled,$job->is_active)=$this->assignBoolean($job->is_closed,$job->is_fulfilled,$job->is_active);
        $job = $this->buildRetrieveOneSuccessMessage("success",$job);
        return $job;
    }

    /**
     * @param $request
     * @return Job
     */
    public function retrieve($request){
        $limit            =   ($request->input('per_page'))?($request->input('per_page')) : 15;
        $userId           =   $request->input('user_id');
        $externalId       =   $request->input('external_id');
        $title            =   $request->input('title');
        $companyName      =   $request->input('company_name');
        $is_closed        =   $request->input('is_closed');
        $city             =   $request->input('city');
        $state            =   $request->input('state');
        $zipCode          =   $request->input('zip_code');
        $orderBy          =   ($request->input('order_by'))? ($request->input('order_by')):'created_at';
        $sortBy           =   ($request->input('sort_by'))?$request->input('sort_by'):'DESC';
        $search           =  $this->searchValueExists($userId,$externalId,$title,$companyName,$is_closed,$city,$state,$zipCode);
        if($search){
            $entity = $this->buildEmptyErrorResponse(parent::HTTP_404);
            return $entity;
        }

        $job = new Job();

        if($userId){
           $job = $job->where('user_id' , '=', $userId);
        }
        if($externalId){
            $job = $job->where('external_id' , 'like', '%'.$externalId.'%');
        }
        if($title){
            $job = $job->where('title' , 'like', '%'.$title.'%');
        }
        if($companyName){
            $job = $job->where('company_name' , 'like', '%'.$companyName.'%');
        }
        if($city){
            $job = $job->where('city' , 'like', '%'.$city.'%');
        }
        if($state){
            $job = $job->where('state' , 'like', '%'.$state.'%');
        }
        if($zipCode){
            $job = $job->where('zip_code' , 'like', '%'.$zipCode.'%');
        }
        if($is_closed){
            $job = $job->where('is_closed', '=' , $is_closed);
        }
        $job = $job->orderby($orderBy,$sortBy)->Paginate($limit);

        $job = $this->buildRetrieveResponse($job->toArray());
        if(!empty($job['results'])){
            foreach($job['results'] as $results){
                list($results['is_closed'],$results['is_fulfilled'],$results['is_active'])=$this->assignBoolean($results['is_closed'],$results['is_fulfilled'],$results['is_active']);
                $result[]=$results;
            }
            $job['results'] = $result;
        }
        $job = $this->buildRetrieveSuccessMessage('success',$job);

        return $job;

    }

    /**
     * @param $userId
     * @param $externalId
     * @return array
     */
    protected function validateCreate($userId,$externalId){
        $errors = array();
        $user = User::find($userId);
        if(!$user){
            $errors['user_id'] = "The user id you have entered not exists.Please enter a valid user id";
        }
        $job = Job::where('external_id','=',$externalId)->first();
        if($job){
            $errors['external_id'] = "The external id you have entered should be unique";
        }
        return $errors;
    }

    /**
     * @param $job
     * @param $userId
     * @param $externalId
     * @return array
     */
    protected function validateUpdate($job,$userId,$externalId){
        $errors = array();
        if(!$job){
            $errors['job_id'] = "The job you are looking for not exists.Please use a valid job id";
        }
        $user = User::find($userId);
        if(!$user){
            $errors['user_id']="The user id you have entered not exists.Please enter a valid user id";
        }
        $job = Job::where('external_id','=',$externalId)->first();
        if($job){
            $errors['external_id'] = "The external id you have entered should be unique";
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
            $errors['job_id'] = "The job you are looking for not exists.Please use a valid job id";
        }
        return $errors;
    }

    /**
     * @param $job
     * @return array|string
     */
    protected function validateRetrieveOne($job){
        $errors = array();
        if(!$job){
           $errors['job_id'] ="The job you are looking for not exists.Please use a valid job id";
        }
        return $errors;
    }

    /**
     * @param $isClosed
     * @param $isFulfilled
     * @param $isActive
     * @return array
     */
    protected function assignBoolean($isClosed,$isFulfilled,$isActive){
        $isClosed     =  (bool)$isClosed;
        $isFulfilled  =  (bool)$isFulfilled;
        $isActive     =  (bool)$isActive;

        return array($isClosed,$isFulfilled,$isActive);
    }
}