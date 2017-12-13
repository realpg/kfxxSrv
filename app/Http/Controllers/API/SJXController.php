<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\HomeManager;
use App\Components\SJXManager;
use App\Components\UserManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\User;
use App\Models\ViewModels\HomeView;
use App\Models\XJ;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class SJXController extends Controller
{

    /*
     * 获取数据项列表
     *
     * By TerryQi
     *
     * 2017-12-08
     */
    public function getList(Request $request)
    {
        $data = $request->all();    //request转array
        $sjx = SJXManager::getSJXs();

        return ApiResponse::makeResponse(true, $sjx, ApiResponse::SUCCESS_CODE);
    }

}