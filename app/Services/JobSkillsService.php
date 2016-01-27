<?php
namespace App\Services;

use App\Models\Job;
use App\Models\JobSkills;

class JobSkillsService extends Base{

    /**
     * creates job skills
     * @param $request
     * @return array
     */
    public function create($request){
     $valError = $this->validateCreate($request->json()->get('job_id'));
     if($valError){
        $valError = $this->failureMessage($valError,parent::HTTP_404);
        return $valError;
     }
     $names = $request->json()->get('names');
     for($i=0; $i<count($names); $i++) {
         $jobSkill[] = JobSkills::create(['job_id'=>$request->json()->get('job_id'),'name'=>$names[$i]['name']]);
     }

     $jobSkill = $this->buildCreateSuccessMessage("success",$jobSkill);
     return $jobSkill;
    }

    /**
     * deletes job skills
     * @param $id
     * @return array|string
     */
    public function delete($id){
        $jobSkill = JobSkills::find($id);
        $valError  = $this->validateDelete($jobSkill);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $jobSkill->delete();
        $jobSkill = $this->buildDeleteSuccessMessage("success");
        return $jobSkill;
    }

    /**
     * retrieves one job skill
     * @param $id
     * @return array|string
     */
    public function retrieveOne($id){
        $jobSkill = JobSkills::find($id);
        $valError = $this->validateRetrieveOne($jobSkill);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $jobSkill = $this->buildRetrieveOneSuccessMessage("success",$jobSkill);
        return $jobSkill;
    }

    /**
     * searches job skills
     * @param $request
     * @return JobSkills|array
     */
    public function retrieve($request){
        $limit    =  ($request->input('per_page'))?$request->input('per_page'):15;
        $jobId    =  $request->input('job_id');
        $name     =  $request->input('name');
        $orderBy  =  ($request->input('order_by'))?$request->input('order_by'):'updated_at';

            $jobSkill = new JobSkills();
        if($jobId){
           $jobSkill = $jobSkill->where('job_id', 'like' ,'%'.$jobId.'%');
        }
        if($name){
            $jobSkill= $jobSkill->where('name', 'like' ,'%'.$name.'%');
        }
        $jobSkill = $jobSkill->orderby($orderBy)->Paginate($limit);
        $jobSkill = $this->buildRetrieveResponse($jobSkill->toArray());
        $jobSkill = $this->buildRetrieveSuccessMessage("success",$jobSkill);
        return $jobSkill;
    }

    /**
     * updates job skills
     * @param $request
     * @param $id
     * @return array
     */
    public function update($request,$id){
        $jobSkill = JobSkills::find($id);
        $valError = $this->validateUpdate($jobSkill,$request->json()->get('job_id'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $search= $this->deleteSkillByJobId($request->json()->get('job_id'));
        $len = count($search);
        for($i = 0;$i < $len;$i++){
            $jobSkill = JobSkills::find($search[$i]['id']);
            $jobSkill->delete();
        }
        $jobSkill = $this->create($request);
        return $jobSkill;

    }

    /**
     * business validation for create method
     * @param $jobId
     * @return array|string
     */
    protected function validateCreate($jobId){
        $errors = array();
        $job = Job::find($jobId);
        if(!$job){
            $errors = "You have entering a job which does not exist.Please enter a valid job_id";
        }
        return $errors;
    }

    /**
     * business validation for delete method
     * @param $jobSkill
     * @return array|string
     */
    protected function validateDelete($jobSkill){
        $errors = array();
        if(!$jobSkill){
            $errors = "The job skill you are looking for not exists.Please enter a valid job skill id";
        }
        return $errors;
    }

    /**
     * business validation for retrieve method
     * @param $jobSkill
     * @return array|string
     */
    protected function validateRetrieveOne($jobSkill){
        $errors = array();
        if(!$jobSkill){
            $errors = "The job skill you are looking for not exists.Please enter a valid job skill id";
        }
        return $errors;
    }

    /**
     * business validation for update method
     * @param $jobSkill
     * @param $jobId
     * @return array|string
     */
    protected function validateUpdate($jobSkill,$jobId){
        $errors = array();
        if(!$jobSkill){
            $errors = "The job skill you are looking for not exists.Please enter a valid job skill id";
        }
        $job = Job::find($jobId);
        if(!$job){
            $errors = "The job-id  you are entering is not valid.Please enter a valid job id";
        }
        return $errors;
    }

    /**
     * @param $jobId
     * @return mixed
     */
    protected function deleteSkillByJobId($jobId){
        $jobSkill = JobSkills::where('job_id',$jobId)->get();
        return $jobSkill;

    }
}