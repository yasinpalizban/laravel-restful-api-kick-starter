<?php

namespace Modules\Common\Http\Controllers;
use Modules\Common\Entities\SettingEntity;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\Request;
use Modules\Common\Services\SettingService;
use Modules\Shared\Http\Controllers\ApiController;
use Modules\Shared\Libraries\UrlAggregation;

class SettingController extends ApiController
{

    public function index(Request $request, SettingService $settingService)
    {

        $urlAggregation= new UrlAggregation($request);
           $data= $settingService->index($urlAggregation);
        return Response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }

    public function show($id, SettingService $settingService)
    {
        $data =$settingService->show($id);

        return  response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }
    public function store(Request $request, SettingService $settingService)
    {
        $rules = [
            'key' => 'required|min:3|max:255|unique:Modules\Common\Models\SettingModel,key',
            'value' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'status' => 'required',
        ];

        $fields=$request->validate($rules);

        $settingEntity = new SettingEntity($fields);
        $settingEntity->createdAt();

        $settingService->create($settingEntity);

        return  response([])->setStatusCode(ResponseAlias::HTTP_CREATED, __('api.commons.save'));

    }


    public function update(Request $request, $id, SettingService $settingService)
    {



        $rules = [
            'key' => 'nullable|min:3|max:255',
            'value' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'status' => 'required'
        ];

        $fields=$request->validate($rules);

        $settingEntity = new SettingEntity($fields);
        $settingEntity->updatedAt();

        $settingEntity->updatedAt();

        $settingService->update($id, $settingEntity);

        return  response([])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.update'));

    }

    public function destroy($id,  SettingService $settingService)
    {
        $settingService->delete($id);
        return  response([])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.remove'));

    }


}
