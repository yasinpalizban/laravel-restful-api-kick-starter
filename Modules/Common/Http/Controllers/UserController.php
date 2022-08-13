<?php

namespace Modules\Common\Http\Controllers;
use Modules\Common\Entities\SettingEntity;
use Modules\Common\Entities\UserEntity;
use Modules\Common\Services\UserService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Http\Request;
use Modules\Shared\Http\Controllers\ApiController;
use Modules\Shared\Libraries\UrlAggregation;

class UserController extends ApiController
{

    public function index(Request $request, UserService $userService)
    {

        $urlAggregation= new UrlAggregation($request);
           $data= $userService->index($urlAggregation);
        return Response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }

    public function show($id, UserService $userService)
    {
        $data =$userService->show($id);

        return  response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }
    public function store(Request $request, UserService $userService)
    {



        $rules = [
            'username' => 'required|alpha_num|min:3|unique:Modules\Auth\Models\UserModel,username',
            'email' => 'required|email:rfc,dns|unique:Modules\Auth\Models\UserModel,email',
            'phone' => 'required|max:11|unique:Modules\Auth\Models\UserModel,phone',
            'password' => 'required',
            'first_name' => 'nullable|alpha|max:255',
            'last_name' => 'nullable|alpha|max:255',
            'group' => 'required',
        ];

        $fields=$request->validate($rules);

        $userEntity = new UserEntity($fields);
        $userEntity->createdAt();

        $userService->create($userEntity);

        return  response([])->setStatusCode(ResponseAlias::HTTP_CREATED, __('api.commons.save'));

    }


    public function update(Request $request, $id, UserService $userService)
    {



        $rules = [

            'firstName' => 'nullable|alpha|max:255',
            'lastName' => 'nullable|alpha|max:255',
            'phone' => 'nullable|max:11',
            'group' => 'required',
            'status' => 'nullable',
        ];

        $fields=$request->validate($rules);

        $userEntity = new UserEntity($fields);
        $userEntity->updatedAt();


        $userService->update($id, $userEntity);

        return  response([])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.update'));

    }

    public function destroy($id,  UserService $userService)
    {
        $userService->delete($id);
        return  response([])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.remove'));

    }


}
