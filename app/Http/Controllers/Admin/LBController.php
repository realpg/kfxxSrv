<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/15
 * Time: 9:11
 */
namespace App\Http\Controllers\Admin;

use App\Components\DateTool;
use App\Components\LBMannager;
use App\Components\QNManager;
use App\Components\RequestValidator;
use App\Components\XJManager;
use App\Models\LB;
use App\Models\LBQuestion;
use Illuminate\Http\Request;

class LBController{
	
	//首页
	public static function index(Request $request){
		$admin = $request->session()->get('admin');
		$LBList=LBMannager::lblist_all();
		foreach ($LBList as $lb) {
			$lb->created_at_str = DateTool::formateData($lb->created_at, 1);
		}
		return view('admin.xj.index', ['admin' => $admin, 'datas' => $LBList]);
	}
	
	//根据id获取量表详情
	public function detail(Request $request,$id){
		$admin = $request->session()->get('admin');
		$lb=LBMannager::getLBById($id);
		$lb->steps=[];
		$lb->created_at_str =DateTool::formateData($lb->created_at,1);
		$lb =LBMannager::getLBDetailByLevel($lb,2);
		foreach ($lb->questions as $question) {
			$question->created_at_str = DateTool::formateData($question->created_at, 1);
		}
		//生成七牛token
		$upload_token = QNManager::uploadToken();
		return view('admin.xj.editStep', ['admin' => $admin, 'data' => $xj, 'upload_token' => $upload_token]);
	}
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
		$lb = LB::where('id', '=', $id)->first();
		$lb->status = $opt;
		$lb->save();
		return redirect('/admin/lb/index');
	}
	
	public function setQuestionPost(Request $request)
	{
		$data = $request->all();
		$lb_question = new LBQuestion();
		$lb_question = LBMannager::setQuestion($lb_question,$data);
		$lb_question->save();
		return redirect('/admin/xj/setStep/' . $lb_question->f_id);
	}
	//删除量表
	public static function del($id){
		if (is_numeric($id) !== true) {
			return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
		}
		$lb=LBMannager::getLBById($id);
		$lb->delete();
		return redirect('/admin/lb/index');
	}
	//删除问题
	public static function delQue(Request $request,$id){
		if (is_numeric($id) !== true) {
			return redirect()->action('\App\Http\Controllers\Admin\IndexController@error', ['msg' => '合规校验失败，请检查参数宣教id$id']);
		}
		$Que=LBMannager::getQuestionsById($id);
		$lb_id=$Que->lb_id;
		$Que->delete();
		return redirect('/admin/xj/setStep/' . $lb_id);
	}
	
	//新建或者编辑量表
	public function edit(Request $request){
		$admin = $request->session()->get('admin');
		$data = $request->all();
		$lb = new LB();
		//type
		if (array_key_exists('id', $data)) {
			$lb = LBMannager::getLBById($data['id']);
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
}