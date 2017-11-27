<?php
/**
 * File_Name:TestController.php
 * Author: leek
 * Date: 2017/9/26
 * Time: 11:19
 */

namespace App\Http\Controllers\API;

use App\Components\TestManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\RequestValidator;


class TestController extends Controller
{
    public function test(Request $request)
    {
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'test' => 'required',
        ]);
        $data = $request->all();

        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }

        $result = TestManager::test($request->all()['test']);

        return ApiResponse::makeResponse(true, $result, ApiResponse::SUCCESS_CODE);
    }
}