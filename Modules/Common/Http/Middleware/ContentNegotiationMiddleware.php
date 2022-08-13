<?php


namespace Modules\Common\Http\Middleware;

use Closure;
use Modules\Common\Enums\LanguageType;
use Modules\Shared\Enums\FilterErrorType;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ContentNegotiationMiddleware
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

//
//
//        $lang = $request->getPreferredLanguage([LanguageType::En, LanguageType::Fa]);
//        $encode = $request->getEncodings();
//        $char = $request->getCharsets();
//
//
//        if (!$lang) {
//            return response()->json([
//                'error' => __('Commmon.filter.contentLang'),
//                'type' => FilterErrorType::Content])
//                ->setStatusCode(ResponseAlias::HTTP_BAD_REQUEST, __('middleWear.contentLang'));
//
//        }
//
//        if (!$encode) {
//            return response()->json([
//                'error' => __('Commmon.filter.contentEncode'),
//                'type' => FilterErrorType::Content])
//                ->setStatusCode(ResponseAlias::HTTP_BAD_REQUEST, __('middleWear.contentEncode'));
//
//        }
//
//        if (!$char) {
//            return response()->json([
//                'error' => __('Commmon.filter.contentChar'),
//                'type' => FilterErrorType::Content])
//                ->setStatusCode(ResponseAlias::HTTP_BAD_REQUEST, __('middleWear.contentChar'));
//
//        }


        return $next($request);
    }
}
