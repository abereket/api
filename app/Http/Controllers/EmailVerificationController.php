<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationsService;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller{

    /**
     * @param $code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($code){

        $EmailVerificationService = new EmailVerificationsService();
        $emailVerification=$EmailVerificationService->update($code);
        return response()->json([$emailVerification]);
    }

    /**
     * @param $code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($code){

        $EmailVerificationService = new EmailVerificationsService();
        $emailVerification = $EmailVerificationService->retrieveOne($code);
        return response()->json([$emailVerification]);
    }
}