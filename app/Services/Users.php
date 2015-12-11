<?php
<<<<<<< HEAD
namespace App\Services;
use App\Models\Agency;
use App\Models\User;

class Users{

    public function create($request){

        $agency=Agency::create($request->all());
        $user=User::create($request->all());

        return $agency;

    }

}
=======
/**
 * Created by PhpStorm.
 * User: kibretbereket
 * Date: 10/12/15
 * Time: 00:00
 */

namespace App\Services;
use App\Models\User;

class Users
{

    public function create($firstName, $lastName, $email, $password, $type, $request)
    {

        $user = new User();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->password = $password;
        $user->type = $type;
        $user->save();

        //2. Save it to the database

        //3. Return user array data
        $result = User::get(array('id', 'uuid', 'first_name', 'last_name', 'email', 'type', 'verified', 'created_at', 'updated_at'));
        return $result;
    }

    public function retrieveOne($user_id)
    {
        $user = User::find($user_id);
        unset($user['password'], $user['deleted_at']);
        return $user;
    }

    public function update($request, $user_id)
    {
        //return $request;
        $user = User::find($user_id);
        if ($user) {
            $user->first_name = $request->input('firstName');
            $user->last_name = $request->input('lastName');
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->type = $request->input('type');
            $user->save();
        }
        unset($user['password'], $user['deleted_at']);
        return $user;

    }

    public function delete($user_id){
        $user=User::find($user_id);
        if($user){
            $user->delete();
            $status='success';
            return $status;
        }
        $status='failure';
        return $status;
    }
}
>>>>>>> kb_#109578638_users
