<?php
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