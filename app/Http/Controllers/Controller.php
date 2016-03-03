<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
Use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends BaseController
{
    /**
     * @param array $errors
     * @return array
     */
    protected function errorDetected($errors){
         foreach($errors as $key=>$value){
             {
                 foreach($value as $k=>$v){
                     $errors[$key]=$v;
                 }
             }
         }
        return $errors;
    }

    /**
     * @param Request $request
     * @param array $errors
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {   $errors = $this->errorDetected($errors);
        $message = 'Please fix your errors';
        return response()->json(['message'=>$message,'code'=>422,'errors'=>[$errors]]);
    }
}
