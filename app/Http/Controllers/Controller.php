<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
Use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends BaseController
{
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse($errors, 422);
        //return $this-> error($errors, 422);
    }
}
