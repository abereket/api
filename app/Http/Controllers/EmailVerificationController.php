<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationsService;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller{

    public function update($code){

        $EmailVerificationService = new EmailVerificationsService();
        $emailVerification=$EmailVerificationService->update($code);
        return response()->json([$emailVerification]);
    }
}