@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">手术管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建手术信息
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>手术名称</th>
                                <th>手术部位</th>
                                <th>录入医师</th>
                                <th>录入时间</th>
                                <th class="opt-th-width">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">{{$data->name}}</span>
                                    </td>
                                    <td><span class="line-height-30">{{$data->hpos->name}}</span>
                                    </td>
                                    <td><span class="line-height-30">{{$data->doctor->name}}</span>
                                    </td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span>
                                    </td>
                                    <td class="opt-th-width">
                                        <span class="line-height-30">
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  onclick="clickEdit({{$data->id}})"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑该手术信息">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                                  onclick="clickDel({{$data->id}});"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该手术信息">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </span>
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
        <div class="modal fade -m" id="addSurgeryModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">编辑手术信息</h4>
                    </div>
                    <form id="editUser" action="{{URL::asset('/admin/surgery/edit')}}" method="post"
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
                                <div class="form-group hidden">
                                    <label for="doctor_id" class="col-sm-2 control-label">录入医师id</label>
                                    <div class="col-sm-10">
                                        <input id="doctor_id" name="doctor_id" type="text" class="form-control"
                                               placeholder="id"
                                               value="{{ $admin->id }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">手术名称</label>
                                    <div class="col-sm-10">
                                        <input id="name" name="name" type="text" class="form-control"
                                               placeholder="请输入手术名称"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="hpos_id" class="col-sm-2 control-label">手术位置</label>
                                    <div class="col-sm-10">
                                        <select id="hpos_id" name="hpos_id" class="form-control">
                                            @foreach($hposs as $hpos)
                                                <option value="{{$hpos->id}}">{{$hpos->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="url"/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" id="addSurgeryModal_confirm_btn" data-value=""
                                    class="btn btn-success">确定
                            </button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        {{--删除对话框--}}
        <div class="modal fade " id="delConfrimModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">提示信息</h4>
                    </div>
                    <div class="modal-body">
                        <p>您确认要删除该手术信息吗？</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button id="delConfrimModal_confirm_btn" data-value="" onclick="delSurgery();"
                                class="btn btn-success"
                                data-dismiss="modal">确定
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </section>
@endsection

@section('script')
    <script type="application/javascript">

        $(document).ready(function () {
            $(".sidebar-menu li:nth-child(10)").addClass("active");
            $('[data-toggle="tooltip"]').tooltip()
        })

        //合规校验
        function checkValid() {
            var name = $("#name").val();
            //合规校验
            if (judgeIsNullStr(name)) {
                $("#name").focus();
                return false;
            }
            return true;
        }

        //点击新建康复手术信息
        function clickAdd() {
            //清空模态框
            $("#editUser")[0].reset();
            $("#doctor_id").val("{{$admin->id}}");
            $("#addSurgeryModal").modal('show');
        }

        //点击编辑手术信息
        function clickEdit(surgery_id) {
            console.log("clickEdit surgery_id:" + surgery_id);
            getSurgeryById("{{URL::asset('')}}", {id: surgery_id, _token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    //展示modal
                    $("#addSurgeryModal").modal('show');
                }
            })
        }

        //点击删除
        function clickDel(surgery_id) {
            console.log("clickDel surgery_id:" + surgery_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", surgery_id);
            $("#delConfrimModal").modal('show');
        }

        //删除轮播
        function delSurgery() {
            var surgery_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delSurgery surgery_id:" + surgery_id);
            //进行tr隐藏
            $("#tr_" + surgery_id).fadeOut();
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/surgery/del')}}/" + surgery_id;
        }


    </script>
@endsection