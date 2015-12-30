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
use App\Services\UsersService;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * calls the create method in services.UsersService
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function create(Request $request)
    {
        $rules = ['firstName' => 'required|max:50', 'lastName' => 'required|max:50', 'email' => 'required|email|max:60',
            'password' => 'required|max:60', 'type' => 'required'];

        $this->validate($request, $rules);

        $userService = new UsersService();
        $result = $userService->create($request);


        return response()->json(["status" => "success", "code" => parent::HTTP_200, "results" => $result]);


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
        return response()->json(["status" => "success", "code" => parent::HTTP_200, "count" => $count, "results" => $user]);
    }

    /**
     * calls the retrieveOne method in services.UsersService
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($user_id)
    {
        $userService = new UsersService();
        $result = $userService->retrieveOne($user_id);

        return response()->json(["success" => "success", "code" => parent::HTTP_200, "results" => $result]);

    }

    /**
     * calls the update method in services.UsersService
     * @param Request $request
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
        $userService = new UsersService();
        $result = $userService->update($request, $user_id);

        return response()->json(["status" => "success", "code" => parent::HTTP_200, "results" => $result]);

    }

    /**
     * calls the delete method in services.UsersService
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($user_id)
    {
        $userService = new UsersService();
        $user= $userService->delete($user_id);
        if(!$user) {
            return response()->json(["message"=>"the entry you want to be deleted is not found"]);
        }
        return response()->json(["status" => "success", "code" => parent::HTTP_204]);

    }
}
?>