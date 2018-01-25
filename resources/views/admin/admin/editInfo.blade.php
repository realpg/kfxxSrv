@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">编辑资料</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="submitForm();">
                    保存
                </button>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-1"></div>
            <!-- middle column -->
            <div class="col-md-10">
                <form id="editDoctor" action="{{URL::asset('/admin/edit')}}" method="post"
                      class="form-horizontal"
                      onsubmit="return checkValid();">
                <div id="message-content" class="white-bg" style="padding: 20px;">
                </div>
                </form>
            </div>
            <!--/.col (right) -->
            <div class="col-md-1"></div>
        </div>
    </section>


    {{--页面加载模板--}}
    <script id="message-content-template" type="text/x-dot-template">
        {{csrf_field()}}
        <div class="hidden">
            <label for="img" class="col-sm-2 control-label text-right">id</label>
            <div class="col-sm-9">
                <input id="id" name="id" value="@{{=it.id }}"style="width: 100%">
            </div>
        </div>
        <div class="hidden">
            <label for="img" class="col-sm-2 control-label text-right">id</label>
            <div class="col-sm-9">
                <input id="token" name="token" value="@{{=it.token }}"style="width: 100%">
            </div>
        </div>

        <div class="row margin-top-10">
            <label for="img" class="col-sm-2 control-label text-right">姓名</label>
            <div class="col-sm-9">
                <input id="name" name="name" value="@{{=it.name }}"style="width: 100%">
            </div>
        </div>

        <div class="row margin-top-10">
            <label for="img" class="col-sm-2 control-label text-right">头像</label>
            <div class="col-sm-9">
                <input id="stepImg" name="img" type="text" class="form-control"
                       placeholder="图片网路链接地址"style="width: 100%"
                       value="@{{=it.avatar}}">
            </div>
        </div>
        <div style="margin-top: 10px;" class="text-center">
            <div id="stepContainer">
                @{{? it.avatar}}
                <img id="stepPickfiles" src="@{{=it.avatar}}" style="width: 260px;height: 260px">
                @{{??}}
                <img id="stepPickfiles" src="{{URL::asset('/img/upload.png')}}"
                     style="width: 260px;">

                @{{?}}
            </div>
            <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传260*260尺寸图片</div>
        </div>

        <div class="row margin-top-10">
            <label for="img" class="col-sm-2 control-label text-right">电话</label>
            <div class="col-sm-9">
                <input id="phonenum" name="phonenum" value="@{{=it.phonenum }}"style="width: 100%">
            </div>
        </div>
        <div class="row margin-top-10">
            <label for="img" class="col-sm-2 control-label text-right">性别</label>
            <div class="col-sm-9">
                <select id="gender" name="gender" style="width: 100%">
                    <option value="1" @{{?it.gender=="1" }}selected="true" @{{? }}>男</option>
                    <option value="2" @{{?it.gender=="2" }}selected="true" @{{? }}>女</option>
                </select>
            </div>
        </div>

    </script>



    {{--提示Modal--}}
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
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('script')
    <script type="application/javascript">


        //优化icheck展示
        function setICheck() {
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            })
        }

        //初始化宣教值
        var xjInfo = {};

        //如果没有宣教值，则设置为空值
        var empty_xjInfo = {
            "id": null,
            "title": "在这里输入宣教标题...",
            "desc": "输入简要描述...",
            "author": "北京大学第三医院",
            "created_at": getCurrentTime(),
            "doctor_id":{{$admin->id}},
            "img": "",
            "hpos_ids": "",
            "show_num": 0,
            "steps": []
        }
        var doctor_info;


        //入口函数
        $(document).ready(function () {
            //tooltip
            $('[data-toggle="tooltip"]').tooltip()
            //从url中获取id
            var id = {{$admin->id}};
            var param = {'id': id}
            getDoctorById("{{URL::asset('')}}", param, function (ret, err) {
                if (ret.result) {
                    doctor_info = ret.ret;
                    console.log(ret);
                    loadHtml();
                }
            })

        });


        //加载页面
        function loadHtml() {
            //清理页面
            $("#message-content").empty();
            //整理数据
            //加载页面
            var interText = doT.template($("#message-content-template").text());
            $("#message-content").html(interText(doctor_info));
        }
        function checkValid() {
            //合规校验
            var name = $("#name").val();
            if (judgeIsNullStr(name)) {
                $("#name").focus();
                return false;
            }
            var phonenum = $("#phonenum").val();
            if (judgeIsNullStr(phonenum) || !isPoneAvailable(phonenum)) {
                $("#phonenum").focus();
                return false;
            }
            var avatar = $("#avatar").val();
            if (judgeIsNullStr(avatar)) {
                $("#avatar").focus();
                return false;
            }
            return true;
        }
        function submitForm() {
            document.getElementById("editDoctor").submit();
        }


    </script>
@endsection