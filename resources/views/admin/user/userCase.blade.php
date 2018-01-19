@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">患者管理</li>
                    <li class="active">用户病例管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +添加患者病例
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="white-bg">
            <div style="padding: 15px;">
                <div class="margin-top-10 font-size-14 grey-bg">
                    <div style="padding: 10px;">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td rowspan="2">
                                    <img src="{{ $user->avatar ? $user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                         style="width: 60px;height: 60px;">
                                </td>
                                <td>患者</td>
                                <td>{{ $user->real_name }}</td>
                                <td>性别</td>
                                <td>
                                    @if($user->gender=="0")
                                        保密
                                    @endif
                                    @if($user->gender=="1")
                                        男
                                    @endif
                                    @if($user->gender=="2")
                                        女
                                    @endif
                                </td>
                                <td>联系方式</td>
                                <td>{{$user->phonenum}}</td>
                            </tr>
                            <tr>
                                <td>出生日期</td>
                                <td>{{$user->birthday}}</td>
                                <td>年龄</td>
                                <td>{{$user->age}}岁</td>
                                <td>--</td>
                                <td>--</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>病例ID</th>
                                <th class="con-th-width-l">手术名称</th>
                                <th>医师</th>
                                <th>位置</th>
                                <th>患侧</th>
                                <th>手术时间</th>
                                <th class="opt-th-width">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">{{$data->id}}</span>
                                    <td class="con-th-width-l">
                                        <div class="line-height-30 text-oneline">
                                            @if(isset($data->surgery))
                                                {{$data->surgery->name}}
                                            @endif
                                        </div>
                                    </td>
                                    <td><span class="line-height-30">
                                             @if(isset($data->kf_doctor))
                                                {{$data->kf_doctor->name}}
                                            @endif
                                        </span>
                                    </td>
                                    <td><span class="line-height-30">
                                            @if(isset($data->hpos))
                                                {{$data->hpos->name}}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            @if ($data->side == "l")
                                                左侧
                                            @endif
                                            @if ($data->side == "r")
                                                右侧
                                            @endif
                                            @if ($data->side == "n")
                                                无
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            @if ($data->ss_time)
                                                {{$data->ss_time}}
                                            @else
                                                --
                                            @endif
                                        </span>
                                    </td>
                                    <td class="opt-th-width">
                                        <span class="line-height-30">
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  onclick="clickEdit({{$data->id}})"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑该病例基本信息">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <a href="{{URL::asset('/admin/user/userKFJH')}}?userCase_id={{$data->id}}"
                                               class="btn btn-social-icon btn-info opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="管理患者康复计划">
                                                <i class="fa fa-calendar opt-btn-i-size"></i>
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>

    <div class="modal fade -m" id="addUserCaseModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理患者病例</h4>
                </div>
                <form id="editUserCase" action="{{URL::asset('/admin/user/editUserCase')}}" method="post"
                      class="form-horizontal"
                      onsubmit="return checkValid();">
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">病例id</label>
                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           placeholder="病例id"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="user_id" class="col-sm-2 control-label">患者id</label>
                                <div class="col-sm-10">
                                    <input id="user_id" name="user_id" type="text" class="form-control" placeholder="id"
                                           value="{{ $user->id }}">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="doctor_id" class="col-sm-2 control-label">录入医师id</label>
                                <div class="col-sm-10">
                                    <input id="doctor_id" name="doctor_id" type="text" class="form-control"
                                           placeholder="id"
                                           value="{{ $admin->id }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="kf_doctor_id" class="col-sm-2 control-label">医师</label>
                                <div class="col-sm-10">
                                    <select id="kf_doctor_id" name="kf_doctor_id" class="form-control">
                                        @foreach($kf_doctors as $kf_doctor)
                                            <option value="{{$kf_doctor->id}}">{{$kf_doctor->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="surgery_id" class="col-sm-2 control-label">手术</label>
                                <div class="col-sm-10">
                                    <select id="surgery_id" name="surgery_id" class="form-control">
                                        @foreach($surgerys as $surgery)
                                            <option value="{{$surgery->id}}">{{$surgery->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="hpos_id" class="col-sm-2 control-label">位置</label>
                                <div class="col-sm-10">
                                    <select id="hpos_id" name="hpos_id" class="form-control">
                                        @foreach($hposs as $hpos)
                                            <option value="{{$hpos->id}}">{{$hpos->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="side" class="col-sm-2 control-label">患侧</label>
                                <div class="col-sm-10">
                                    <select id="side" name="side" class="form-control">
                                        <option value="l">左侧</option>
                                        <option value="r">右侧</option>
                                        <option value="n">无</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ss_time" class="col-sm-2 control-label">手术时间</label>
                                <div class="col-sm-10">
                                    <input id="ss_time" name="ss_time" type="date" class="form-control"
                                           placeholder="请选择手术时间"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-2 control-label">病例描述</label>
                                <div class="col-sm-10">
                                    <textarea id="desc" name="desc" class="form-control" rows="5"
                                              placeholder="请输入 ..."></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" id="addUserCaseModal_confirm_btn" data-value=""
                                class="btn btn-success">确定
                        </button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@endsection


@section('script')
    <script type="application/javascript">

        //入口函数
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        function clickAdd() {
            console.log("clickAdd()");
            //清空模态框
            $("#editUserCase")[0].reset();
            $("#user_id").val("{{$user->id}}");
            $("#doctor_id").val("{{$admin->id}}");
            //展示modal
            $("#addUserCaseModal").modal('show');
        }


        //点击编辑患者信息
        function clickEdit(userCase_id) {
            console.log("clickEdit userCase_id:" + userCase_id);
            getUserCaseById("{{URL::asset('')}}", {id: userCase_id, level: "0"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#kf_doctor_id").val(msgObj.kf_doctor_id);
                    $("#ss_time").val(msgObj.ss_time);
                    $("#desc").val(msgObj.desc);
                    $("#surgery_id").val(msgObj.surgery_id);
                    $("#hpos_id").val(msgObj.hpos_id);
                    $("#side").val(msgObj.side);
                    $("#addUserCaseModal").modal('show');
                }
            })
        }


        //合规校验
        function checkValid() {
            var ss_time = $("#ss_time").val();
            if (judgeIsNullStr(ss_time)) {
                return false;
            }
            return true;
        }

    </script>
@endsection