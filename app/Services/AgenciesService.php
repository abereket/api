<?php
namespace App\Services;

use App\Models\Agency;
use App\Models\User;
use Faker\Provider\Uuid;
//use app\Services\Base;

class AgenciesService extends Base
{
    /**
     * creates an agency
     * @param $request
     * @return static
     */
    public function create($request)
    {
        //Perform business specific validations
        $user        =    User::find($request->json()->get('userId'));
        $valError    =   $this->validateCreate($user);
        if ($valError) {
            $valError = $this->failureMessage($valError,Parent::HTTP_404);
            return $valError;
        }

        //create the agency
        $agency   =   Agency::create(['uuid' =>  Uuid::uuid(),'name'=>$request->json()->get('name'),'user_id'=>$request->json()->get('userId'),'description'=>$request->json()->get('description')]);

        //Create email verification entry
        $emailVerification     =  new EmailVerificationsService();
        $code                  =  $emailVerification->create('agency',$agency->user_id);
        //Send agency-user activation email
        $emailService = new EmailsService();
        $from         = "info@zemployee.com";
        $subject      = "Agency, please active your email";
        $body         = "Please click the link below to active your account " . $code;

        $emailService->send($user->email, $from, $subject, $body);


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
        $agency            =     Agency::find($agency_id);
        $valError          =     $this->validateRetrieveOne($agency);
        if($valError){
            $valError      =     $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $agency=$this->buildRetrieveOneSuccessMessage("success",$agency);
        return $agency;
    }

    /**
     * updates an agency
     * @param $request
     * @param $agency_id
     * @return mixed
     */
    public function update($request,$agency_id)
    {
        $agency        =     Agency::find($agency_id);
        $valError      =     $this->validateUpdate($agency, $request->json()->get('userId'));
        if($valError){
            $valError  =     $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }

        $agency->name         =   ($request->json()->get('name'))?($request->json()->get('name')):$agency->name;
        $agency->description  =   ($request->json()->get('description'))?($request->json()->get('description')):$agency->description;
        $agency->user_id      =   ($request->json()->get('userId'))?($request->json()->get('userId')):$agency->user_id;
        $agency->save();

        $agency               =    $this->buildUpdateSuccessMessage("success",$agency);
        return $agency;
    }

    /**
     * @param $agency_id
     * @return array
     */
    public function delete($agency_id){
        $agency           =   Agency::find($agency_id);
        $valError         =   $this->validateDelete($agency);
        if ($valError){
            $valError     =   $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $agency->delete();
        $agency           =   $this->buildDeleteSuccessMessage("success");
        return $agency;
    }

    /**
     * This method performs business class validation for agencies create method
     * @param $user
     * @return array
     */
    protected function validateCreate($user){
        $errors        =    array();
        if (!$user){
            $errors    =    "Please provide a valid user id.The value you entered not exists";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for agencies retrieveOne method
     * @param $agency
     * @return array
     */
    protected function validateRetrieveOne($agency){
        $errors       =    array();
        if(!$agency){
            $errors   =    "please provide a valid agency";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for agencies update method
     * @param $agency
     * @param $userId
     * @return array
     */
    protected function validateUpdate($agency, $userId){
        $errors        =  array();
        if(!$agency){
            $errors[]    =  "please provide a valid agency";
        }
        if($userId) {
            $user      =   User::find($userId);
            if(!$user){
                $message  =  array("message"=>"The value you entered not exists.please enter a valid user id");
                return $message;
            }
        }
        return $errors;

    }

    /**
     * This method performs business class validation for agencies delete method
     * @param $agency
     * @return array
     */
    protected function validateDelete($agency) {
        $errors        =   array();
        if (!$agency){
            $errors    =  array("message" => "Please provide valid agency id");
        }

        return $errors;
    }
}

?>