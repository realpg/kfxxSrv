@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">量表管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建量表
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
                                <th>标题</th>
                                <th>类型</th>
                                <th>描述</th>
                                <th>时间</th>
                                <th>录入人</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr style="word-break:break-all;width: 17%" id="tr_{{$data->id}}">
                                    <td>
                                        <div class="line-height-30  text-oneline ">
                                            {{$data->name}}
                                        </div>
                                    </td>
                                    <td style="width: 8%">
                                        <div class="line-height-30  text-oneline ">
                                            @if($data->type == '0')
                                                普通量表
                                            @elseif($data->type == '1')
                                                验证量表
                                            @else
                                                随访量表
                                            @endif
                                        </div>
                                    </td>

                                    <td style="word-break:break-all;width: 17%">
                                        <div class="line-height-30 text-info text-oneline ">
                                            {{$data->desc}}
                                        </div>
                                    </td>

                                    <td style="word-break:break-all;width: 17%"><span
                                                class="line-height-30">{{$data->created_at_str}}</span>
                                    </td>

                                    <td >
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
                                            <span class="label label-default line-height-30">隐藏</span>
                                        @else
                                            <span class="label label-success line-height-30">展示</span>
                                        @endif

                                    </td>

                                    <td width="22%">
                                        <span class="line-height-30">
                                            <a href="{{URL::asset('/admin/lb/setStatus')}}/{{$data->id}}?opt=1"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="在小程序页面中展示该量表">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/lb/setStatus')}}/{{$data->id}}?opt=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="在小程序页面中隐藏该量表图">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑该量表"
                                                  onclick="clickEdit({{$data->id}})">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该量表"
                                                  onclick="clickDel({{$data->id}})">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </span>
                                            <a href="{{URL::asset('/admin/lb/setQue')}}/?id={{$data->id}}"
                                               class="btn btn-social-icon btn-primary margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="设置量表问题">
                                                <i class="fa fa-question-circle opt-btn-i-size"></i>
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
    </section>
    {{--新建对话框--}}
    <div class="modal fade -m" id="addADModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理量表</h4>
                </div>
                <form id="editLB" action="{{URL::asset('/admin/lb/edit')}}" method="post" class="form-horizontal"
                      onsubmit="return checkValid();">
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
                                <label for="title" class="col-sm-2 control-label">录入人id</label>
                                <div class="col-sm-10">
                                    <input id="doctor_id" name="doctor_id" type="text" class="form-control"
                                           value="{{$admin->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">录入人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                           value="{{$admin->name}}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="seq" class="col-sm-2 control-label">标题*</label>

                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control"
                                           placeholder="请输入"
                                           value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="seq" class="col-sm-2 control-label">类型</label>

                                <div class="col-sm-10">
                                    <select name="type" class="form-control" id="type">
                                        <option value="0">普通量表</option>
                                        <option value="1">验证量表</option>
                                        <option value="2">随访量表</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="seq" class="col-sm-2 control-label">简介*</label>

                                <div class="col-sm-10">
                                    <textarea id="desc" name="desc" type="text" class="form-control"
                                              rows="3"></textarea>
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
                    <p>您确认要删除该量表图片吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="delConfrimModal_confirm_btn" data-value="" onclick="delAD();"
                            class="btn btn-success"
                            data-dismiss="modal">确定
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script')
    <script type="application/javascript">

        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        //点击删除
        function clickDel(ad_id) {
            console.log("clickDel ad_id:" + ad_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", ad_id);
            $("#delConfrimModal").modal('show');
        }

        //删除量表
        function delAD() {
            var ad_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delAD ad_id:" + ad_id);
            //进行tr隐藏
            $("#tr_" + ad_id).fadeOut();
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/lb/del')}}/" + ad_id;
        }

        //点击新建量表图
        function clickAdd() {
            //清空模态框
            $("#editLB")[0].reset();

            $("#addADModal").modal('show');
        }

        //点击编辑
        function clickEdit(ad_id) {
            console.log("clickEdit ad_id:" + ad_id);
            getLBById("{{URL::asset('')}}", {id: ad_id}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    $("#type").val(msgObj.type);
                    $("#desc").val(msgObj.desc);
                    //展示modal
                    $("#addADModal").modal('show');
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