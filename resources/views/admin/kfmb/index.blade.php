@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">康复模板管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建康复模板
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
                                <th>名称</th>
                                <th>录入时间</th>
                                <th>医师头像</th>
                                <th>录入医师</th>
                                <th>状态</th>
                                <th class="opt-th-width-l">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30 text-info">{{$data->name}}</span></td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span>
                                    </td>
                                    <td>
                                        <img src="{{ $data->doctor->avatar ? $data->doctor->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             class="img-rect-30 radius-5">
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                        @if ($data->doctor)
                                                {{$data->doctor->name}}
                                            @else
                                                未知
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @if($data->status === '0')
                                            <span class="label label-default line-height-30">失效</span>
                                        @else
                                            <span class="label label-success line-height-30">生效</span>
                                        @endif

                                    </td>
                                    <td class="opt-th-width-l">
                                        <span class="line-height-30">
                                            <a href="{{URL::asset('/admin/kfmb/setStatus')}}/{{$data->id}}?opt=1"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="模板生效，可以使用">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/kfmb/setStatus')}}/{{$data->id}}?opt=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="模板失效，暂时无法使用">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  onclick="clickEdit({{$data->id}})"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑该模板">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                                  onclick="clickDel({{$data->id}});"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该模板">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </span>
                                            <a href="{{URL::asset('/admin/kfmb/setStep')}}/{{$data->id}}"
                                               class="btn btn-social-icon btn-primary margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="设置康复模板的图文">
                                                <i class="fa fa-image opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/kfmb/setJH')}}/{{$data->id}}"
                                               class="btn btn-social-icon btn-info opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="设置康复模板计划">
                                                <i class="fa fa-calendar-plus-o opt-btn-i-size"></i>
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
        <div class="modal fade modal-margin-top-m" id="addKFMBModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">新建/编辑</h4>
                    </div>
                    <form action="{{URL::asset('/admin/kfmb/edit')}}" method="post" class="form-horizontal"
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
                                    <label for="doctor_id" class="col-sm-2 control-label">doctor_id*</label>
                                    <div class="col-sm-10">
                                        <input id="doctor_id" name="doctor_id" type="text" class="form-control"
                                               placeholder="录入医生id"
                                               value="{{$admin->id}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="admin_name" class="col-sm-2 control-label">录入人</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control"
                                               value="{{$admin->name}}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">名称*</label>
                                    <div class="col-sm-10">
                                        <input id="name" name="name" type="text" class="form-control"
                                               placeholder="请输入模板名称"
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
                            <button type="submit" id="addKFMBModal_confirm_btn" data-value=""
                                    class="btn btn-success">确定
                            </button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        {{--删除对话框--}}
        <div class="modal fade modal-margin-top" id="delConfrimModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">提示信息</h4>
                    </div>
                    <div class="modal-body">
                        <p>您确认要删除该康复模板吗？</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button id="delConfrimModal_confirm_btn" data-value="" onclick="delKFMB();"
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

        $(function () {
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
            var desc = $("#desc").val();
            if (judgeIsNullStr(desc)) {
                $("#desc").focus();
                return false;
            }
            return true;
        }

        //删除轮播
        function delKFMB() {
            var kfmb_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delAD kfmb_id:" + kfmb_id);
            //进行tr隐藏
            $("#tr_" + kfmb_id).fadeOut();
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/kfmb/del')}}/" + kfmb_id;
        }

        //点击删除
        function clickDel(kfmb_id) {
            console.log("clickDel kfmb_id:" + kfmb_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", kfmb_id);
            $("#delConfrimModal").modal('show');
        }

        //点击新建康复模板l
        function clickAdd() {
            //清空模态框
            $("#id").val("");
            $("#name").val("");
            $("#desc").val("");
            $("#addKFMBModal").modal('show');
        }

        //点击编辑
        function clickEdit(kfmb_id) {
            console.log("clickEdit kfmb_id:" + kfmb_id);
            getKFMBById("{{URL::asset('')}}", {id: kfmb_id, level: 0}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    $("#desc").val(msgObj.desc);
                    //展示modal
                    $("#addKFMBModal").modal('show');
                }
            })
        }


    </script>
@endsection