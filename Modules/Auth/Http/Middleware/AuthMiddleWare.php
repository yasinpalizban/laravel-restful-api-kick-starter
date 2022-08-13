<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Modules\Auth\Config\ModuleAuthConfig;
use Modules\Auth\Models\GroupModel;
use Modules\Auth\Models\GroupUserModel;
use Modules\Auth\Models\PermissionGroupModel;
use Modules\Auth\Models\PermissionModel;
use Modules\Auth\Models\PermissionUserModel;
use Modules\Auth\Models\UserModel;
use Modules\Auth\Services\RoleRouteService;
use Modules\Shared\Enums\FilterErrorType;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthMiddleWare
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

        $roleRoute = new RoleRouteService();
        $groupsUsersModel = new GroupUserModel();
        $permissionGroupModel = new PermissionGroupModel();
        $permissionUserModel = new PermissionUserModel();
        $permissionModel = new PermissionModel();
        $groupModel = new GroupModel();
        $userModel = new UserModel();

        $authConfig = new  ModuleAuthConfig();


        $controllerName =  routeController($request->getRequestUri());


        try {
            $authorization = $request->Server('HTTP_AUTHORIZATION') ? getJWTHeader($request->Server('HTTP_AUTHORIZATION')) : $request->Cookie($authConfig->jwt['name']);


            if (is_null($authorization) ) {
                return response()->json([
                    'type' => FilterErrorType::Login,
                    'error' => __('middleWear.authToken')])
                    ->setStatusCode(ResponseAlias::HTTP_UNAUTHORIZED, __('middleWear.authToken'));
            }

            $jwtUser = validateJWT($authorization, $authConfig->jwt['secretKey']);


            $findUser = $userModel->where('id',  $jwtUser->userId)->first();

            if (is_null($findUser)) {

                return response()->json(['success' => false,
                    'type' => FilterErrorType::Login,
                    'error' => __('middleWear.wrongAuth')])
                    ->setStatusCode(ResponseAlias::HTTP_UNAUTHORIZED, __('middleWear.wrongAuth'));

            }

            $userGroup = $groupModel->getGroupsForUser($jwtUser->userId);

            $findUser->groupId = $userGroup[0]->id;
            $findUser->groupName = $userGroup[0]->name;

            $request->request->add(['user'=>$findUser]);

            //get permission for  controller
            $controllerPermission = $permissionModel->where([
                "name" => $controllerName,
                "active" => 1
            ])->first();



            // if there is not permission for controller check by roles
            // other wise check by permission by user or group

            if (empty($controllerPermission)) {


                // Check each requested roles

                $controllerRoles = $roleRoute->getRoleAccess($controllerName);

                if (empty($controllerRoles)) {
                    return $next($request);
                }

                foreach ($controllerRoles as $group) {

                    if ($group== $userGroup[0]->name) {
                        return $next($request);
                    }
                }

            } else {

                // Check each requested permission

                $typeMethod = strtolower( $request->getMethod());

                // get group of user
                $groupOfUser = $groupsUsersModel->where('user_id', $jwtUser->userId)
                    ->first();

                $permissionOfGroup = $permissionGroupModel->where([
                    "permission_id" => $controllerPermission->id,
                    "group_id" => $groupOfUser->group_id
                ])->first();

                $permissionOfUser = $permissionUserModel->where([
                    "permission_id" => $controllerPermission->id,
                    "user_id" => $jwtUser->userId
                ])->first();


                if (!empty($permissionOfGroup) && strstr($permissionOfGroup->actions, $typeMethod)) {

                    return $next($request);

                }

                if (!empty($permissionOfUser) && strstr($permissionOfUser->actions, $typeMethod)) {

                    return $next($request);
                }

            }

            return response()->json([
                'type' => FilterErrorType::Permission,
                'error' => __('middleWear.notEnoughPrivilege')])
                ->setStatusCode(ResponseAlias::HTTP_UNAUTHORIZED, __('middleWear.notEnoughPrivilege'));


        } catch (\Exception $e) {

            return response()->json([
                'type' => FilterErrorType::Login,
                'error' => __('middleWear.wrongAuth')])
                ->setStatusCode(ResponseAlias::HTTP_UNAUTHORIZED, __('middleWear.wrongAuth'));


        }


    }
}
