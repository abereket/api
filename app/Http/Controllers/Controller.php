<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
Use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
class Controller extends BaseController
{
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
            return new JsonResponse($errors, 422);

    }
}
