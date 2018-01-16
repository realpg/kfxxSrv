<?php
/**
 * 首页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20 0020
 * Time: 20:15
 */

namespace App\Http\Controllers\Admin;

use App\Components\AdminManager;
use App\Components\DateTool;
use App\Components\DoctorManager;
use App\Components\HposManager;
use App\Components\QNManager;
use App\Components\SJXManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Models\AD;
use App\Models\Admin;
use App\Models\HPos;
use App\Models\SJX;
use App\Models\XJ;
use App\Models\TWStep;
use App\Models\XJType;
use Hamcrest\Util;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class HPosController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $hPoss = HposManager::getHPosList();
        foreach ($hPoss as $hPos) {
            $hPos->created_at_str = DateTool::formateData($hPos->created_at, 1);
        }
//        dd($hPoss);
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.hPos.index', ['admin' => $admin, 'datas' => $hPoss, 'upload_token' => $upload_token]);
    }

    //新建或编辑数据项-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $hPos = new HPos();

        if (array_key_exists('id', $data)) {
            $hPos = HposManager::getHPosById($data['id']);
        }

        return ApiResponse::makeResponse(true, $hPos, ApiResponse::SUCCESS_CODE);
    }

    //新建或编辑数据项->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $hPos = new HPos();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $hPos = HposManager::getHPosById($data['id']);
        }
        $hPos = HposManager::setHPos($hPos, $data);
        $hPos->save();
        $hPos = HposManager::getHPosById($hPos->id);
        return ApiResponse::makeResponse(true, $hPos, ApiResponse::SUCCESS_CODE);
    }
}