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
        //Perform business specific validations
        $userService = new UsersService();
        $user        =    $userService->retrieveOne($request->json()->get('userId'));
        $valError    =   $this->validateCreate($user);
        if ($valError) {
            return $valError;
        }

        //create the agency
        $agency = Agency::create(['uuid' =>  Uuid::uuid(),'name'=>$request->json()->get('name'),'user_id'=>$request->json()->get('userId'),'description'=>$request->json()->get('description')]);

        //Create email verification entry
        $emailVerification = new EmailVerificationsService();
        $code = $emailVerification->create('agency',$agency->user_id);

        //Send agency-user activation email
        $emailService = new EmailsService();
        $from         = "info@zemployee.com";
        $subject      = "Agency, please active your email";
        $body         = "Please click the link below to active your account " . $code;
        $emailService->send($user->email, $from, $subject, $body);

        //build success message
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
        $userService = new UsersService();
        $user  =    $userService->retrieveOne($agency->user_id);
        $user = ($user instanceof User)?$user:null;
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
        $valError = $this->validateUpdate($agency, $request->json()->get('userId'));
        if($valError){
            return $valError;
        }

        $agency->name         =   ($request->json()->get('name'))?($request->json()->get('name')):$agency->name;
        $agency->description  =   ($request->json()->get('description'))?($request->json()->get('description')):$agency->description;
        $agency->user_id      =   ($request->json()->get('userId'))?($request->json()->get('userId')):$agency->user_id;
        $agency->save();
        $userService = new UsersService();
        $user  =    $userService->retrieveOne($agency->user_id);
        $user = ($user instanceof User)?$user:null;
        $agency->user   = $user;
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
     * @param $user
     * @return array
     */
    protected function validateCreate($user){
        $errors = array();
        if (! $user instanceof User){
            $errors = array_merge($errors, $user);
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
     * @param $userId
     * @return array
     */
    protected function validateUpdate($agency, $userId){
        $errors = array();
        if(!$agency){
            $errors[] = array("message" => "please provide a valid agency");
        }
        if($userId) {
            $userService = new UsersService();
            $user = $userService->retrieveOne($userId);
            if(!$user instanceof User){
                $message=array("message"=>"The value you entered not exists.please enter a valid user id");
                return $message;
            }
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