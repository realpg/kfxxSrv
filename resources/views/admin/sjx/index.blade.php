@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">数据项管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建数据项
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
                                <th>数据项</th>
                                <th class="con-th-width-l">描述</th>
                                <th>单位</th>
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
                                    <td><span class="line-height-30">{{$data->unit}}</span></td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span></td>
                                    <td class="opt-th-width-m">
                                        <span class="line-height-30">
                                            <span onclick="clickEdit({{$data->id}})"
                                                  class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑该数据项">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <a href=""
                                               class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                               data-toggle="modal" data-target="#tip_modal"
                                               onclick="">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
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
            </div>
        </div>
        {{--模态框--}}
        <div class="modal fade modal-margin-top" id="tip_modal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">提示信息</h4>
                    </div>
                    <div class="modal-body">
                        <div>由于数据项关联了较多信息，因此需要慎重删除，请联系系统管理员进行咨询和处理</div>
                        <div class="margin-top-10">
                            <a href="">数据项的作用，以及如何管理数据项</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>

        {{--新建对话框--}}
        <div class="modal fade modal-margin-top-m" id="addSJXModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">新建/编辑数据项</h4>
                    </div>
                    <form action="{{URL::asset('/admin/sjx/edit')}}" method="post" class="form-horizontal"
                          onsubmit="return checkValid();">
                        <div class="modal-body">
                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group hidden">
                                    <label for="doctor_id" class="col-sm-2 control-label">id</label>
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
                                    <label class="col-sm-2 control-label">录入人</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                               value="{{$admin->name}}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">名称*</label>
                                    <div class="col-sm-10">
                                        <input id="name" name="name" type="text" class="form-control"
                                               placeholder="请输入数据项名称"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="desc" class="col-sm-2 control-label">描述</label>
                                    <div class="col-sm-10">
                                    <textarea id="desc" name="desc" class="form-control" rows="3"
                                              placeholder="请输入 ..."></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="unit" class="col-sm-2 control-label">单位</label>
                                    <div class="col-sm-10">
                                        <select id="unit" name="unit" class="form-control">
                                            <option value="mm">毫米(mm)
                                            </option>
                                            <option value="cm">厘米(cm)
                                            </option>
                                            <option value="m">米(m)</option>
                                            <option value="疼度">疼度</option>
                                            <option value="角度">角度</option>
                                            <option value="摄氏度">摄氏度</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="url"/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" id="addSJXModal_confirm_btn" data-value=""
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

        //提示
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })


        //点击新建数据项
        function clickAdd() {
            //清空模态框
            $("#id").val("");
            $("#name").val("");
            $("#desc").val("");
            $("#unit").val("cm");
            $("#addSJXModal").modal('show');
        }

        //点击编辑
        function clickEdit(sjx_id) {
            console.log("clickEdit sjx_id:" + sjx_id);
            getSJXById("{{URL::asset('')}}", {id: sjx_id}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    $("#desc").val(msgObj.desc);
                    $("#unit").val(msgObj.unit);
                    //展示modal
                    $("#addSJXModal").modal('show');
                }
            })
        }

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

    </script>
@endsection