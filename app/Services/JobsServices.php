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
    $valError = $this->validateCreate($request->json()->get('user_id'));
    if($valError){
        $valError = $this->failureMessage($valError,parent::HTTP_404);
        return $valError;
    }
    $job = Job::create(['user_id'=>$request->json()->get('user_id'),'tittle'=>$request->json()->get('tittle'),
                        'company_name'=>$request->json()->get('company_name'),'type'=>$request->json()->get('type'),
                        'link'=>$request->json()->get('link'),'is_fulfilled'=>$request->json()->get('is_fulfilled',0),
                        'is_closed'=>$request->json()->get('is_closed',0),'city'=>$request->json()->get('city'),
                        'state'=>$request->json()->get('state'),'zip_code'=>$request->json()->get('zip_code')]);

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
        $valError = $this->validateUpdate($job,$request->json()->get('user_id'));
        if($valError){
            $valError=$this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $job->user_id      =  $request->json()->get('user_id');
        $job->tittle       =  $request->json()->get('tittle',$job->tittle);
        $job->company_name =  $request->json()->get('company_name',$job->company_name);
        $job->type         =  $request->json()->get('type',$job->type);
        $job->link         =  $request->json()->get('link',$job->link);
        $job->is_fulfilled =  $request->json()->get('is_fulfilled',$job->is_fulfilled);
        $job->is_closed    =  $request->json()->get('is_closed',$job->is_closed);
        $job->city         =  $request->json()->get('city',$job->city);
        $job->state        =  $request->json()->get('state',$job->state);
        $job->zip_code     =  $request->json()->get('zip_code',$job->zip_code);
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
        $tittle           =   $request->input('tittle');
        $companyName      =   $request->input('company_name');
        $type             =   $request->input('type');
        $is_closed        =   $request->input('is_closed');
        $city             =   $request->input('city');
        $state            =   $request->input('state');
        $zipCode          =   $request->input('zip_code');
        $orderBy         =   ($request->input('order_by'))? ($request->input('order_by')):'created_at';
        $sortBy          =   ($request->input('sort_by'))?$request->input('sort_by'):'DESC';



        $job = new Job();

        if($userId){
           $job = $job->where('user_id' , 'like', '%'.$userId.'%');
        }
        if($tittle){
            $job = $job->where('tittle' , 'like', '%'.$tittle.'%');
        }
        if($companyName){
            $job = $job->where('company_name' , 'like', '%'.$companyName.'%');
        }
        if($type){
            $job = $job->where('type' , 'like', '%'.$type.'%');
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
        $job = $this->buildRetrieveSuccessMessage('success',$job);

        return $job;

    }
    /**
     * @param $userId
     * @return array|string
     */
    protected function validateCreate($userId){
        $errors = array();
        $user = User::find($userId);
        if(!$user){
            $errors['user_id'] = "The user id you have entered not exists.Please enter a valid user id";
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
            $errors['job_id'] = "The job you are looking for not exists.Please use a valid job id";
        }
        $user = User::find($userId);
        if(!$user){
            $errors['user_id']="The user id you have entered not exists.Please enter a valid user id";
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
}