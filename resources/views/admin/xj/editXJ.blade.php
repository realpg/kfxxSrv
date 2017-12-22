@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>新建/编辑宣教</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">宣教管理</li>
            <li class="active">新建/编辑</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-3"></div>
            <!-- middle column -->
            <div class="col-md-6">
                <div id="message-content" class="white-bg" style="padding: 20px;">


                </div>

                <div class="margin-top-25 text-center">
                    <button class="btn btn-info btn-block btn-flat" onclick="clickSave();">保存宣教信息</button>
                </div>
            </div>
            <!--/.col (right) -->
            <div class="col-md-3"></div>
        </div>
    </section>


    {{--页面加载模板--}}
    <script id="message-content-template" type="text/x-dot-template">
        <div>
            <div class="font-size-16">
                @{{=it.title}}
            </div>
            <div class="border-t margin-top-10 margin-bottom-10">
            </div>
            <div class="font-size-14 grey-font">
                <span>@{{=it.created_at_str}}</span>
                <span class="margin-left-10 text-info">@{{=it.author}}</span>
                <span class="margin-left-10">阅读 @{{=it.show_num}}</span>
            </div>
            <div class="grey-bg margin-top-10" style="padding: 10px;">
                @{{=it.desc_html}}
            </div>
            <div class="text-right margin-top-15">
                <img src="{{URL::asset('/img/edit_icon.png')}}" class="opt-btn-size" onclick="editXJInfo();">
            </div>

            <div style="border: 1px #F1F1F1 dashed;" class="margin-top-20 margin-bottom-20"></div>
        </div>
        <div class="margin-top-15 margin-bottom-15 text-center">
            <img src="{{URL::asset('/img/add_button_icon.png')}}" style="width: 36px;height: 36px;"
                 onclick="editStep(0,'add');">
        </div>
        <!--步骤信息-->
        @{{for(var i=0;i
        <it.steps.length ;i++){}}
        <div>
            @{{?it.steps[i].text}}
            <div class="padding-bottom-10">@{{=it.steps[i].text_html}}</div>
            @{{?}}
            @{{?it.steps[i].img}}
            <div class="padding-10">
                <img src="@{{=it.steps[i].img}}"
                     style="width: 100%;" class="padding-top-10 padding-bottom-10">
            </div>
            @{{?}}
            <div class="text-right margin-top-15">
                <img src="{{URL::asset('/img/up_pointer_icon.png')}}"
                     class="opt-btn-size margin-right-10"
                     onclick="moveUpStep(@{{=i}});">
                <img src="{{URL::asset('/img/down_pointer_icon.png')}}"
                     class="opt-btn-size margin-right-10"
                     onclick="moveDownStep(@{{=i}});">
                <img src="{{URL::asset('/img/edit_icon.png')}}"
                     class="opt-btn-size margin-right-10"
                     onclick="editStep(@{{=i}},'edit');">
                <img src="{{URL::asset('/img/delete_icon.png')}}" class="opt-btn-size"
                     onclick="delStep(@{{=i}});">
            </div>

            <div style="border: 1px #F1F1F1 dashed;" class="margin-top-20 margin-bottom-20"></div>
        </div>
        <div class="margin-top-15 margin-bottom-15 text-center">
            <img src="{{URL::asset('/img/add_button_icon.png')}}"
                 style="width: 36px;height: 36px;"
                 onclick="editStep(@{{=i+1}},'add');">
        </div>
        @{{}}}

    </script>


    <!--新建编辑宣教对话框-->
    <div class="modal fade modal-margin-top-m" id="editXJModal" tabindex="-1" role="dialog">


    </div>
    <!-- /.modal -->
    <script id="editXJModal-content-template" type="text/x-dot-template">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理宣教信息</h4>
                </div>
                <form id="editXJ" action="" method="post" class="form-horizontal">
                    <div class="modal-body">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">标题*</label>

                                <div class="col-sm-10">
                                    <input id="title" name="title" type="text" class="form-control"
                                           placeholder="请输入标题"
                                           value="@{{=it.title}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-2 control-label">简介*</label>

                                <div class="col-sm-10">
                                    <textarea id="desc" name="desc" class="form-control" rows="3"
                                              placeholder="请输入 ...">@{{=it.desc}}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="author" class="col-sm-2 control-label">展示作者*</label>

                                <div class="col-sm-10">
                                    <input id="author" name="author" type="text" class="form-control"
                                           placeholder="请输入作者"
                                           value="@{{=it.author}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="img" class="col-sm-2 control-label">封面*</label>

                                <div class="col-sm-10">
                                    <input id="img" name="img" type="text" class="form-control"
                                           placeholder="图片网路链接地址"
                                           value="@{{=it.img}}">
                                </div>
                            </div>
                            <div class="text-center margin-top-10">
                                <div id="container">
                                    @{{? it.img}}
                                    <img id="pickfiles" src="@{{=it.img}}" style="width: 240px;">
                                    @{{??}}
                                    <img id="pickfiles" src="{{URL::asset('/img/upload.png')}}" style="width: 240px;">
                                    @{{?}}
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传500*260尺寸图片
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" data-value="" class="btn btn-success"
                                onclick="clickEditXJ();">确定
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </script>

    <!--新建编辑宣教步骤对话框-->
    <div class="modal fade modal-margin-top-m" id="editStepModal" tabindex="-1" role="dialog">


    </div>

    <script id="editStepModal-content-template" type="text/x-dot-template">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理图文信息</h4>
                </div>
                <form id="editStep" action="" method="post" class="form-horizontal">
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="stepText" class="col-sm-2 control-label">文字</label>

                                <div class="col-sm-10">
                                    <textarea id="stepText" class="form-control" rows="3"
                                              placeholder="请输入 ...">@{{=it.text}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="img" class="col-sm-2 control-label">图片</label>

                                <div class="col-sm-10">
                                    <input id="stepImg" name="img" type="text" class="form-control"
                                           placeholder="图片网路链接地址"
                                           value="@{{=it.img}}">
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="text-center">
                                <div id="stepContainer">
                                    @{{? it.img}}
                                    <img id="stepPickfiles" src="@{{=it.img}}" style="width: 240px;">
                                    @{{??}}
                                    <img id="stepPickfiles" src="{{URL::asset('/img/upload.png')}}"
                                         style="width: 240px;">
                                    @{{?}}
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传500*260尺寸图片</div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" data-value="" class="btn btn-success"
                                onclick="clickEditStep(@{{=it.index}},'@{{=it.opt}}');">确定
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </script>

@endsection

@section('script')
    <script src="{{ URL::asset('js/qiniu.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/plupload.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/moxie.js') }}"></script>
    <script type="application/javascript">


        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            })
        });


        //基础宣教信息
        var xjInfo = {
            "id": null,
            "title": "在这里输入宣教标题...",
            "desc": "输入简要描述...",
            "author": "北京大学第三医院",
            "created_at": getCurrentTime(),
            "img": "",
            "type": "",
            "show_num": 0,
            "steps": []
        }


        //入口函数
        $(document).ready(function () {
            //加载页面
            loadHtml();
        });


        //加载页面
        function loadHtml() {
            //清理页面
            $("#message-content").empty();
            //整理数据
            xjInfo.created_at_str = convertDateToChinese(xjInfo.created_at);
            xjInfo.desc_html = Text2Html(xjInfo.desc);
            for (var i = 0; i < xjInfo.steps.length; i++) {
                xjInfo.steps[i].text_html = Text2Html(xjInfo.steps[i].text);
            }
            //加载页面
            var interText = doT.template($("#message-content-template").text());
            $("#message-content").html(interText(xjInfo));
        }

        function editXJInfo() {
            console.log("editXJInfo");
            var interText = doT.template($("#editXJModal-content-template").text());
            $("#editXJModal").html(interText(xjInfo));
            $("#editXJModal").modal('show');
        }

        //点击编辑宣教
        function clickEditXJ() {
            //对宣教对象基础信息进行赋值
            xjInfo.title = $("#title").val();
            xjInfo.author = $("#author").val();
            xjInfo.desc = $("#desc").val();
            xjInfo.img = $("#img").val();
            loadHtml();
            $("#editXJModal").modal('hide');
        }

        //点击编辑步骤信息
        function editStep(index, edit_or_add) {
            console.log("editStep index:" + index + " edit_or_add:" + edit_or_add);
            var steps = xjInfo.steps;
            var stepObj = {
                "text": "",
                "img": "",
                "index": index,
                "opt": edit_or_add
            };
            //如果是新建
            if (edit_or_add == "add") {

            } else {        //如果是编辑
                stepObj.text = xjInfo.steps[index].text;
                stepObj.img = xjInfo.steps[index].img;
            }
            var interText = doT.template($("#editStepModal-content-template").text());
            $("#editStepModal").html(interText(stepObj));
            $("#editStepModal").modal('show');
        }

        //点击添加步骤
        function clickEditStep(index, edit_or_add) {
            var stepObj = {};
            stepObj.text = $("#stepText").val();
            stepObj.img = $("#stepImg").val();
            if (edit_or_add == "add") {
                xjInfo.steps.splice(index, 0, stepObj);
            } else {
                xjInfo.steps[index] = stepObj;
            }
            console.log("xjInfo:" + JSON.stringify(xjInfo));
            loadHtml();
            $("#editStepModal").modal('hide');
        }

        //删除步骤
        function delStep(index) {
            xjInfo.steps.splice(index, 1);
            loadHtml();
        }


        //上移宣教信息
        function moveUpStep(index) {
            if (index == 0) {
                return;
            }
            var tempObj = xjInfo.steps[index];
            xjInfo.steps[index] = xjInfo.steps[index - 1];
            xjInfo.steps[index - 1] = tempObj;
            loadHtml();
        }

        //下移宣教信息
        function moveDownStep(index) {
            if (index == xjInfo.steps.length - 1) {
                return;
            }
            var tempObj = xjInfo.steps[index];
            xjInfo.steps[index] = xjInfo.steps[index + 1];
            xjInfo.steps[index + 1] = tempObj;
            loadHtml();
        }


        //保存宣教信息
        function clickSave() {
            console.log("clickSave xjInfo:" + JSON.stringify(xjInfo));
            //调用接口进行编辑
            editXJ("{{URL::asset('')}}", xjInfo, function (ret, err) {

            })
        }


    </script>
@endsection