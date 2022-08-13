<?php

namespace Modules\App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\App\Entities\GraphEntity;
use Modules\App\Services\GraphService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class GraphController extends Controller
{

    public function index(Request $request,GraphService $graphService)
    {
        $data = $graphService->index();
        return Response()->json($data)->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.receive'));
    }
    public function store(Request $request, GraphService $graphService)
    {


        $rules = [
            'type' => 'required',
            'fromDate' => 'required',
            'toDate' => 'required',
        ];

        $fields=$request->validate($rules);


        $graphEntity = new GraphEntity($fields);


        $data = $graphService->create($graphEntity);



        return Response()->json( ['data' => $data])->setStatusCode(ResponseAlias::HTTP_OK, __('api.commons.save'));
    }

}
