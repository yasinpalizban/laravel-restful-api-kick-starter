<?php


namespace Modules\Common\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Modules\Shared\Enums\FilterErrorType;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
class  DataInputMiddleware
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

        $isFormData=count($_POST)|| count($_FILES);
        if (($request->getMethod()=='POST'|| $request->getMethod()=='PUT') && !$request->isJson() && !$isFormData) {
            return response()->json([
                'error' => __(' middleWear.dataInput'),
                'type' => FilterErrorType::DataInput])
                ->setStatusCode(ResponseAlias::HTTP_BAD_REQUEST, __(' middleWear.dataInput'));
        }

        return $next($request);
    }
}
