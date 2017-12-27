@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">患者管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建患者信息
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{--条件搜索--}}
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="">
                    <!-- form start -->
                    <form action="{{URL::asset('/admin/user/search')}}" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input id="search_word" name="search_word" type="text" class="form-control"
                                           placeholder="根据用户名称/手机号码搜索"
                                           value="">
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-info btn-block btn-flat" onclick="">
                                        搜索
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>

        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>头像</th>
                                <th>姓名</th>
                                <th>性别</th>
                                <th>年龄</th>
                                <th>电话</th>
                                <th>手术</th>
                                <th>医师</th>
                                <th>康复师</th>
                                <th class="opt-th-width">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td>
                                        <img src="{{ $data->avatar ? $data->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             class="img-rect-30 radius-5">
                                    </td>
                                    <td><span class="line-height-30">{{$data->real_name}}</span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                           @if($data->gender=="0")
                                                保密
                                            @endif
                                            @if($data->gender=="1")
                                                男
                                            @endif
                                            @if($data->gender=="2")
                                                女
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                        @if ($data->age)
                                                {{$data->age}}岁
                                            @else
                                                --
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">{{$data->phonenum}}
                                    </td>
                                    <td>
                                        <div class="line-height-30 text-oneline con-th-width-m">
                                            @if ($data->userCase&&$data->userCase->kfmb)
                                                {{$data->userCase->kfmb->name}}
                                            @else
                                                --
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            @if ($data->userCase&&$data->userCase->zz_doctor)
                                                {{$data->userCase->zz_doctor->name}}
                                            @else
                                                --
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            @if ($data->userCase&&$data->userCase->kf_doctor)
                                                {{$data->userCase->kf_doctor->name}}
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
                                                  title="编辑该患者基本信息">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <a href="{{URL::asset('/admin/user/userCaseIndex')}}?user_id={{$data->id}}"
                                               class="btn btn-social-icon btn-info opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="管理患者病例">
                                                <i class="fa fa-th-list opt-btn-i-size"></i>
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
        <!-- /.row -->
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div class="col-sm-7 text-right">
                {!! $datas->links() !!}
            </div>
        </div>

        {{--新建对话框--}}
        <div class="modal fade modal-margin-top-m" id="addUserModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">新建/编辑</h4>
                    </div>
                    <form id="editUser" action="{{URL::asset('/admin/user/edit')}}" method="post"
                          class="form-horizontal"
                          onsubmit="return checkValid();">
                        <div class="modal-body">
                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group hidden">
                                    <label for="id" class="col-sm-2 control-label">id*</label>

                                    <div class="col-sm-10">
                                        <input id="id" name="id" type="text" class="form-control"
                                               placeholder="自动生成id"
                                               value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="real_name" class="col-sm-2 control-label">姓名</label>
                                    <div class="col-sm-10">
                                        <input id="real_name" name="real_name" type="text" class="form-control"
                                               placeholder="请输入患者名称"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="birthday" class="col-sm-2 control-label">出生日期</label>
                                    <div class="col-sm-10">
                                        <input id="birthday" name="birthday" type="date" class="form-control"
                                               placeholder="请输入患者出生日期"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phonenum" class="col-sm-2 control-label">联系方式</label>
                                    <div class="col-sm-10">
                                        <input id="phonenum" name="phonenum" type="text" class="form-control"
                                               placeholder="请输入手机号码"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="col-sm-2 control-label">性别</label>
                                    <div class="col-sm-10">
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="1" {{ $data->gender==='1' ? 'selected' : '' }}>男</option>
                                            <option value="2" {{ $data->gender==='2' ? 'selected' : '' }}>女</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="url"/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" id="addUserModal_confirm_btn" data-value=""
                                    class="btn btn-success">确定
                            </button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    </section>
@endsection

@section('script')
    <script type="application/javascript">

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        //合规校验
        function checkValid() {
            var real_name = $("#real_name").val();
            //合规校验
            if (judgeIsNullStr(real_name)) {
                $("#real_name").focus();
                return false;
            }
            var birthday = $("#birthday").val();
            if (judgeIsNullStr(birthday)) {
                $("#birthday").focus();
                return false;
            }
            var phonenum = $("#phonenum").val();
            if (judgeIsNullStr(phonenum)) {
                $("#phonenum").focus();
                return false;
            }
            return true;
        }

        //点击新建康复患者信息
        function clickAdd() {
            //清空模态框
            $("#editUser")[0].reset();
            $("#addUserModal").modal('show');
        }

        //点击编辑患者信息
        function clickEdit(user_id) {
            console.log("clickEdit user_id:" + user_id);
            getUserById("{{URL::asset('')}}", {id: user_id}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#real_name").val(msgObj.real_name);
                    $("#phonenum").val(msgObj.phonenum);
                    $("#gender").val(msgObj.gender);
                    $("#birthday").val(msgObj.birthday);
                    //展示modal
                    $("#addUserModal").modal('show');
                }
            })
        }


    </script>
@endsection