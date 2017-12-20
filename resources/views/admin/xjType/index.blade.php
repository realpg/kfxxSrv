@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">宣教类别管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建宣教类别
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
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>类型</th>
                                <th class="con-th-width-l">描述</th>
                                <th>录入时间</th>
                                <th class="opt-th-width-m">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">{{$data->name}}</span></td>
                                    <td class="con-th-width-l"><span
                                                class="line-height-30 text-oneline">{{$data->desc}}</span>
                                    </td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span></td>
                                    <td class="opt-th-width-m">
                                        <span class="line-height-30">
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑宣教类别"
                                                  onclick="clickEdit({{$data->id}})">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除宣教类别"
                                                  onclick="clickDel({{$data->id}})"
                                                  data-toggle="modal" data-target="#tip_modal">
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
    </section>

    {{--新建对话框--}}
    <div class="modal fade modal-margin-top-m" id="addXJTypeModel" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">新建/编辑宣教类别</h4>
                </div>
                <form id="editXJType" action="{{URL::asset('/admin/xjType/edit')}}" method="post"
                      class="form-horizontal" onsubmit="return checkValid();">
                    {{csrf_field()}}
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">id</label>
                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="doctor_id" class="col-sm-2 control-label">录入人id</label>
                                <div class="col-sm-10">
                                    <input id="doctor_id" name="doctor_id" type="text" class="form-control"
                                           value="{{$admin->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="admin_name" class="col-sm-2 control-label">录入人</label>
                                <div class="col-sm-10">
                                    <input id="admin_name" type="text" class="form-control"
                                           value="{{$admin->name}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">分类*</label>

                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control"
                                           placeholder="请输入分类名称"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-2 control-label">描述</label>
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
                        <button type="submit" id="addADModal_confirm_btn" data-value=""
                                class="btn btn-success">确定
                        </button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    {{--删除模态框--}}
    <div class="modal fade modal-margin-top" id="delConfrimModel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">提示信息</h4>
                </div>
                <div class="modal-body">
                    <div>由于宣教分类关联了较多信息，因此需要慎重删除，请联系系统管理员进行咨询和处理</div>
                    <div class="margin-top-10">
                        <a href="">宣教分类的作用，以及如何管理宣教类别</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="application/javascript">

        //提示
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })


        //点击删除
        function clickDel(xjType_id) {
            console.log("clickDel xjType_id:" + xjType_id);
            //为删除按钮赋值
            $("#delConfrimModel").modal('show');
        }

        //点击新建轮播图
        function clickAdd() {
            //清空模态框
            $("#editXJType")[0].reset();
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            $("#addXJTypeModel").modal('show');
        }


        //点击编辑
        function clickEdit(xjType_id) {
            console.log("clickEdit xjType_id:" + xjType_id);
            getXJTypeById("{{URL::asset('')}}", {id: xjType_id}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    $("#desc").val(msgObj.desc);
                    //展示modal
                    $("#addXJTypeModel").modal('show');
                }
            })
        }

        //合规校验
        function checkValid() {
            console.log("checkValid");
            var name = $("#name").val();
            //合规校验
            if (judgeIsNullStr(name)) {
                $("#name").focus();
                return false;
            }
            var desc = $("#desc").val();
            if (judgeIsNullStr(desc)) {
                $("#desc").focus();
                return false;
            }
            return true;
        }

    </script>
@endsection