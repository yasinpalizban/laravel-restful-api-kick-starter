<?php

namespace Modules\Auth\Http\Controllers;
use Modules\Auth\Entities\PermissionUserEntity;
use Modules\Auth\Services\PermissionUserService;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\Request;
use Modules\Common\Services\SettingService;
use Modules\Shared\Http\Controllers\ApiController;
use Modules\Shared\Libraries\UrlAggregation;

class PermissionUserController extends ApiController
{

    public function index(Request $request, PermissionUserService $permissionUserService, int $permission_id=0)
    {
        $urlAggregation= new UrlAggregation($request);
           $data= $permissionUserService->setNestId($permission_id)->index($urlAggregation);
        return Response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }

    public function show($id, PermissionUserService $permissionUserService)
    {
        $data =$permissionUserService->show($id);

        return  response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }
    public function store(Request $request, SettingService $settingService)
    {
        $rules = [
            'userId' => 'required',
            'permissionId' => 'required',
            'actions' => 'required|min:3|max:255',

        ];

        $fields=$request->validate($rules);

        $settingEntity = new PermissionUserEntity($fields);


        $settingService->create($settingEntity);

        return  response([])->setStatusCode(ResponseAlias::HTTP_CREATED, __('api.commons.save'));

    }


    public function update(Request $request, $id, PermissionUserService $permissionUserService)
    {



        $rules = [
            'userId' => 'required',
            'permissionId' => 'required',
            'actions' => 'required|min:3|max:255',
        ];

        $fields=$request->validate($rules);

        $settingEntity = new PermissionUserEntity($fields);

        $permissionUserService->update($id, $settingEntity);

        return  response([])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.update'));

    }

    public function destroy($id,  PermissionUserService $permissionUserService)
    {
        $permissionUserService->delete($id);
        return  response([])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.remove'));

    }


}
