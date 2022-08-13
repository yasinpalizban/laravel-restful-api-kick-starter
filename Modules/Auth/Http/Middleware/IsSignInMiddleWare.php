<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Modules\Auth\Config\ModuleAuthConfig;
use Modules\Auth\Models\UserModel;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class IsSignInMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $authConfig = new  ModuleAuthConfig();

        $authorization = $request->Server('AUTHORIZATION') ? getJWTHeader($request->Server('AUTHORIZATION')) : $request->Cookie($authConfig->jwt['name']);
        $userModel = new UserModel();

        if (!is_null($authorization)) {

            try {

                $jwtUser = validateJWT($authorization, $authConfig->jwt['secretKey']);

                $findUser = $userModel->where('id',  $jwtUser->userId)->first();
                if ($findUser) {

                    return response()->json()
                        ->setStatusCode(ResponseAlias::HTTP_NOT_MODIFIED, __('middleWear.youAlreadySignedIn'));
                } else {
                    return $next($request);
                }


            } catch (\Exception $e) {

                return $next($request);
            }


        } else {
            return $next($request);
        }


    }
}
