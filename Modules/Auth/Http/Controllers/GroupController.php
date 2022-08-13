<?php

namespace Modules\Auth\Http\Controllers;
use Modules\Auth\Entities\GroupEntity;
use Modules\Auth\Services\GroupService;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\Request;

use Modules\Shared\Http\Controllers\ApiController;
use Modules\Shared\Libraries\UrlAggregation;

class GroupController extends ApiController
{

    public function index(Request $request, GroupService $groupService)
    {

        $urlAggregation= new UrlAggregation($request);
           $data= $groupService->index($urlAggregation);
        return Response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }

    public function show($id, GroupService $groupService)
    {
        $data =$groupService->show($id);

        return  response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }
    public function store(Request $request, GroupService $groupService)
    {
        $rules = [
            'name' => 'required|min:3|max:255|unique:Modules\Auth\Models\GroupModel,name',
            'description' => 'required|min:3|max:255',

        ];

        $fields=$request->validate($rules);

        $groupEntity = new GroupEntity($fields);

        $groupService->create($groupEntity);

        return  response([])->setStatusCode(ResponseAlias::HTTP_CREATED, __('api.commons.save'));

    }


    public function update(Request $request, $id, GroupService $groupService)
    {



        $rules = [
            'name' => 'nullable|min:3|max:255|unique:Modules\Auth\Models\GroupModel,name',
            'description' => 'required|min:3|max:255',

        ];

        $fields=$request->validate($rules);

        $groupEntity = new GroupEntity($fields);

        $groupService->update($id, $groupEntity);

        return  response([])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.update'));

    }

    public function destroy($id,  GroupService $groupService)
    {
        $groupService->delete($id);
        return  response([])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.remove'));

    }


}
