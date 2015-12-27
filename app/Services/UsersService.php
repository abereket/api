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
    public function create($request,$agency)
    {
        $user = new User();
        $user->uuid         =  Uuid::uuid();
        $user->first_name   =  $request->input('firstName');
        $user->last_name    =  $request->input('lastName');
        $user->email        =  $request->input('email');
        $user->password     =  $request->input('password');
        $user->type         =  $agency;
        $user->save();

        $result = User::where('id',$user->id)->first();
        return $result;
    }

    /** takes user id as a parameter and returns the corresponding user
     * @param $user_id
     * @return mixed
     */
    public function retrieveOne($user_id)
    {
        $user = User::find($user_id);
        unset($user['password'], $user['deleted_at']);
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
        if ($user) {
            $user->id          =   $request->input('id');
            $user->first_name  =   $request->input('firstName');
            $user->last_name   =   $request->input('lastName');
            $user->email       =   $request->input('email');
            $user->password    =   $request->input('password');
            $user->type        =   $request->input('type');
            $user->save();
            unset($user['password']);
        }
        return $user;

    }

    /**
     * takes user id as a parameter and deletes the corresponding user
     * @param $user_id
     * @return mixed
     */
    public function delete($user_id){
        $user=User::find($user_id);
        if(!$user){
            return $user;
        }
        $user->delete();
        return $user;
    }
}
