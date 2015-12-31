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
use Illuminate\Http\Request;

class UsersService
{
    /**
     * takes request as a parameter and  creates a user
     * @param $request
     * @return mixed
     */
    public function create($request)
    {
        $type=func_get_args();
        $user = new User();
        $user->uuid         =  Uuid::uuid();
        $user->first_name   =  $request->json()->get('firstName');
        $user->last_name    =  $request->json()->get('lastName');
        $user->email        =  $request->json()->get('email');
        $user->password     =  $request->json()->get('password');
        $user->type         =  ($request->json()->get('type'))?($request->json()->get('type')):$type[1];
        $user->save();
        $valError = $this->validateCreate($user);
        if($valError){
            return $valError;
        }
        return $user;
    }

    /** takes user id as a parameter and returns the corresponding user
     * @param $user_id
     * @return mixed
     */
    public function retrieveOne($user_id)
    {
        $user = User::find($user_id);
        $valError = $this->validateRetrieveOne($user);
        if($valError){
            return $valError;
        }
        unset($user['password']);
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
        $user = User::find($user_id);
        $valError = $this->validateUpdate($user);
        if($valError){
            return $valError;
        }

            $user->id          =   $request->input('id');
            $user->first_name  =   $request->input('firstName');
            $user->last_name   =   $request->input('lastName');
            $user->email       =   $request->input('email');
            $user->password    =   $request->input('password');
            $user->type        =   $request->input('type');
            $user->verified    =   $request->input('verified');
            $user->save();
            unset($user['password']);

        return $user;

    }

    /**
     * takes user id as a parameter and deletes the corresponding user
     * @param $user_id
     * @return mixed
     */
    public function delete($user_id){
        $user=User::find($user_id);
        $valError=$this->validateDelete($user);
        if($valError){
            return $valError;
        }
        $user->delete();
        return $user;
    }

    protected function validateCreate($user){
        $errors = array();
        if(!$user){
            $errors[] = array("message"=>"please provide a valid user");
        }
        return $errors;
    }
    protected function validateRetrieveOne($user){
        $errors = array();
        if(!$user){
            $errors[] = array("message"=>"please provide a valid user");
        }
        return $errors;
    }
    protected function validateUpdate($user){
        $errors = array();
        if(!$user){
            $errors[] = array("message"=>"please provide a valid user");
        }
        return $errors;
    }
    protected function validateDelete($user){
        $errors = array();
        if(!$user){
            $errors[] = array("message"=>"please provide a valid user");
        }
       return $errors;
    }
}
