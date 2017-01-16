<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use \Illuminate\Http\Request;
use \App\Services\Api\Errors as ApiErrors;

class ValidateParameters {

    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        if(!$request->input('width') || !$request->input('height')) {
            $apiError = ApiErrors::getApiError(ApiErrors::API_ERROR_USER_SENT_BAD_HEIGHT_WIDTH);
            return response()->json($apiError['data'], $apiError['code']);
        }

        return $next($request);

    }

}
