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
        $rules = ['first_name' => 'required|string|max:50', 'last_name' => 'required|string|max:50', 'email' => 'required|email|max:60',
            'password' => 'string|max:60', 'type' => 'required|in:recruiter,candidate,agency,zemployee','invited_by'=>'sometimes|required|integer|exists:users,id,deleted_at,NULL'];

        $this->validate($request, $rules);

        $userService = new UsersService();
        $user = $userService->create($request);

        return response()->json($user);
    }

    /**
     * calls the retrieve method in services.UsersService
     * retrieves all the users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request)
    {
        $userService = new UsersService();
        $user = $userService->retrieve($request);
        return response()->json($user);
    }

    /**
     * calls the retrieveOne method in services.UsersService
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($user_id)
    {
        $userService = new UsersService();
        $user = $userService->retrieveOne($user_id);

        return response()->json($user);

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
            'first_name'    => 'sometimes|required|string|max:50',
            'last_name'     => 'sometimes|required|string|max:50',
            'password'      => 'sometimes|required|string|max:60',
            'type'          => 'sometimes|required|in:recruiter,candidate,agency,zemployee',
            'verified'      => 'boolean'
        ];

        $this->validate($request, $rules);
        $userService = new UsersService();
        $user = $userService->update($request, $user_id);

        return response()->json($user);

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
        return response()->json($user);

    }
}
?>