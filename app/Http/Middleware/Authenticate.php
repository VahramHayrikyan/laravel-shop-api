<?php

namespace App\Http\Middleware;
use App\Services\BaseService;
use Closure;

class Authenticate
{
    use BaseService;

    public function handle($request, Closure $next)
    {
        if (!empty($request->header('access-key'))) {
            return $next($request);
        }
        return $this->errorResponse("Not Authorized" ,[], Response::HTTP_UNAUTHORIZED);
    }
}

