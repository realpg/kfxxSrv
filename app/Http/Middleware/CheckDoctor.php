<?php

namespace App\Http\Middleware;

use App\Components\DoctorManager;
use App\Components\UserManager;
use App\Http\Controllers\ApiResponse;
use Closure;
use Illuminate\Support\Facades\Log;


class CheckDoctor
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $request->all();
        //合规校验
        if (!array_key_exists('token', $data)) {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::TOKEN_LOST], ApiResponse::TOKEN_LOST);
        }
        if (!array_key_exists('doctor_id', $data)) {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::USER_ID_LOST], ApiResponse::USER_ID_LOST);
        }
        //校验token的合法性
        $result = DoctorManager::ckeckToken($data['user_id'], $data['token']);
        if (!$result) {
            return ApiResponse::makeResponse(false, ApiResponse::$errorMassage[ApiResponse::TOKEN_ERROR], ApiResponse::TOKEN_ERROR);
        }
        return $next($request);

    }
}
