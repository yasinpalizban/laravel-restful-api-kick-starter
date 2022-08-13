<?php

namespace Modules\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Common\Services\UserService;
use Modules\Shared\Libraries\UrlAggregation;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OverViewController extends Controller
{

    public function index(Request $request , UserService $userService)
    {
       $urlAggregation=  new UrlAggregation($request);

        return Response()->json([
            'userPost' => $userService->index($urlAggregation)['data'],
            'countPost' => [
                'users' => $userService->getCountItems()
            ]])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }






}
