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
use App\Components\QNManager;
use App\Components\XJManager;
use App\Http\Controllers\ApiResponse;
use App\Models\AD;
use App\Models\Admin;
use App\Models\XJ;
use App\Models\TWStep;
use App\Models\XJType;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class XJController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $xjs = XJManager::getIndexXJs();
        foreach ($xjs as $xj) {
            $xj->created_at_str = DateTool::formateData($xj->created_at, 1);
            $xj = XJManager::getXJInfoByLevel($xj, 0);
        }
        return view('admin.xj.index', ['admin' => $admin, 'datas' => $xjs]);
    }

    //编辑宣教,返回页面
    public function editXJ(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        //types
        $xj_types = XJType::all();
        $xj = new XJ();
        if (array_key_exists('id', $data)) {
            $xj = XJManager::getXJById($data['id']);
            //步骤信息
            $xj->steps = [];
            $xj = XJManager::getXJInfoByLevel($xj, 3);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.xj.editXJ', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token, 'xj_types' => $xj_types]);
    }


    /*
     * 编辑宣教详细信息
     *
     * By TerryQi
     *
     * 2017-12-31
     *
     */
    public function editXJPost(Request $request)
    {
        //获取数据，要求ajax设置Content-Type为application/json; charset=utf-8
        $data = $request->all();
        //新建/编辑宣教信息
        $xj = new XJ();
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $xj = XJManager::getXJById($data['id']);
        }
        $xj = XJManager::setXJ($xj, $data);
        $xj->save();
        //获取数据库中原有的信息
        $ori_steps = XJManager::getStepsByFidAndFtable($xj->id, 'xj');
        $new_steps = $data['steps'];
        //删除步骤
        foreach ($ori_steps as $ori_step) {
            if (!Utils::isIdInArray($ori_step->id, $new_steps)) {
                $ori_step->delete();
            }
        }
        //新建/编辑步骤
        foreach ($new_steps as $new_step) {
            $new_step['f_id'] = $xj->id;
            $new_step['f_table'] = "xj";
            $twStep = new TWStep();
            if (array_key_exists('id', $new_step) && !Utils::isObjNull($new_step['id'])) {
                $twStep = XJManager::getStepById($new_step['id']);
            }
            $twStep = XJManager::setTWStep($twStep, $new_step);
            $twStep->save();
        }
        //重新获取宣教信息并返回
        $xj = XJManager::getXJInfoByLevel($xj, 3);
        return ApiResponse::makeResponse(true, $xj, ApiResponse::SUCCESS_CODE);
    }


    /*
     * 分类首页
     *
     * By TerryQi
     *
     * 2017-12-11
     *
     */
    public function indexType(Request $request)
    {
        $admin = $request->session()->get('admin');
        $xjTypes = XJManager::getXJTypes();
        foreach ($xjTypes as $xjType) {
            $xjType->created_at_str = DateTool::formateData($xjType->created_at, 1);
        }
        return view('admin.xjType.index', ['admin' => $admin, 'datas' => $xjTypes]);
    }

    /*
     * 根据id获取宣教详情
     *
     * By TerryQi
     *
     * 2017-120=-07
     *
     */
    public function setStep(Request $request, $id)
    {
        $admin = $request->session()->get('admin');
        $xj = XJManager::getXJById($id);
        $xj->steps = [];
        $xj->created_at_str = DateTool::formateData($xj->created_at, 1);
        $xj = XJManager::getXJInfoByLevel($xj, 3);
        foreach ($xj->steps as $step) {
            $step->created_at_str = DateTool::formateData($step->created_at, 1);
        }
        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.xj.editStep', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token]);
    }


    /*
     * 添加宣教步骤信息
     *
     * By TerryQi
     *
     * 2017-12-07
     *
     */
    public function setStepPost(Request $request)
    {
        $data = $request->all();
        $tw_step = new TWStep();
        $tw_step = XJManager::setTWStep($tw_step, $data);
        $tw_step->f_table = "xj";
        $tw_step->save();
        return redirect('/admin/xj/setStep/' . $tw_step->f_id);
    }

    //设置状态
    public function setStatus(Request $request, $id)
    {
        $data = $request->all();
        //合规校验
        $requestValidationResult = RequestValidator::validator($request->all(), [
            'opt' => 'required',
        ]);
        if ($requestValidationResult !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数' . $requestValidationResult]);
        }
        //opt必须为0或者1
        $opt = $data['opt'];
        if (!($opt == '0' || $opt == '1')) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数,opt必须为0或者1，现值为' . $opt]);
        }
        $xj = XJ::where('id', '=', $id)->first();
        $xj->status = $opt;
        $xj->save();
        return redirect('/admin/xj/index');
    }


    //删除宣教
    public function del(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
        }
        $xj = XJManager::getXJById($id);
        $xj->delete();
        return redirect('/admin/xj/index');
    }

    //删除宣教步骤
    public function delStep(Request $request, $id)
    {
        if (is_numeric($id) !== true) {
            return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
        }
        $tw_step = XJManager::getStepById($id);
        $xj_id = $tw_step->f_id;
        $tw_step->delete();
        return redirect('/admin/xj/setStep/' . $xj_id);
    }


    //新建或编辑宣教-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $xj = new XJ();
        //types
        $xj_types = XJType::all();
        if (array_key_exists('id', $data)) {
            $xj = XJManager::getXJById($data['id']);
            foreach ($xj_types as $xj_type) {
                if (in_array($xj_type->id, explode(",", $xj->type))) {
                    $xj_type->checked = true;
                }
            }
            //步骤信息
            $xj->steps = [];
            $xj->created_at_str = DateTool::formateData($xj->created_at, 1);
            $xj = XJManager::getXJInfoByLevel($xj, 3);
            foreach ($xj->steps as $step) {
                $step->created_at_str = DateTool::formateData($step->created_at, 1);
            }
        }

        //生成七牛token
        $upload_token = QNManager::uploadToken();
        return view('admin.xj.edit', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token, 'xj_types' => $xj_types]);
    }

    //新建或编辑宣教类型-post
    public function editType(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $xjType = new XJType();
        if (array_key_exists('id', $data)) {
            $xjType = XJManager::getXJTypeById($data['id']);
        }
        //步骤信息
        return view('admin.xjType.edit', ['admin' => $admin, 'data' => $xjType]);
    }

    //新建或编辑宣教->post
    public function editPost(Request $request)
    {
        $data = $request->all();
        $xj = new XJ();
        //存在id是保存
        if (array_key_exists('id', $data)) {
            $xj = XJManager::getXJById($data['id']);
        }
        $xj = XJManager::setXJ($xj, $data);
        $xj->save();
        return redirect('/admin/xj/edit' . '?id=' . $xj->id);
    }

    //新建或编辑宣教类别->post
    public function editTypePost(Request $request)
    {
        $data = $request->all();
        $xjType = new XJType();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $xjType = XJManager::getXJTypeById($data['id']);
        }
        $xjType = XJManager::setXJType($xjType, $data);
        $xjType->save();
        return redirect('/admin/xjType/index');
    }

}