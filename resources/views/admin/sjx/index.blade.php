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
        {{--条件搜索--}}
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="">
                    <!-- form start -->
                    <form action="{{URL::asset('/admin/sjx/search')}}" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <select name="hpos_id" class="form-control">
                                        @foreach($hposs as $hpos)
                                            <option value="{{$hpos->id}}"
                                            @if(isset($search_hpos_id))
                                                {{ $hpos->id == $search_hpos_id ? 'selected':''}}
                                                    @endif
                                            >{{$hpos->name}}</option>
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
                                <th>位置</th>
                                <th>单/双侧</th>
                                <th>单位</th>
                                <th>录入时间</th>
                                <th class="opt-th-width-m">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td><span class="line-height-30">{{$data->name}}</span></td>
                                    <td>
                                        @if($data->type=="1")
                                            <span class="line-height-30">皮温</span>
                                        @endif
                                        @if($data->type=="2")
                                            <span class="line-height-30">皮疼</span>
                                        @endif
                                        @if($data->type=="3")
                                            <span class="line-height-30">角度</span>
                                        @endif
                                        @if($data->type=="4")
                                            <span class="line-height-30">围度</span>
                                        @endif
                                    </td>
                                    <td><span class="line-height-30">{{$data->hpos->name}}</span></td>
                                    <td><span class="line-height-30">{{$data->side == 0 ? '单侧':'双侧'}}</span></td>
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
                                            <span class="btn btn-social-icon btn-danger margin-right-10 opt-btn-size"
                                                  onclick="clickDel({{$data->id}});"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该数据项">
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

        {{--分页--}}
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div class="col-sm-7 text-right">
                {!! $datas->links() !!}
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
                    <form id="editSJX" action="{{URL::asset('/admin/sjx/edit')}}" method="post" class="form-horizontal"
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
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="hpos_id" class="col-sm-2 control-label">患处</label>
                                    <div class="col-sm-10">
                                        <select id="hpos_id" name="hpos_id" class="form-control">
                                            @foreach($hposs as $hpos)
                                                <option value="{{$hpos->id}}">{{$hpos->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="side" class="col-sm-2 control-label">采集侧</label>
                                    <div class="col-sm-10">
                                        <select id="side" name="side" class="form-control">
                                            <option value="0">单侧</option>
                                            <option value="1">双侧</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="is_dis_lr" class="col-sm-2 control-label">区分左右</label>
                                    <div class="col-sm-10">
                                        <select id="is_dis_lr" name="is_dis_lr" class="form-control">
                                            <option value="0">不区分</option>
                                            <option value="1">区分</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="is_dis_pos" class="col-sm-2 control-label">区分位置</label>
                                    <div class="col-sm-10">
                                        <select id="is_dis_pos" name="is_dis_pos" class="form-control">
                                            <option value="0">不区分</option>
                                            <option value="1">区分</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="type" class="col-sm-2 control-label">数据类型</label>
                                    <div class="col-sm-10">
                                        <select id="type" name="type" class="form-control">
                                            <option value="1">皮温</option>
                                            <option value="2">皮疼</option>
                                            <option value="3">角度</option>
                                            <option value="4">围度</option>
                                        </select>
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
            $("#editSJX")[0].reset();
            $("#doctor_id").val("{{$admin->id}}");
            $("#addSJXModal").modal('show');
        }

        //点击编辑
        function clickEdit(sjx_id) {
            console.log("clickEdit sjx_id:" + sjx_id);
            getSJXById("{{URL::asset('')}}", {id: sjx_id, _token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    console.log("hpos_id:" + msgObj.hpos_id);
                    $("#hpos_id").val(msgObj.hpos_id);
                    $("#side").val(msgObj.side);
                    $("#type").val(msgObj.type);
                    $("#unit").val(msgObj.unit);
                    $("#is_dis_lr").val(msgObj.is_dis_lr);
                    $("#is_dis_pos").val(msgObj.is_dis_pos);
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


        //点击删除数据项
        function clickDel(sjx_id) {
            console.log("clickDel sjx_id:" + sjx_id);
            //为删除按钮赋值
            $("#tip_modal").modal('show');
        }

        //点击搜索全部
        function searchAll() {
            window.location = "{{URL::asset('/admin/sjx/index')}}";
        }

    </script>
@endsection