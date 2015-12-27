<?php
namespace App\Services;

use App\Models\Agency;
use App\Models\User;
use Faker\Provider\Uuid;

class AgenciesService
{
    /**
     * creates an agency
     * @param $request
     * @return static
     */
    public function create($request)
    {
        $userService = new UsersService();
        $user  =    $userService->create($request,"agency");
        if(!$user instanceof User){
            return $user;
        }

        $agency = Agency::create(['uuid' =>  Uuid::uuid(),'name'=>$request->input('name'),'user_id'=>$user->id,'description'=>$request->input('description')]);
        $valError        =   $this->validateCreate($agency);
        if($valError){
            return $valError;
        }
        $emailVerification = new EmailVerificationsService();
        $emailVerification->create('agency',$agency->user_id);
        $agency=$this->buildCreateSuccessMessage("success",$agency);

        return $agency;
    }

    /**
     * retrieves an(one) agency
     * @param $agency_id
     * @return array
     */
    public function retrieveOne($agency_id)
    {
        $agency=Agency::find($agency_id);
        $valError = $this->validateRetrieveOne($agency);
        if($valError){
            return $valError;
        }
        $user           =    User::find($agency->user_id);
        $agency->user   = $user;
        $object=$this->buildRetrieveOneSuccessMessage("success",$agency);
        return $object;
    }

    /**
     * updates an agency
     * @param $request
     * @param $agency_id
     * @return mixed
     */
    public function update($request,$agency_id)
    {
        $agency   = Agency::find($agency_id);
        $valError = $this->validateUpdate($agency);
        if($valError){
        return $valError;
        }
        $agency->name         =   $request->input('name');
        $agency->description  =   $request->input('description');
        $agency->user_id      =   $request->input('userId');
        $agency->save();

        $agency=$this->buildUpdateSuccessMessage("success",$agency);
        return $agency;
    }

    /**
     * @param $agency_id
     * @return array
     */
    public function delete($agency_id){
        $agency = Agency::find($agency_id);
        $valError = $this->validateDelete($agency);
        if ($valError) {
            return $valError;
        }
        $agency->delete();
        $agency = $this->buildDeleteSuccessMessage("success");
        return $agency;
    }

    /**
     * @param $agency
     * @return array
     */
    protected function validateCreate($agency){
        $errors = array();
        if(!$agency){
            $errors[] = array("message" => "please provide a valid agency");
        }
        return $errors;
    }

    /**
     * @param $agency
     * @return array
     */
    protected function validateRetrieveOne($agency){
        $errors = array();
        if(!$agency){
            $errors[] = array("message" => "please provide a valid agency");
        }
        return $errors;
    }

    /**
     * @param $agency
     * @return array
     */
    protected function validateUpdate($agency){
        $errors = array();
        if(!$agency){
            $errors[] = array("message" => "please provide a valid agency");
        }
        return $errors;
    }

    /**
     * @param $agency
     * @return array
     */
    protected function validateDelete($agency) {
        $errors = array();
        if (!$agency) {
            $errors[] = array("message" => "Please provide valid agency id");
        }

        return $errors;
    }

    //success messages

    /**
     * @param $successMessage
     * @return array
     */
    protected function buildDeleteSuccessMessage($successMessage){
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => 204);
        return $message;
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildCreateSuccessMessage($successMessage, $entity){
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => 200,'results' => $entity);
        return $message;
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildUpdateSuccessMessage($successMessage, $entity){
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => 201,'results' => $entity);
        return $message;
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildRetrieveOneSuccessMessage($successMessage, $entity){
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => 201,'results' => $entity);
        return $message;
    }
}

?>