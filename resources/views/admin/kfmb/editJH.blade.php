@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">康复模板计划管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    保存模板计划
                </button>
            </div>
        </div>
        <div id="token">{{csrf_field()}}</div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="message-content">

        </div>
    </section>


    <script id="message-content-template" type="text/x-dot-template">
        <div class="white-bg">
            <div style="padding: 15px;">
                <div class="font-size-16 text-info">
                    @{{=it.name}}
                </div>
                <div class="margin-top-10 font-size-14 grey-bg">
                    <div style="padding: 10px;">
                        @{{=it.desc_str}}
                    </div>
                </div>
                <div class="margin-top-10  font-size-14">
                    点击查看：<a href="{{URL::asset('/admin/xj/editXJ')}}?id=@{{=it.xj_id}}" target="_blank"><span
                                class="text-aqua">宣教信息<i
                                    class="fa fa-link margin-left-5"></i></span></a>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row margin-top-10">
            <div class="col-md-12">
                <!-- The time line -->
                <ul class="timeline">
                    <!-- timeline time label -->
                    <li class="time-label">
                  <span class="bg-aqua-active">
                    模板计划
                  </span>
                    </li>
                    <!-- /.timeline-label -->
                    <li class="time-label">
                        <div class="text-center">
                            <img src="{{URL::asset('/img/add_button_icon.png')}}" style="width: 36px;height: 36px;"
                                 onclick="editJH(0,'add');">
                        </div>
                    </li>
                    <!-- timeline item -->
                    @{{for(var i=0;i
                    <it.jhs.length ;i++){}}
                    <li>
                        <i class="fa fa-clock-o bg-aqua"></i>

                        <div class="timeline-item">
                            <!--右侧-->
                            <span class="time font-size-14">@{{=it.jhs[i].btime_type_str}}</span>

                            <h3 class="timeline-header">

                                <button type="button" class="btn btn-info">
                                    @{{=it.jhs[i].start_time}}@{{=it.jhs[i].start_unit_str}}
                                </button>
                                -
                                <button type="button" class="btn btn-info">
                                    @{{=it.jhs[i].end_time}}@{{=it.jhs[i].end_unit_str}}
                                </button>
                            </h3>
                            <div class="timeline-body">
                                <div>
                                    @{{=it.jhs[i].desc_str}}
                                </div>
                                <div class="margin-top-10 grey-bg">

                                    @{{for(var j=0;j
                                    <it.jhs
                                            [i].jhsjs.length;j++){}}
                                    <div style="padding: 10px;">
                                        <i class="fa fa-align-justify"></i>
                                        <span class="margin-left-15">@{{=it.jhs[i].jhsjs[j].sjx.name}}
                                            (@{{=it.jhs[i].jhsjs[j].sjx.unit}})</span>
                                        <span class="margin-left-5 text-aqua">阈值范围 @{{=it.jhs[i].jhsjs[j].min_value}}
                                            - @{{=it.jhs[i].jhsjs[j].max_value}}</span>

                                        <i class="fa fa-minus-circle text-info pull-right btn"
                                           onclick="delSJ(@{{=i}},@{{=j}})"></i>
                                    </div>
                                    @{{}}}

                                    <div style="padding: 10px;" class="text-info btn">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="margin-left-15" onclick="editSJ(@{{=i}},'add');">添加数据采集</span>
                                    </div>
                                </div>
                            </div>

                            <div class="timeline-footer">
                                <div class="text-right margin-top-15"><img
                                            src="{{URL::asset('/img/up_pointer_icon.png')}}"
                                            class="opt-btn-size margin-right-10" onclick="moveUpJH(@{{=i}});"> <img
                                            src="{{URL::asset('/img/down_pointer_icon.png')}}"
                                            class="opt-btn-size margin-right-10" onclick="moveDownJH(@{{=i}});"> <img
                                            src="{{URL::asset('/img/edit_icon.png')}}"
                                            class="opt-btn-size margin-right-10" onclick="editJH(@{{=i}},'edit');"> <img
                                            src="{{URL::asset('/img/delete_icon.png')}}"
                                            class="opt-btn-size" onclick="delJH(@{{=i}});"></div>
                            </div>
                        </div>
                    </li>

                    <!-- END timeline item -->
                    <!-- timeline item -->
                    <!-- END timeline item -->
                    <!-- timeline time label -->
                    <li class="time-label">
                        <div class="text-center">
                            <img src="{{URL::asset('/img/add_button_icon.png')}}" style="width: 36px;height: 36px;"
                                 onclick="editJH(@{{=i+1}},'add')">
                        </div>
                    </li>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    @{{}}}
                </ul>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </script>


    <!--新建编辑计划对话框-->
    <div class="modal fade modal-margin-top-m" id="editJHModal" tabindex="-1" role="dialog">


    </div>

    <script id="editJHModal-content-template" type="text/x-dot-template">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理康复计划</h4>
                </div>
                <form id="editJH" action="" method="post" class="form-horizontal">
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="desc" class="col-sm-2 control-label">说明*</label>

                                <div class="col-sm-10">
                                    <textarea id="desc" name="desc" class="form-control" rows="5"
                                              placeholder="请输入 ...">@{{=it.desc}}</textarea>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="btime_type" class="col-sm-2 control-label">基线*</label>

                                <div class="col-sm-10">
                                    <select id="btime_type" name="btime_type" class="form-control">
                                        <option value="0">手术后</option>
                                        <option value="1">首次弯腿后
                                        </option>
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
                                               value="@{{=it.start_time}}">
                                        <select id="start_unit" class="form-control">
                                            <option value="0">天</option>
                                            <option value="1">周</option>
                                            <option value="2">月</option>
                                        </select>
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
                                               value="@{{=it.end_time}}">
                                        <select id="end_unit" class="form-control">
                                            <option value="0">天</option>
                                            <option value="1">周</option>
                                            <option value="2">月</option>
                                        </select>
                                        <span class="pull-right text-info text-oneline" style="line-height: 30px;">计划的执行不晚于结束时间</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" data-value="" class="btn btn-success"
                                onclick="clickEditJH(@{{=it.index}},'@{{=it.opt}}');">确定
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </script>


    <!--数据采集模板-->
    <div class="modal fade modal-margin-top-m" id="editSJModal" tabindex="-1" role="dialog">

    </div>


    <script id="editSJModal-content-template" type="text/x-dot-template">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理采集数据</h4>
                </div>
                <form id="editSJ" action="" method="post" class="form-horizontal">
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="sjx_id" class="col-sm-2 control-label">采集数据</label>
                                <div class="col-sm-10">
                                    <select id="sjx_id" name="sjx_id" class="form-control">
                                        @foreach($sjxs as $sjx)
                                            <option id="sjx_{{$sjx->id}}" data-name="{{$sjx->name}}"
                                                    data-unit="{{$sjx->unit}}"
                                                    value="{{$sjx->id}}">{{$sjx->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="min_value" class="col-sm-2 control-label">最小值*</label>

                                <div class="col-sm-10">
                                    <input id="min_value" name="min_value" type="number" class="form-control"
                                           placeholder="该值为阈值最小值，小于最小值将报警"
                                           value="@{{=it.min_value}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="max_value" class="col-sm-2 control-label">最大值*</label>

                                <div class="col-sm-10">
                                    <input id="max_value" name="max_value" type="number" class="form-control"
                                           placeholder="该值为阈值最大值，大于最大值将报警"
                                           value="@{{=it.max_value}}">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" data-value="" class="btn btn-success"
                                onclick="clickEditSJ(@{{=it.jh_index}},'add');">确定
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </script>


    <!--提示modal-->
    <div class="modal fade" id="tipModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">提示信息</h4>
                </div>
                <div class="modal-body" id="tipModalBody">

                </div>
                <div class="modal-footer">
                    <button id="delConfrimModal_confirm_btn" data-value=""
                            class="btn btn-success"
                            data-dismiss="modal">确定
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection


@section('script')
    <script type="application/javascript">
        //康复模板信息
        var kfmbInfo = {}

        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            var kfmb_id = getQueryString("id");
            if (judgeIsNullStr(kfmb_id)) {
                //没有id则报错
                $("#tipModalBody").html('<p>康复模板id为空，请联系管理员处理</p>');
                $("#tipModal").modal('show');
                return;
            } else {
                var param = {
                    id: kfmb_id,
                    level: "03"
                }
                getKFMBById("{{URL::asset('')}}", param, function (ret, err) {
                    //提示保存成功
                    if (ret.result == true) {
                        kfmbInfo = ret.ret;
                        loadHtml();
                    }
                })
            }
        });

        //点击编辑康复计划
        function editJH(index, edit_or_add) {
            console.log("index index:" + index + " edit_or_add:" + edit_or_add);
            var jhs = kfmbInfo.jhs;
            var jhObj = {
                desc: "",
                start_time: 0,
                start_unit: 0,
                end_time: 0,
                end_unit: 0,
                "index": index,
                "opt": edit_or_add,
                jhsjs: []
            };
            //如果是新建
            if (edit_or_add == "add") {

            } else {        //如果是编辑
                jhObj.desc = nullToEmptyStr(kfmbInfo.jhs[index].desc);
                jhObj.start_time = kfmbInfo.jhs[index].start_time;
                jhObj.start_unit = kfmbInfo.jhs[index].start_unit;
                jhObj.end_time = kfmbInfo.jhs[index].end_time;
                jhObj.end_unit = kfmbInfo.jhs[index].end_unit;
                jhObj.btime_type = kfmbInfo.jhs[index].btime_type;
            }
            console.log("jhObj:" + JSON.stringify(jhObj));
            var interText = doT.template($("#editJHModal-content-template").text());
            $("#editJHModal").html(interText(jhObj));
            /*
             * 需要进行select赋值
             */
            $("#start_unit").val(jhObj.start_unit);
            $("#end_unit").val(jhObj.end_unit);
            $("#editJHModal").modal('show');
        }


        //点击保存
        function clickEditJH(index, edit_or_add) {
            var jhObj = {
                desc: $("#desc").val(),
                btime_type: $("#btime_type").val(),
                start_time: $("#start_time").val(),
                start_unit: $("#start_unit").val(),
                end_time: $("#end_time").val(),
                end_unit: $("#end_unit").val(),
                jhsjs: []
            };

            //合规校验
            if (judgeIsNullStr(jhObj.desc)) {
                $("#desc").focus();
                return;
            }
            if (judgeIsNullStr(jhObj.start_time)) {
                $("#start_time").focus();
                return;
            }
            if (judgeIsNullStr(jhObj.end_time)) {
                $("#end_time").focus();
                return;
            }
            //进行操作
            if (edit_or_add == "add") {
                kfmbInfo.jhs.splice(index, 0, jhObj);
            } else {
                kfmbInfo.jhs[index].desc = jhObj.desc;
                kfmbInfo.jhs[index].btime_type = jhObj.btime_type;
                kfmbInfo.jhs[index].start_time = jhObj.start_time;
                kfmbInfo.jhs[index].start_unit = jhObj.start_unit;
                kfmbInfo.jhs[index].end_time = jhObj.end_time;
                kfmbInfo.jhs[index].end_unit = jhObj.end_unit;
            }
            console.log("kfmbInfo:" + JSON.stringify(kfmbInfo));
            loadHtml();
            $("#editJHModal").modal('hide');
        }


        //    加载页面
        function loadHtml() {
            //清理页面
            $("#message-content").empty();
            //康复模板的描述
            kfmbInfo.desc_str = Text2Html(kfmbInfo.desc);
            //整理数据
            for (var i = 0; i < kfmbInfo.jhs.length; i++) {
                kfmbInfo.jhs[i].desc_str = Text2Html(kfmbInfo.jhs[i].desc);
                kfmbInfo.jhs[i].btime_type_str = getBtimeTypeStr(kfmbInfo.jhs[i].btime_type);
                kfmbInfo.jhs[i].start_unit_str = getTimeUnitStr(kfmbInfo.jhs[i].start_unit);
                kfmbInfo.jhs[i].end_unit_str = getTimeUnitStr(kfmbInfo.jhs[i].end_unit);
            }
            //加载页面
            console.log("kfmbInfo:" + JSON.stringify(kfmbInfo));
            var interText = doT.template($("#message-content-template").text());
            $("#message-content").html(interText(kfmbInfo));

        }


        //删除步骤
        function delJH(index) {
            kfmbInfo.jhs.splice(index, 1);
            loadHtml();
        }


        //上移计划信息
        function moveUpJH(index) {
            if (index == 0) {
                return;
            }
            var tempObj = kfmbInfo.jhs[index];
            kfmbInfo.jhs[index] = kfmbInfo.jhs[index - 1];
            kfmbInfo.jhs[index - 1] = tempObj;
            loadHtml();
        }

        //下移计划信息
        function moveDownJH(index) {
            if (index == kfmbInfo.jhs.length - 1) {
                return;
            }
            var tempObj = kfmbInfo.jhs[index];
            kfmbInfo.jhs[index] = kfmbInfo.jhs[index + 1];
            kfmbInfo.jhs[index + 1] = tempObj;
            loadHtml();
        }

        //编辑数据
        function editSJ(jh_index, edit_or_add) {

            console.log("editSJ index:" + jh_index + " edit_or_add:" + edit_or_add);
            //新建数据项
            var jhsjObj = {
                sjx_id: 0,
                min_value: 0,
                max_value: 0,
                jh_index: jh_index
            };
            //如果是新建
            if (edit_or_add == "add") {

            } else {        //如果是编辑

            }
//        console.log("jhObj:" + JSON.stringify(jhObj));
            var interText = doT.template($("#editSJModal-content-template").text());
            $("#editSJModal").html(interText(jhsjObj));
            $("#editSJModal").modal('show');
        }


        //点击编辑
        function clickEditSJ(jh_index, edit_or_add) {
            console.log("kfmbInfo:" + JSON.stringify(kfmbInfo) + " jh_index:" + jh_index);
            var jhsjObj = {};
            jhsjObj.sjx_id = $("#sjx_id").val();
            jhsjObj.min_value = $("#min_value").val();
            jhsjObj.max_value = $("#max_value").val();
            jhsjObj.sjx = {
                name: $("#sjx_" + jhsjObj.sjx_id).attr("data-name"),
                unit: $("#sjx_" + jhsjObj.sjx_id).attr("data-unit")
            }
            kfmbInfo.jhs[jh_index].jhsjs.push(jhsjObj);
            console.log("kfmbInfo:" + JSON.stringify(kfmbInfo));
            loadHtml();
            $("#editSJModal").modal('hide');
        }

        //删除数据项
        function delSJ(jh_index, sj_index) {
            kfmbInfo.jhs[jh_index].jhsjs.splice(sj_index, 1);
            loadHtml();
        }

        //点击保存
        function clickAdd() {
            console.log("clickAdd kfmbInfo:" + JSON.stringify(kfmbInfo));
            //没有康复计划
            if (kfmbInfo.jhs.length <= 0) {
                $("#tipModalBody").html('<p>康复模板id为空，请联系管理员处理</p>');
                $("#tipModal").modal('show');
                return;
            }
            delete kfmbInfo.desc_str;
            //进行计划排序
            for (var i = 0; i < kfmbInfo.jhs.length; i++) {
                kfmbInfo.jhs[i].seq = i;
            }
            var token = $("#token").children().val();
            kfmbInfo._token = token;
            //调用接口进行编辑
            editKFMBweb("{{URL::asset('')}}", JSON.stringify(kfmbInfo), function (ret, err) {
                //提示保存成功
                if (ret.result == true) {
                    $("#tipModalBody").html('<p>康复模板计划保存成功</p>');
                    $("#tipModal").modal('show');
                    kfmbInfo = ret.ret;
                    loadHtml();
                } else {
                    $("#tipModalBody").html("<p>康复计划信息保存失败，请联系<span class='text-info'>管理员处理</span></p>");
                    $("#tipModal").modal('show');
                }
            })
        }


    </script>
@endsection