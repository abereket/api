<?php
/**
 * Created by PhpStorm.
 * User: kibretbereket
 * Date: 07/12/15
 * Time: 18:27
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Users;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * takes all the fields and creates the user
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function create(Request $request)
    {

        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $email = $request->input('email');
        $password = $request->input('password');
        $type = $request->input('type');

        $rules = ['firstName' => 'required|max:50', 'lastName' => 'required|max:50', 'email' => 'required|email|max:60',
            'password' => 'required|max:60', 'type' => 'required'];

        $this->validate($request, $rules);

        $userService = new Users();
        $result = $userService->create($firstName, $lastName, $email, $password, $type, $request);


        return response()->json(["status" => "success", "code" => "200", "results" => $result]);


    }

    /**
     * retrieves all the users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve()
    {
        $user = array(array(
            'id' => 1,
            'uuid' => '12659-adfad-7671',
            'firstName' => 'kibret',
            'lastName' => 'bereket',
            'email' => 'kibret@example.com',
            'type' => 'agency',
            'verified' => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        ), array(
            'id' => 2,
            'uuid' => '12659-adfad-7672',
            'firstName' => 'Amanuel',
            'lastName' => 'bereket',
            'email' => 'Amanuel@example.com',
            'type' => 'agency',
            'verified' => 'false',
            'createdAt' => date("Y-m-d H:m:i"),
            'updatedAt' => date("Y-m-d H:m:i")
        )
        );
        $count = count($user);
        return response()->json(["status" => "success", "code" => "200", "count" => $count, "results" => $user]);
    }

    public function retrieveOne($user_id)
    {
        $userService = new Users();
        $result = $userService->retrieveOne($user_id);
        if ($result !== null) {
            return response()->json(["success" => "success", "code" => "200", "results" => $result]);
        }
        return response()->json(["message" => "there is no available user according to your data"]);
    }


    public function update(Request $request, $user_id)
    {

        $rules = [
            'id'            => 'required|max:11',
            'firstName'     => 'required|max:50',
            'lastName'      => 'required|max:50',
            'email'         => 'required|email|max:60',
            'password'      => 'required|max:60',
            'type'          => 'required'
        ];

        $this->validate($request, $rules);
        $userService = new Users();
        $result = $userService->update($request, $user_id);
        if ($result !== null) {
            return response()->json(["status" => "success", "code" => "200", "results" => $result]);
        }
        return response()->json(["message" => "there is no any entry to be updated"]);
    }

    public function delete($user_id)
    {
        $userService = new Users();
        $user= $userService->delete($user_id);
        if($user) {
            return response()->json(["status" => $status, "code" => 204]);
        }
        return response()->json(["message"=>"the entry you want to be deleted is not found"]);
    }
}
?>