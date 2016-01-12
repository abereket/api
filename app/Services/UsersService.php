<?php
/**
 * Created by PhpStorm.
 * User: kibretbereket
 * Date: 10/12/15
 * Time: 00:00
 */

namespace App\Services;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UsersService extends Base
{
    /**
     * takes request as a parameter and  creates a user
     * @param $request
     * @return mixed
     */
    public function create($request)
    {
        $valError = $this->validateCreate($request->json()->get('email'));
        if($valError){
            return $valError;
        }

        $user = new User();
        $user->uuid         =  Uuid::uuid();
        $user->first_name   =  $request->json()->get('firstName');
        $user->last_name    =  $request->json()->get('lastName');
        $user->email        =  $request->json()->get('email');
        $user->password     =  hash('sha512',$request->json()->get('password'));
        $user->type         =  ($request->json()->get('type'));

        $user->save();

        return $user;
    }

    /** takes user id as a parameter and returns the corresponding user
     * @param $user_id
     * @return mixed
     */
    public function retrieveOne($user_id)
    {
        $user          =     User::find($user_id);
        $valError      =     $this->validateRetrieveOne($user);
        if($valError){
            $valError  =     $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $user          = $this->buildRetrieveOneSuccessMessage('success',$user);
        return $user;
    }

    /**
     * takes user id as a parameter and updates the corresponding user
     * @param $request
     * @param $user_id
     * @return mixed
     */
    public function update($request, $user_id)
    {
        $user           = User::find($user_id);
        $valError       = $this->validateUpdate($user);
        if($valError){
            $valError   = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }

            $user->first_name  =   ($request->json()->get('firstName'))?($request->json()->get('firstName')):$user->first_name;
            $user->last_name   =   ($request->json()->get('lastName'))?($request->json()->get('lastName')):$user->last_name;
            $user->email       =   ($request->json()->get('email'))?($request->json()->get('email')):$user->email;
            $user->password    =   ($request->json()->get('password'))?hash('sha512',($request->json()->get('password'))):$user->password;
            $user->type        =   ($request->json()->get('type'))?($request->json()->get('type')):$user->type;
            $user->verified    =   ($request->json()->get('verified'))?($request->json()->get('verified')):$user->verified;
            $user->save();
            unset($user['password']);

        
        $user=$this->buildUpdateSuccessMessage('success',$user);
        return $user;

    }

    /**
     * takes user id as a parameter and deletes the corresponding user
     * @param $user_id
     * @return mixed
     */
    public function delete($user_id){
        $user          =       User::find($user_id);
        $valError      =       $this->validateDelete($user);
        if($valError){
            $valError  =       $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $user->delete();
        $user = $this->buildDeleteSuccessMessage('success');
        return $user;
    }

    /**
     * This method performs business class validation for users create method
     * @param $user
     * @return array
     */
    protected function validateCreate($user){
        $errors        =      array();
        if(!$user){
            $errors[]  =      array("message"=>"please provide a valid user");
        }
        return $errors;
    }

    /**
     * This method performs business class validation for users retrieveOne method
     * @param $user
     * @return array
     */
    protected function validateRetrieveOne($user){
        $errors       = array();
        if(!$user){
            $errors   = array("message"=>"please provide a valid user");
        }
        return $errors;
    }

    /**
     * This method performs business class validation for users update method
     * @param $user
     * @return array
     */
    protected function validateUpdate($user){
        $errors        = array();
        if(!$user){
            $errors  = array("message"=>"please provide a valid user");
        }
        return $errors;
    }

    /**
     * This method performs business class validation for users delete method
     * @param $user
     * @return array
     */
    protected function validateDelete($user){
        $errors        =  array();
        if(!$user){
            $errors  =  ["message"=>"please provide a valid user"];
        }
       return $errors;
    }
}
