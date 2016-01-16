<?php

namespace App\Http\Middleware;

use App\Services\UsersService;
use Exception;
use Closure;
class authenticateMiddleware {
    public function handle($request, Closure $next)
    {
        $userService = new UsersService();
        $user = $userService->authenticate($request->json()->get('email'),$request->json()->get('password'));
        if (!$user) {
            //return "Please enter a valid user name and password";
            throw new Exception("Please provide valid username and password");

        }
        return $next($request);
    }
}
