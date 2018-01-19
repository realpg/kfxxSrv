<?php
/**
 * File_Name:UserController.php
 * Author: leek
 * Date: 2017/8/23
 * Time: 15:24
 */

namespace App\Http\Controllers\API;

use App\Components\ADManager;
use App\Components\HomeManager;
use App\Components\KFMBManager;
use App\Components\UserManager;
use App\Components\Utils;
use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Libs\wxDecode\ErrorCode;
use App\Libs\wxDecode\WXBizDataCrypt;
use App\Models\KFMB;
use App\Models\KFMBJH;
use App\Models\KFMBJHSJ;
use App\Models\ViewModels\HomeView;
use Illuminate\Http\Request;
use App\Components\RequestValidator;
use Qiniu\Auth;

class KFMBController extends Controller
{

    /*
     * 获取全部康复模板列表
     *
     * By TerryQi
     *
     * 2017-11-27
     */
    public function getKFMBList(Request $request)
    {
        $data = $request->all();
        $kfmbs = KFMBManager::getListByStatus(["1"]);
        return ApiResponse::makeResponse(true, $kfmbs, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 根据id获取康复模板基本信息
     *
     * By TerryQi
     *
     * 2017-12-12
     */
    public function getKFMBById(Request $request)
    {
        $data = $request->all();
        //合规校验account_type
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'id' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
        }
        $kfmb = KFMBManager::getKFMBById($data['id']);
        $kfmb = KFMBManager::getKFMBInfoByLevel($kfmb, $data['level']);
        return ApiResponse::makeResponse(true, $kfmb, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 保存康复模板，目前康复模板信息不需要保存，因此未实现该逻辑
     *
     * By TerryQi
     *
     * 2017-12-26
     *
     */
    public function editKFMB(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
        //新建/编辑康复模板
        $kfmb = new KFMB();
        if (array_key_exists('id', $data) && !Utils::isObjNull($data['id'])) {
            $kfmb = KFMBManager::getKFMBById($data['id']);
        }
        $kfmb = KFMBManager::setKFMB($kfmb, $data);
        //保存康复模板
        $kfmb->save();

        //编辑康复模板计划
        //删除已经无用的康复计划
        $ori_jhs = KFMBManager::getJHListByKFMBId($kfmb->id);
        //删除原有康复计划以及计划下关联的数据
        foreach ($ori_jhs as $ori_jh) {
            $ori_jhsjs = KFMBManager::getJHSJByJHId($ori_jh->id);
            $ori_jh->delete();
            foreach ($ori_jhsjs as $ori_jhsj) {
                $ori_jhsj->delete();
            }
        }
        //新建康复计划及数据
        $new_jhs = $data['jhs'];
        foreach ($new_jhs as $new_jh) {
            $jh = new KFMBJH();
            $jh = KFMBManager::setKFMBJH($jh, $new_jh);
            $jh->kfmb_id = $kfmb->id;
            $jh->save();
            //新建康复计划采集数据
            $new_jhsjs = $new_jh['jhsjs'];
            foreach ($new_jhsjs as $new_jhsj) {
                $jhsj = new KFMBJHSJ();
                $jhsj = KFMBManager::setKFMBJHSJ($jhsj, $new_jhsj);
                $jhsj->mbjh_id = $jh->id;
                $jhsj->save();
            }
        }
        //保存康复模板
        $kfmb = KFMBManager::getKFMBInfoByLevel($kfmb, "03");
        return ApiResponse::makeResponse(true, $kfmb, ApiResponse::SUCCESS_CODE);
    }

}