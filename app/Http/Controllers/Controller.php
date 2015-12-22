<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
Use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
class Controller extends BaseController
{
    const HTTP_200 = 200;
    const HTTP_204 = 204;
    const HTTP_422 = 422;
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
            return new JsonResponse($errors, self::HTTP_422);

    }
}
