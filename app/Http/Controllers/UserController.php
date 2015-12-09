<?php
/**
 * Created by PhpStorm.
 * User: kibretbereket
 * Date: 07/12/15
 * Time: 18:27
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
class UserController extends Controller
{
    /**
     * takes all the fields and creates the user
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function create()
    {
        $user = array(
            'id'         => 1,
            'uuid'       =>'12659-adfad-7671',
            'firstName'  => 'kibret',
            'lastName'   => 'bereket',
            'email'      => 'kibret@example.com',
            'type'       => 'agency',
            'password'   =>'yessir',
            'verified'   => 'false',
            'createdAt'  => date("Y-m-d H:m:i"),
            'updatedAt'  => date("Y-m-d H:m:i")
        );
        $users=$user;
       unset($users['password']);

        return response()->json(["status" => "success", "code" => "200", "results" => $users]);


    }

    /**
     * retrieves all the users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve()
    {
        $user = array(array(
            'id'        => 1,
            'uuid'      =>'12659-adfad-7671',
            'firstName' => 'kibret',
            'lastName'  => 'bereket',
            'email'     => 'kibret@example.com',
            'type'      => 'agency',
            'verified'  => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        ), array(
            'id'        => 2,
            'uuid'      =>'12659-adfad-7672',
            'firstName' => 'Amanuel',
            'lastName'  => 'bereket',
            'email'     => 'Amanuel@example.com',
            'type'      => 'agency',
            'verified'  => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        )
        );
        $count = count($user);
        return response()->json(["status" => "success", "code" => "200", "count" => $count, "results" => $user]);
    }

    /**
     * takes the user_id as parameter and retrieves the user
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($user_id)
    {
        $user = array(array(
            'id'        => 1,
            'uuid'      =>'12659-adfad-7671',
            'firstName' => 'kibret',
            'lastName'  => 'bereket',
            'email'     => 'kibret@example.com',
            'type'      => 'agency',
            'verified'  => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        ), array(
            'id'        => 2,
            'uuid'      => '12659-adfad-7672',
            'firstName' => 'Amanuel',
            'lastName'  => 'bereket',
            'email'     => 'Amanuel@example.com',
            'type'      => 'agency',
            'verified'  => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        )
        );
        foreach ($user as $users) {
            if (in_array($user_id, $users)) {
                if($users['id']==$user_id) {
                    return response()->json(["success" => "success", "code" => "200", "results" => $users]);
                }
            }
        }
        return response()->json(["message"  =>  "there is no available user according to your data"]);
    }

    /**
     * takes the user_id as parameter and updates the user
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($user_id){
        $user = array(array(
            'id'        => 1,
            'uuid'      => '12659-adfad-7671',
            'firstName' => 'kibret',
            'lastName'  => 'bereket',
            'email'     => 'kibret@example.com',
            'type'      => 'agency',
            'password'  => 'yessir',
            'verified'  => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        ), array(
            'id'        => 2,
            'uuid'      => '12659-adfad-7672',
            'firstName' => 'Amanuel',
            'lastName'  => 'bereket',
            'email'     => 'Amanuel@example.com',
            'type'      => 'agency',
            'password'  => 'yessir1',
            'verified'  => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        )
        );

        foreach($user as $users){
            if(in_array($user_id,$users)) {
                if($users['id']==$user_id) {
                    $users['firstName'] = 'kibretB';
                    $users['lastName'] = 'bereketB';
                    $users['email'] = 'kibretB@example.com';
                    $users['type'] = 'candidate';
                    $users['type'] = 'candidate';

                    unset($users["password"]);
                    return response()->json(["status" => "success", "code" => "200", "results" => $users]);
                }
                }
        }
        return response()->json(["message"=>"there is no any entry to be updated"]);

    }

    /**
     * takes the user_id as parameter and updates the user
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($user_id){
        $user = array(array(
            'id'        => 1,
            'uuid'      => '12659-adfad-7671',
            'firstName' => 'kibret',
            'lastName'  => 'bereket',
            'email'     => 'kibret@example.com',
            'type'      => 'agency',
            'verified'  => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        ), array(
            'id'        => 2,
            'uuid'      =>'12659-adfad-7672',
            'firstName' => 'Amanuel',
            'lastName'  => 'bereket',
            'email'     => 'Amanuel@example.com',
            'type'      => 'agency',
            'verified'  => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        )
        );
        foreach($user as $users){
            if(in_array($user_id,$users)){
                if($users['id']==$user_id) {
                    unset($users);
                    return response()->json(["status" => "success", "code" => 204]);
                }
            }
        }
        return response()->json(["message"=>"the entry you want to be deleted is not found"]);
    }
}
?>