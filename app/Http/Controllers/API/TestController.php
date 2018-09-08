<?php
/**
 * File_Name:TestController.php
 * Author: leek
 * Date: 2017/9/26
 * Time: 11:19
 */

namespace App\Http\Controllers\API;

use App\Components\SechduleManager;
use App\Components\TestManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Log;


class TestController extends Controller
{
	public function test(Request $request)
	{
		Log::info("Schedule Running".date('Y/m/d h:i:sa'));
		return "Schedule Running".date('Y/m/d h:i:sa');
	}
}