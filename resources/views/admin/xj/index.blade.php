@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">宣教管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <a href="{{URL::asset('/admin/xj/editXJ')}}">
                    <button type="button" class="btn btn-primary">
                        +新建宣教
                    </button>
                </a>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>标题</th>
                                <th>创建时间</th>
                                <th>作者</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>
                                        <div class="line-height-30 text-info text-oneline con-th-width-ll">
                                            {{$data->title}}
                                        </div>
                                    </td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            {{$data->author}}
                                        </span>
                                    </td>
                                    <td>
                                        @if($data->status === '0')
                                            <span class="label label-default line-height-30">失效</span>
                                        @else
                                            <span class="label label-success line-height-30">生效</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="line-height-30 ">
                                            <a href="{{URL::asset('/admin/xj/setStatus')}}/{{$data->id}}?opt=1"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="生效的宣教图文才可以推送给客户">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/xj/setStatus')}}/{{$data->id}}?opt=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="失效的宣教图文不会推送给客户">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/xj/editXJ')}}?id={{$data->id}}"
                                               class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               title="编辑宣教基本信息">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </a>
                                            <span class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除宣教"
                                                  onclick="clickDel({{$data->id}})">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div class="col-sm-7 text-right">
                {!! $datas->links() !!}
            </div>
        </div>
        <!-- /.row -->
    </section>


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
                    <p>您确认要删除该宣教图文吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="delConfrimModal_confirm_btn" data-value="" onclick="delXJ();"
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
        function clickDel(xj_id) {
            console.log("clickDel xj_id:" + xj_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", xj_id);
            $("#delConfrimModal").modal('show');
        }

        //删除宣教
        function delXJ() {
            var xj_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delXJ xj_id:" + xj_id);
            //进行tr隐藏
            $("#tr_" + xj_id).fadeOut();
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/xj/del')}}/" + xj_id;
        }

    </script>
@endsection