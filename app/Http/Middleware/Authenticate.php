<?php namespace App\Http\Middleware;

use Auth;
use Closure;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions;
use \App\Services\Api\Errors as ApiErrors;
use \App\Models\User;

class Authenticate {

	/**
	 * @param \Illuminate\Http\Request $request
	 * @param Closure $next
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Exception
	 */
	public function handle(\Illuminate\Http\Request $request, Closure $next)
	{

		if (!$userId = JWTAuth::parseToken('bearer')->getPayload()->get('sub')) {
			$error = ApiErrors::getApiError(ApiErrors::API_ERROR_INVALID_TOKEN);
			return response()->json($error['data'], $error['code']);
		}
		$user = User::find($userId);

		if($user instanceof \Illuminate\Contracts\Auth\Authenticatable && $user instanceof User) {
			Auth::login($user);
		} else {
			$error = ApiErrors::getApiError(ApiErrors::API_ERROR_INVALID_TOKEN);
			return response()->json($error['data'], $error['code']);
		}


		return $next($request);

	}

}
