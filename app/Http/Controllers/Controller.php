<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
Use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends BaseController
{
    //const HTTP = array('422'=>204, '200'=>200);
    const HTTP_422 = 422;
    const HTTP_200 = 200;
    const HTTP_204 = 204;
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse($errors, self::HTTP_422);
        //return $this-> error($errors, 422);
    }
}
