@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">患者管理</li>
                    <li class="active">采集数据管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>采集时间</th>
                            <th>数据项名称</th>
                            <th>位置</th>
                            <th>患侧</th>
                            <th>测量值</th>
                            <th>详细位置</th>
                            <th>结果</th>
                            <th class="opt-th-width">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td>
                                    <div class="weui-cells__title">{{$data->created_at}}</div>
                                </td>
                                <td>
                                    <div class="weui-cell__bd">{{$data->sjx->name}}</div>
                                </td>
                                <td>
                                    <div class="weui-cell__ft">{{$data->hpos->name}}</div>
                                </td>
                                <td>
                                    @if($data->c_side=='n')
                                        <div class="weui-cell__ft">不区分</div>
                                    @elseif($data->c_side=='l')
                                        <div class="weui-cell__ft">左侧</div>
                                    @elseif($data->c_side=='r')
                                        <div class="weui-cell__ft">右侧</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="weui-cell__ft">{{$data->value}}</div>
                                </td>
                                <td>
                                    @if($data->sjx->is_dis_pos==1)
                                        <div class="weui-cell__ft">{{$data->c_pos}}</div>
                                    @else
                                        <div>不区分</div>
                                    @endif
                                </td>
                                <td>

                                    @if($data->result==0)
                                        <div class="weui-cell__ft" style="color:green">正常</div>
                                    @elseif($data->result==1)
                                        <div class="weui-cell__ft" style="color:red">异常</div>
                                    @endif
                                </td>
                                <td class="opt-th-width">
                                    <span class="line-height-30">

                                        <span onclick="showDetail({{$data->id}})"
                                              class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                              data-toggle="tooltip"
                                              data-placement="top"
                                              title="详细信息">
                                        <i class="fa fa-th-list opt-btn-i-size"></i>
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

    <!--新建编辑计划步骤对话框-->
    <div class="modal fade -m" id="Modal" tabindex="-1" role="dialog">


    </div>

    <script id="Modal-content-template" type="text/x-dot-template">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">数据详细信息</h4>
                </div>
                <form id="editJH" action="" method="post" class="form-horizontal">
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">姓名</label>

                                <div class="col-sm-9">
                                    <div id="name" name="name" class="form-control">
                                        <a href="{{URL::asset('/admin/user/userCaseIndex')}}/?user_id=@{{=it.user_id}}">
            <span class="line-height-30">
                @{{?it.user}}
                        @{{=it.user.real_name}}
                @{{?? }}
                    未知
                @{{? }}
            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-3 control-label">采集时间</label>

                                <span class="col-sm-9">
                                    <span class="line-height-30 form-control">@{{=it.created_at}}</span>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-3 control-label">数据项名称</label>
                                <div class="col-sm-9">
                                    <span class="line-height-30 form-control">@{{=it.sjx.name}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-3 control-label">采集位置</label>
                                <div class="col-sm-9">
                                    <span class="line-height-30 form-control">@{{=it.hpos.name}}</span>
                                </div>
                            </div>
                            @{{?it.sjx.is_dis_pos==1 }}
                            <div class="form-group">
                                <label for="desc" class="col-sm-3 control-label">详细位置</label>
                                <div class="col-sm-9">
                                    <span class="line-height-30 form-control">@{{=it.c_pos}}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-3 control-label">说明图片</label>
                                <div class="col-sm-9 line-height-30">
                                    <img src="@{{=it.hpos.img }}">
                                </div>
                            </div>
                            @{{? }}
                            <div class="form-group">
                                <label for="desc" class="col-sm-3 control-label">类型</label>
                                <div class="col-sm-9">
                                    @{{?it.sjx.type==1}}
                                    <span class="line-height-30 form-control">皮温</span>
                                    @{{??it.sjx.type==2}}
                                    <span class="line-height-30 form-control">皮疼</span>
                                    @{{??it.sjx.type==3}}
                                    <span class="line-height-30 form-control">角度</span>
                                    @{{??it.sjx.type==4}}
                                    <span class="line-height-30 form-control">围度</span>
                                    @{{? }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-3 control-label">测量值</label>
                                @{{?it.value.length== 1}}
                                <div class="col-sm-9">
                                    <div class="line-height-30 form-control">@{{=it.value[0] }}</div>
                                </div>
                                @{{??it.value.length== 2}}
                                <div class="col-sm-9 line-height-30 pull-left">
                                    <div class="form-control">
                                        <span class="col-sm-4">左侧:@{{=it.value[0] }}</span>
                                        <span class="col-sm-1">|</span>
                                        <span class="col-sm-4">右侧:@{{=it.value[1] }}</span>
                                    </div>
                                </div>
                                @{{? }}
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-3 control-label">单位</label>
                                <div class="col-sm-9">
                                    <div class="line-height-30 form-control">@{{=it.sjx.unit }}</div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-success"
                                        onclick="hideModal()">确定
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </script>
@endsection


@section('script')
    <script type="application/javascript">
        function showDetail(id) {
            console.log(id)
            var param = {
                id: id
            }
            getCJSJDetailById("{{URL::asset('')}}", param, function (ret) {

                var data = ret.ret;
                data.value = ret.ret.value.split(',');
                $("#Modal").empty();
                console.log(ret.ret, data);
                var interText = doT.template($("#Modal-content-template").text());
                $("#Modal").html(interText(data));
                $("#Modal").modal('show');
            });
        }

        function hideModal() {
            $("#Modal").modal('hide');
        }

    </script>
@endsection