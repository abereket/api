<?php
namespace App\Http\Controllers;

use App\Services\UsersService;

class TestController extends Controller{
    /**
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function deleteUsers(){
        $userService = new UsersService();
        $user = $userService->deleteUsers();
        return $user;
    }
}