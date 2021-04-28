<?php

namespace App\Http\Middleware;

use App\Services\ResponseService;
use Closure;
use Illuminate\Http\Response;

class AccessKey
{
    use ResponseService;

    public function handle($request,  Closure $next)
    {
        $accessKey = $request->header('access-key');
        if (!empty($accessKey) && config('constants.access-key') == $accessKey) {

            $userRoles = $request->header('auth-roles');
            $rolesArr  = json_decode($userRoles);

//            $request->request->add(['user_roles' => $rolesArr]);

            return $next($request);
        }
        return $this->errorResponse("Not Authorized", [], Response::HTTP_UNAUTHORIZED);
    }
}
