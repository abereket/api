<?php
namespace App\Http\Controllers;

use App\Services\UsersService;
use Illuminate\Http\Request;

class IsAuthenticatedController extends Controller{
    /**
     * @param Request $request
     * @return mixed
     */
    public function authenticate(Request $request){
        $rules = ['user_name'=>'required','password'=>'required'];
        $this->validate($request,$rules);
        $userService = new UsersService();
        $user = $userService->authenticate($request->json()->get('user_name'),$request->json()->get('password'));

        return response()->json($user);
    }
}