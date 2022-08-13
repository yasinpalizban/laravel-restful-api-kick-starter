<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Shared\Http\Controllers\BaseController;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class HomeController extends BaseController
{

    public function index()
    {

     //   return view('home::forgot',['hash'=>'jkshdfjdshfjdshfds']);
        return Response()->json(['this i home ctl '])->setStatusCode(ResponseAlias::HTTP_OK);
    }

}
