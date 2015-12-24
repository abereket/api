<?php
namespace App\Services;

use App\Models\Agency;
use App\Models\User;
use App\Services\UsersService;


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
        $user          =    $userService->create($request);
        $valError      =    $this->validateCreate($user);
        if($valError){
         return $valError;
        }
        $agency=Agency::create(['name'=>$request->input('name'),'user_id'=>$request->input('userId'),'description'=>$request->input('description')]);
        //send invitation email to the user
        $valError        =   $this->validateCreateA($agency);
        if($valError){
         return $valError;
        }
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
        $valError = $this->ValidateRetrieveOneA($agency);
        if($valError){
            return $valError;
        }
        $user     = User::find($agency->user_id);
        $valError   =$this->ValidateRetrieveOne($user);
        if($valError) {
            return $valError;
        }

        $object=array('agency'=>$agency,
                      'user'=>array('id'=>$agency->user_id,'first_name'=>$user->first_name,'last_name'=>$user->last_name, 'email'=>$user->email));
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
        return $agency;
    }


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

    //Buisness class validation

    protected function validateCreate($user){
        $errors = array();
        if(!$user){
            $errors[] = "please provide a valid user";
        }
        return $errors;
    }
    protected function validateCreateA($agency){
        $errors = array();
        if(!$agency){
            $errors[] = "please provide a valid agency";
        }
        return $errors;
    }
    protected function validateRetrieveOneA($agency){
        $errors = array();
        if(!$agency){
            $errors[] = "please provide a valid agency";
        }
        return $errors;
    }
    protected function validateRetrieveOne($user){
        $errors = array();
        if(!$user){
            $errors[] = "please provide a valid agency";
        }
        return $errors;
    }
    protected function validateUpdate($agency){
        $errors = array();
        if(!$agency){
            $errors[] = "please provide a valid agency";
        }
        return $errors;
    }
    protected function validateDelete($agency) {
        $errors = array();
        if (!$agency) {
            $errors[] = array('message' => 'Please provide valid agency id', 'code' => 400);
        }

        return $errors;
    }

    //success messages

    protected function buildDeleteSuccessMessage($successMessage){
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => 204);
        return $message;
    }
    protected function buildCreateSuccessMessage($successMessage, $entity){
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => 201,'results' => $entity);
    }
    protected function buildUpdateSuccessMessage($successMessage, $entity){
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => 201,'results' => $entity);
    }
    protected function buildRetrieveOneSuccessMessage($successMessage, $entity){
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => 201,'results' => $entity);
    }
}

?>