@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>设置康复计划</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">康复模板</li>
            <li class="active">设置康复计划</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="box box-default">
                    <!-- form start -->
                    <form action="" method="post" class="form-horizontal"
                          onsubmit="return checkValid();">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="kfmb_id" class="col-sm-2 control-label">康复模板id</label>

                                <div class="col-sm-10">
                                    <input id="kfmb_id" name="kfmb_id" type="text" class="form-control"
                                           placeholder="父id"
                                           value="{{ $data->id }}">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="jh_id" class="col-sm-2 control-label">id</label>
                                <div class="col-sm-10">
                                    <input id="jh_id" name="jh_id" type="text" class="form-control" placeholder="id"
                                           value="{{ isset($jh->id) ? $jh->id : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">计划*</label>
                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control" placeholder="请输入计划名称"
                                           value="{{ isset($jh->name) ? $jh->name : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-2 control-label">描述*</label>
                                <div class="col-sm-10">
                                    <textarea id="desc" name="desc" class="form-control" rows="5"
                                              placeholder="请输入 ...">{{ isset($jh->desc) ? $jh->desc : '' }}</textarea>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="btime_type" class="col-sm-2 control-label">基线*</label>
                                <div class="col-sm-10">
                                    <select id="btime_type" name="btime_type" class="form-control">
                                        <option value="0" {{ $jh->btime_type==='0' ? 'selected' : '' }}>术后</option>
                                        <option value="1" {{ $jh->btime_type==='1' ? 'selected' : '' }}>首次弯腿后</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="start_time" class="col-sm-2 control-label">开始</label>
                                <div class="col-sm-10">
                                    <div class="form-inline">
                                        <input id="start_time" name="start_time" type="number"
                                               class="form-control pull-left"
                                               placeholder="开始时间"
                                               value="{{ isset($jh->start_time) ? $jh->start_time : '0' }}">
                                        <span class="pull-right text-info text-oneline" style="line-height: 30px;">计划的执行不早于开始时间</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="end_time" class="col-sm-2 control-label">结束</label>
                                <div class="col-sm-10">
                                    <div class="form-inline">
                                        <input id="end_time" name="end_time" type="number"
                                               class="form-control pull-left"
                                               placeholder="结束时间"
                                               value="{{ isset($jh->end_time) ? $jh->end_time : '3' }}">
                                        <span class="pull-right text-info text-oneline" style="line-height: 30px;">计划的执行不晚于结束时间</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info btn-block btn-flat">添加康复计划</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
            </div>
            <!--/.col (right) -->
            <div class="col-md-6">
                {{--作品预览信息--}}
                <div class="white-bg" style="padding: 30px;">
                    <div class="font-size-16">
                        {{$data->name}}
                    </div>
                    <div class="border-t margin-top-10 margin-bottom-10">
                    </div>

                    <div style="padding: 10px;">
                        <!--康复计划信息-->
                        @foreach($data->jhs as $jh)
                            <div>
                                <!--康复计划信息-->
                                <div class="border-bottom padding-bottom-20 padding-top-20">
                                    <div id="" class="margin-bottom-10">
                                        <!--标题-->
                                        <div class="font-size-18 text-info">任务<span
                                                    class="margin-left-10">{{$jh->name}}</span></div>
                                        @if($jh->desc)
                                            <div class="grey-bg margin-top-10" style="padding: 10px;">
                                                {{$jh->desc}}
                                            </div>
                                        @endif
                                        <div class="margin-top-10 font-size-16">
                                            <i class="fa fa-clock-o"></i>
                                            <span class="">执行时间</span>
                                            <span class="margin-left-15">
                                                {{ $jh->btime_type==='0' ? '手术后' : '' }}
                                                {{ $jh->btime_type==='1' ? '首次弯腿时间' : '' }}
                                            </span>
                                            <span class="margin-left-20">{{$jh->start_time}}天</span>
                                            <span class="margin-left-10">到</span>
                                            <span class="margin-left-10">{{$jh->end_time}}天</span>
                                        </div>
                                    </div>
                                    <div class="padding-bottom-10">
                                        <a href="{{URL::asset('/admin/kfmb/delJH/')}}/{{$jh->id}}"
                                           class="btn btn-danger btn-xs pull-right">删除</a>
                                        <a href="{{URL::asset('/admin/kfmb/setJH/')}}/{{$data->id}}?jh_id={{$jh->id}}"
                                           class="btn btn-warning btn-xs pull-right margin-right-10">编辑</a>
                                    </div>
                                    {{--宣教信息--}}
                                    <div class="margin-top-20">
                                        <div>
                                            <span class="padding-left-10"
                                                  style="border-left: 3px solid #31708f;">关联宣教</span>
                                            <span class="text-info btn btn-xs btn-warning margin-left-20"
                                                  onclick="clickGLJHSJ({{$jh->id}});">关联</span>
                                        </div>
                                        @if(isset($jh->xj))
                                            <div class="margin-top-10">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="text-info text-oneline">
                                                            {{$jh->xj->title}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="{{URL::asset('/admin/kfmb/delJHXJ')}}?jh_id={{$jh->id}}"
                                                           class="btn btn-danger btn-xs pull-right">删除</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="margin-top-20">
                                        <div>
                                            <span class="padding-left-10"
                                                  style="border-left: 3px solid #31708f;">采集数据</span>
                                            <span class="text-info btn btn-xs btn-warning margin-left-20"
                                                  onclick="clickTJJHSJ({{$jh->id}})">添加</span>
                                        </div>
                                        @foreach($jh->jhsjs as $jhsj)
                                            <div class="margin-top-10">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="text-info">
                                                            <span class="text-info">{{$jhsj->sj->name}}
                                                                ({{$jhsj->sj->unit}})</span>
                                                            <span class="margin-left-10">阈值范围 {{$jhsj->min_value}}
                                                                -{{$jhsj->max_value}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="{{URL::asset('/admin/kfmb/delCJSJ')}}?jhsj_id={{$jhsj->id}}"
                                                           class="btn btn-danger btn-xs pull-right">删除</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{--新建对话框--}}
        <div class="modal fade modal-margin-top-m" id="addJHXJModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">关联宣教</h4>
                    </div>
                    <form action="{{URL::asset('/admin/kfmb/setJH')}}/{{$data->id}}" method="post"
                          class="form-horizontal">
                        <div class="modal-body">
                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group hidden">
                                    <label for="jh_id" class="col-sm-2 control-label">关联宣教的计划id</label>
                                    <div class="col-sm-10">
                                        <input id="glxj_jh_id" name="jh_id" type="text" class="form-control"
                                               placeholder="id"
                                               value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="xj_ids" class="col-sm-2 control-label">关联宣教</label>
                                    <div class="col-sm-10">
                                        <select id="xj_ids" name="xj_ids" class="form-control">
                                            @foreach($all_xjs as $xj)
                                                <option value="{{$xj->id}}">{{$xj->title}}</option>
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
                            <button type="submit" id="addJHXJModal_confirm_btn" data-value=""
                                    class="btn btn-success">确定
                            </button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        {{--采集数据--}}
        <div class="modal fade modal-margin-top-m" id="addCJSJModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">采集数据</h4>
                    </div>
                    <form action="{{URL::asset('/admin/kfmb/setCJSJ')}}" method="post"
                          class="form-horizontal" onsubmit="return checkCJSJValid();">
                        <div class="modal-body">
                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group hidden">
                                    <label for="jh_id" class="col-sm-2 control-label">关联宣教的计划id</label>
                                    <div class="col-sm-10">
                                        <input id="cjsj_jh_id" name="jh_id" type="text" class="form-control"
                                               placeholder="id"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="xj_ids" class="col-sm-2 control-label">采集数据</label>
                                    <div class="col-sm-10">
                                        <select id="sjx_id" name="sjx_id" class="form-control">
                                            @foreach($all_sjxs as $sjx)
                                                <option value="{{$sjx->id}}">{{$sjx->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jh_id" class="col-sm-2 control-label">最小值*</label>
                                    <div class="col-sm-10">
                                        <input id="min_value" name="min_value" type="number" class="form-control"
                                               placeholder="该值为阈值最小值，小于最小值将报警"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jh_id" class="col-sm-2 control-label">最大值*</label>
                                    <div class="col-sm-10">
                                        <input id="max_value" name="max_value" type="number" class="form-control"
                                               placeholder="该值为阈值最大值，大于最大值将报警"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="url"/>
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="submit" id="addCJSJModal_confirm_btn" data-value=""
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
    <script src="{{ URL::asset('js/qiniu.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/plupload.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/moxie.js') }}"></script>
    <script type="application/javascript">

        //合规校验
        function checkValid() {
            var name = $("#name").val();
            //合规校验
            if (judgeIsNullStr(text)) {
                $("#text").focus();
                return false;
            }
            return true;
        }

        //采集数据form的合规校验
        function checkCJSJValid() {
            var min_value = $("#min_value").val();
            //合规校验
            if (judgeIsNullStr(min_value)) {
                $("#min_value").focus();
                return false;
            }
            var max_value = $("#max_value").val();
            //合规校验
            if (judgeIsNullStr(max_value)) {
                $("#max_value").focus();
                return false;
            }
            return true;
        }

        //关联宣教
        function clickGLJHSJ(jh_id) {
            console.log("clickGLJHSJ jh_id:" + jh_id);
            //为删除按钮赋值
            $("#addJHXJModal_confirm_btn").attr("data-value", jh_id);
            $("#glxj_jh_id").val(jh_id);
            $("#addJHXJModal").modal('show');
        }

        //添加采集数据
        function clickTJJHSJ(jh_id) {
            console.log("clickGLJHSJ jh_id:" + jh_id);
            $("#max_value").val("");
            $("#min_value").val("");
            //为删除按钮赋值
            $("#addCJSJModal").attr("data-value", jh_id);
            $("#cjsj_jh_id").val(jh_id);
            $("#addCJSJModal").modal('show');
        }


    </script>
@endsection