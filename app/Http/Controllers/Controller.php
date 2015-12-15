<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
            return new JsonResponse($errors, 422);

    }
}
