<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\DoctorManager;
use App\Components\HomeManager;
use App\Components\HposManager;
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
        $sjxs = SJXManager::getSJXsPaginate();
        foreach ($sjxs as $sjx) {
            $sjx->doctor = DoctorManager::getDoctorById($sjx->doctor_id);
            $sjx->hpos = HposManager::getHPosById($sjx->hpos_id);
        }
        return ApiResponse::makeResponse(true, $sjxs, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据位置检索数据项信息
     *
     * By TerryQi
     *
     * 2018-01-19
     */
    public function getListByHPosId(Request $request)
    {
        $data = $request->all();    //request转array
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'hpos_id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $sjxs = SJXManager::getSJXsByHPos($data['hpos_id']);
        foreach ($sjxs as $sjx) {
            $sjx->doctor = DoctorManager::getDoctorById($sjx->doctor_id);
            $sjx->hpos = HposManager::getHPosById($sjx->hpos_id);
        }
        return ApiResponse::makeResponse(true, $sjxs, ApiResponse::SUCCESS_CODE);
    }

    /*
     * 根据id获取数据项
     *
     * By TerryQi
     *
     * 2017-12-13
     *
     */
    public function getSJXById(Request $request)
    {
        $data = $request->all();    //request转array
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $sjx = SJXManager::getSJXById($data['id']);
        $sjx->doctor = DoctorManager::getDoctorById($sjx->doctor_id);
        $sjx->hpos = HposManager::getHPosById($sjx->hpos_id);
        return ApiResponse::makeResponse(true, $sjx, ApiResponse::SUCCESS_CODE);
    }

}