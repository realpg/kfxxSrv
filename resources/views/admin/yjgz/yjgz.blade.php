@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">预警规则管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建预警规则
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
                    <form action="{{URL::asset('/admin/sjx/serachYJGZ')}}" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <select name="sjx_id" class="form-control">
                                            <option value="0">全部</option>
                                        @foreach($sjxs as $sjx)
                                            <option value="{{$sjx->id}}"
                                            @if(isset($search_sjx_id))
                                                {{ $sjx->id == $search_sjx_id ? 'selected':''}}
                                                    @endif
                                            >{{$sjx->name}}</option>
                                        @endforeach
                                    </select>
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
                                <th>数据项</th>
                                <th>类型</th>
                                <th>最大值</th>
                                <th>最小值</th>
                                <th>趋势</th>
                                <th>录入时间</th>
                                <th class="opt-th-width-m">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">{{$data->sjx->name}}</span></td>
                                    <td>
                                        @if($data->type=="0")
                                            <span class="line-height-30">阈值</span>
                                        @endif
                                        @if($data->type=="1")
                                            <span class="line-height-30">双侧差值</span>
                                        @endif
                                        @if($data->type=="2")
                                            <span class="line-height-30">多次对比</span>
                                        @endif
                                    </td>
                                    <td><span class="line-height-30">{{$data->max_value}}</span></td>
                                    <td><span class="line-height-30">{{$data->min_value}}</span></td>
                                    <td>
                                        <span class="line-height-30">{{$data->trend == 0 ? '忽略':($data->trend >0?'上升':'下降')}}</span>
                                    </td>
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
                                            <a href="{{url('/admin/sjx/deleteYJGZ')}}?id={{$data->id}}">
                                            <span class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                                  onclick=""
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该数据项">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </span>
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

        {{--分页--}}
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div class="col-sm-7 text-right">
                {{--{!! $datas->links() !!}--}}
            </div>
        </div>

        {{--模态框--}}
        <div class="modal fade " id="tip_modal" tabindex="-1" role="dialog"
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
        <div class="modal fade -m" id="addSJXModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">新建/编辑数据项</h4>
                    </div>
                    <form id="editSJX" action="{{URL::asset('/admin/sjx/editYJGZ')}}" method="post"
                          class="form-horizontal"
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
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="sjx_id" class="col-sm-2 control-label">数据项</label>
                                    <div class="col-sm-10">
                                        <select id="sjx_id" name="sjx_id" class="form-control">
                                            @foreach($sjxs as $sjx)
                                                <option value="{{$sjx->id}}">{{$sjx->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="type" class="col-sm-2 control-label">类型</label>
                                    <div class="col-sm-10">
                                        <select id="type" name="type" class="form-control">
                                            <option value="0">阈值</option>
                                            <option value="1">双侧差值</option>
                                            <option value="2">多次对比</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="max_value" class="col-sm-2 control-label">最大值</label>
                                    <div class="col-sm-10">
                                        <input id="max_value" name="max_value" type="number" class="form-control"
                                               placeholder="请输入最大值"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="min_value" class="col-sm-2 control-label">最小值</label>
                                    <div class="col-sm-10">
                                        <input id="min_value" name="min_value" type="number" class="form-control"
                                               placeholder="请输入最小值"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="trend" class="col-sm-2 control-label">趋势</label>
                                    <div class="col-sm-10">
                                        <select id="trend" name="trend" class="form-control">
                                            <option value="0">忽略</option>
                                            <option value="1">上升</option>
                                            <option value="-1">下降</option>
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
            $("#editSJX")[0].reset();
            $("#doctor_id").val("{{$admin->id}}");
            $("#addSJXModal").modal('show');
        }

        //点击编辑
        function clickEdit(sjx_id) {
            console.log("clickEdit sjx_id:" + sjx_id);
            getYJGZById("{{URL::asset('')}}", {id: sjx_id, _token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    console.log("sjx_id:" + msgObj.sjx_id);
                    $("#sjx_id").val(msgObj.sjx_id);
                    $("#type").val(msgObj.type);
                    $("#max_value").val(msgObj.max_value);
                    $("#min_value").val(msgObj.min_value);
                    $("#trend").val(msgObj.trend);
                    //展示modal
                    $("#addSJXModal").modal('show');
                }
            })
        }

        //合规校验
        function checkValid() {
//            var name = $("#name").val();
//            //合规校验
//            if (judgeIsNullStr(name)) {
//                $("#name").focus();
//                return false;
//            }
            return true;
        }


        //点击删除数据项
        function clickDel(id) {
            console.log("clickDel id:" + id);
            //为删除按钮赋值
            var param = {id: id};
            deleteYJGZ("{{URL::asset('')}}", param, function (res) {
                console.log(res);
            })
        }

        //点击搜索全部
        function searchAll() {
            window.location = "{{URL::asset('/admin/sjx/index')}}";
        }

    </script>
@endsection