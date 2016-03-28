<?php
namespace App\Http\Controllers;

use App\Services\UsersService;
use Illuminate\Http\Request;

class IsAuthenticatedController extends Controller{
    public function authenticate(Request $request){
        $userService = new UsersService();
        $user = $userService->authenticate($request->json()->get('user_name'),$request->json()->get('password'));
        return $user;
    }
}