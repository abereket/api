<?php
namespace App\Services;

use App\Models\User;
use App\Models\Agency;



class EmailVerificationsService{

    public function create($verificationType,$agencyId){
        $user = User::find($agencyId);

    }
}