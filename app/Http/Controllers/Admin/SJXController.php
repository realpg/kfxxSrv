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
use App\Components\YJGZManager;
use App\Http\Controllers\ApiResponse;
use App\Models\AD;
use App\Models\Admin;
use App\Models\SJX;
use App\Models\XJ;
use App\Models\TWStep;
use App\Models\XJType;
use App\Models\YJGZ;
use Illuminate\Http\Request;
use App\Libs\ServerUtils;
use App\Components\RequestValidator;
use Illuminate\Support\Facades\Redirect;


class SJXController
{

    //首页
    public function index(Request $request)
    {
        $admin = $request->session()->get('admin');
        $sjxs = SJXManager::getSJXsPaginate();
        foreach ($sjxs as $sjx) {
            $sjx->created_at_str = DateTool::formateData($sjx->created_at, 1);
            $sjx->doctor = DoctorManager::getDoctorById($sjx->doctor_id);
            $sjx->hpos = HposManager::getHPosById($sjx->hpos_id);
        }
        $hposs = HposManager::getHPosList();
        return view('admin.sjx.index', ['admin' => $admin, 'datas' => $sjxs, 'hposs' => $hposs]);
    }

    //根据患处位置搜索数据项
    public function search(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $search_hpos_id = $data['hpos_id'];
        $sjxs = SJXManager::getSJXsByHPosPaginate($search_hpos_id);
        foreach ($sjxs as $sjx) {
            $sjx->created_at_str = DateTool::formateData($sjx->created_at, 1);
            $sjx->doctor = DoctorManager::getDoctorById($sjx->doctor_id);
            $sjx->hpos = HposManager::getHPosById($sjx->hpos_id);
        }
        $hposs = HposManager::getHPosList();
        return view('admin.sjx.index', ['admin' => $admin, 'datas' => $sjxs, 'hposs' => $hposs, 'search_hpos_id' => $search_hpos_id]);
    }

    //新建或编辑数据项-get
    public function edit(Request $request)
    {
        $admin = $request->session()->get('admin');
        $data = $request->all();
        $sjx = new SJX();
        if (array_key_exists('id', $data)) {
            $sjx = SJXManager::getSJXById($data['id']);
        }
        return ApiResponse::makeResponse(true, $sjx, ApiResponse::SUCCESS_CODE);
    }
//新建或编辑预警规则-get
	public function editYJGZ(Request $request)
	{
		$admin = $request->session()->get('admin');
		$data = $request->all();
		$yjgz=new YJGZ();
		if (array_key_exists('id', $data)) {
			$yjgz = YJGZManager::getYJGZbyId($data['id']);
		}
		return ApiResponse::makeResponse(true, $yjgz, ApiResponse::SUCCESS_CODE);
	}

    //新建或编辑数据项->post
    public function editPost(Request $request)
    {
        $data = $request->all();
//        dd($data);
        $sjx = new SJX();
        //存在id是保存
        if (array_key_exists('id', $data) && $data['id'] != null) {
            $sjx = SJXManager::getSJXById($data['id']);
        }
        $sjx = SJXManager::setSJX($sjx, $data);
        $sjx->save();
        return redirect('/admin/sjx/index');
    }
	public function editYJGZPost(Request $request)
	{
		$data = $request->all();
//        dd($data);
		$yjgz=new YJGZ();
		//存在id是保存
		if (array_key_exists('id', $data) && $data['id'] != null) {
			$yjgz =YJGZManager::getYJGZbyId($data['id']);
		}
		$yjgz = YJGZManager::setYJGZ($yjgz,$data);
		$yjgz->save();
		return redirect('/admin/sjx/yjgz');
	}
    public static function yjgzIndex(Request $request){
	    $admin = $request->session()->get('admin');
	    $data = $request->all();
	    $sjxs = SJXManager::getSJXs();
	    $yjgzs=YJGZManager::getAll();
	    foreach ($yjgzs as $yjgz) {
		    $yjgz->created_at_str = DateTool::formateData($yjgz->created_at, 1);
		    //$yjgz->doctor = DoctorManager::getDoctorById($sjx->doctor_id);
		    $yjgz->sjx = SJXManager::getSJXById($yjgz->sjx_id);
	    }
	    $hposs = HposManager::getHPosList();
	    return view('admin.yjgz.yjgz', ['admin' => $admin, 'datas' => $yjgzs, 'sjxs' => $sjxs]);
    }
    
	public static function deleteYJGZ(Request $request){
		$data = $request->all();
//        dd($data);
		//合规校验account_type
		$requestValidationResult = RequestValidator::validator($request->all(), [
			'id' => 'required',
		]);
		if ($requestValidationResult !== true) {
			return ApiResponse::makeResponse(false, $requestValidationResult, ApiResponse::MISSING_PARAM);
		}
		$yjgz=YJGZManager::getYJGZbyId($data['id']);
		$yjgz->delete();
		return redirect('/admin/sjx/yjgz');
	}
}