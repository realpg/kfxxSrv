@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">宣教管理</li>
                    <li class="active">新建/编辑</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickSave();">
                    保存宣教信息
                </button>
            </div>
        </div>
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
        @{{for(var i=0;i<it.steps.length ;i++){}}
        <div>
            @{{?it.steps[i].text}}
            <div class="padding-bottom-10">@{{=it.steps[i].text_html}}</div>
            @{{?}}
            @{{? it.steps[i].img}}
            <div class="text-center">
                <img src="@{{=it.steps[i].img}}"
                     style="width: 60%;margin: auto;" class="padding-top-10 padding-bottom-10">
            </div>
            @{{??}}

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
                 onclick="editStep(@{{= i+1}},'add');">
        </div>
        @{{}}}

    </script>


    <!--新建编辑宣教对话框-->
    <div class="modal fade -m" id="editXJModal" tabindex="-1" role="dialog">


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
                                    <img id="pickfiles" src="@{{=it.img}}" style="width: 260px;">
                                    @{{??}}
                                    <img id="pickfiles" src="{{URL::asset('/img/upload.png')}}" style="width: 260px;">
                                    @{{?}}
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传500*260尺寸图片
                                </div>
                            </div>

                            <div class="form-group margin-top-10">
                                <label for="type" class="col-sm-2 control-label">位置</label>
                                <div class="col-sm-10">
                                    <div class="margin-top-10 row">
                                        <div class="row">
                                            @foreach($hposs as $hpos)
                                                <div class="col-xs-4">
                                                    <input type="checkbox" name="hpos_id" id="hpos_id{{$hpos->id}}"
                                                           value="{{$hpos->id}}"
                                                           class="minimal">
                                                    <span
                                                            class="margin-left-10">{{$hpos->name}}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
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
    <div class="modal fade -m" id="editStepModal" tabindex="-1" role="dialog">


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
                                    <textarea id="stepText" class="form-control" rows="10"
                                              placeholder="请输入 ...">@{{=it.text}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="img" class="col-sm-2 control-label">图片</label>
                                <div class="col-sm-10">
                                    <input id="stepImg" name="img" type="text" class="form-control"
                                           placeholder="图片网络链接地址"
                                           value="@{{=it.img}}">
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="text-center">
                                <div id="stepContainer">
                                    @{{? it.img}}
                                    <img id="stepPickfiles" src="@{{=it.img}}" style="width: 260px;">
                                    @{{??}}
                                    <img id="stepPickfiles" src="{{URL::asset('/img/upload.png')}}"
                                         style="width: 260px;">
                                    @{{?}}
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传260*260尺寸图片</div>
                            </div>

                            <div class="form-group">
                                <label for="video" class="col-sm-2 control-label">视频</label>
                                <div class="col-sm-10">
                                    <input id="stepVideo_input" name="video" type="text" class="form-control"
                                           placeholder="视频网路链接地址"
                                           value="@{{=it.video}}">
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="text-center">
                                <div id="stepVideoContainer" >
                                    @{{? it.video}}
                                    <button  id="stepVideoPickfiles" style="border: solid 1px #2b542c">更换视频</button>
                                    <video id="stepVideo"class="pull-right"src="@{{=it.video}}" controls="controls" width="462px" height="260px"style="border: solid 1px #d2d6de">
                                        您的浏览器不支持视频
                                    </video>
                                    @{{??}}
                                    <button id="stepVideoPickfiles" style="border: solid 1px #2b542c;">选择视频</button>
                                    <video id="stepVideo"class="pull-right"src="@{{=it.video}}" controls="controls" width="462px" height="260px"style="border: solid 1px #d2d6de">
                                        您的浏览器不支持视频
                                    </video>
                                    @{{?}}
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*上传的视频大小<100M,且仅支持Ogg、MPEG4、WebM格式</div>
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
            "title": "在这里输入宣教标题",
            "desc": "输入简要描述,描述将在宣教列表上显示",
            "author": "北京大学第三医院",
            "created_at": getCurrentTime(),
            "doctor_id":{{$admin->id}},
            "img": "",
            "video": "",
            "hpos_ids": "",
            "show_num": 0,
            "steps": []
        };


        //入口函数
        $(document).ready(function () {
            $(".sidebar-menu li:nth-child(9)").addClass("active");
            //tooltip
            $('[data-toggle="tooltip"]').tooltip()
            //从url中获取id
            var xj_id = getQueryString("id");
            if (judgeIsNullStr(xj_id)) {
                //加载页面，空页面
                xjInfo = empty_xjInfo;
                loadHtml();
            } else {
                //加载计划页面
                var param = {
                    id: xj_id
                }
                getXJInfoById("{{URL::asset('')}}", param, function (ret, err) {
                    //提示保存成功
                    if (ret.result == true) {
                        xjInfo = ret.ret;
                        loadHtml();
                    }
                })
            }
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
            //专门设置位置
            var hpos_arr = [];
            if (!judgeIsNullStr(xjInfo.hpos_ids)) {
                var hpos_arr = xjInfo.hpos_ids.split(',');
            }
            for (var i = 0; i < hpos_arr.length; i++) {
                $("#hpos_id" + hpos_arr[i]).attr('checked', 'true');
            }
            //初始化七牛
            initQNUploader('container', 'img', 'pickfiles');
            $("#editXJModal").modal('show');
            setICheck();
        }

        //点击编辑宣教
        function clickEditXJ() {
            //对宣教对象基础信息进行赋值
            xjInfo.title = $("#title").val();
            xjInfo.author = $("#author").val();
            xjInfo.desc = $("#desc").val();
            xjInfo.img = $("#img").val();

            xjInfo.hpos_ids = "";
            var hpos_arr = [];
            $('input:checkbox').each(function () {
                if ($(this).is(':checked') == true) {
                    hpos_arr.push($(this).attr('value'));
                }
            });
            xjInfo.hpos_ids = hpos_arr.toString();
            //合规校验
            if (judgeIsNullStr(xjInfo.title)) {
                $("#title").focus();
                return;
            }
            if (judgeIsNullStr(xjInfo.author)) {
                $("#author").focus();
                return;
            }
            if (judgeIsNullStr(xjInfo.desc)) {
                $("#desc").focus();
                return;
            }
            if (judgeIsNullStr(xjInfo.img)) {
                $("#img").focus();
                return;
            }
            //加载页面
            loadHtml();
            console.log("xjInfo:" + JSON.stringify(xjInfo));
            $("#editXJModal").modal('hide');
        }

        //点击编辑步骤信息
        function editStep(index, edit_or_add) {
            console.log("editStep index:" + index + " edit_or_add:" + edit_or_add);
            var steps = xjInfo.steps;
            var stepObj = {
                "text": "",
                "img": "",
                "video": "",
                "index": index,
                "opt": edit_or_add
            };
            //如果是新建
            if (edit_or_add == "add") {

            } else {        //如果是编辑
                stepObj.text = nullToEmptyStr(xjInfo.steps[index].text);
                stepObj.img = nullToEmptyStr(xjInfo.steps[index].img);
                stepObj.video = nullToEmptyStr(xjInfo.steps[index].video);
            }
            console.log("stepObj:" + JSON.stringify(stepObj));
            var interText = doT.template($("#editStepModal-content-template").text());
            $("#editStepModal").html(interText(stepObj));
            //初始化七牛
            initQNUploader('stepContainer', 'stepImg', 'stepPickfiles');
            initQNUploader_video('stepVideoContainer', 'stepVideo_input','stepVideo' ,'stepVideoPickfiles');
            $("#editStepModal").modal('show');
        }

        //点击添加步骤
        function clickEditStep(index, edit_or_add) {
            var stepObj = {};
            stepObj.text = $("#stepText").val();
            stepObj.img = $("#stepImg").val();
            stepObj.video = $("#stepVideo_input").val();
            //合规校验
            if (judgeIsNullStr(stepObj.text) && judgeIsNullStr(stepObj.img)&&judgeIsNullStr(stepObj.video)) {
                $("#stepText").focus();
                return;
            }
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
            //如果没有步骤信息，说明还没有录入，需要进行录入
            if (xjInfo.steps.length <= 0) {
                $("#tipModalBody").html('<p>请录入宣教图文信息</p>');
                $("#tipModal").modal('show');
                return;
            }
            //进行排序
            for (var i = 0; i < xjInfo.steps.length; i++) {
                xjInfo.steps[i].seq = i;
            }
            xjInfo._token = "{{ csrf_token() }}";
            console.log("cilckSave xjInfo:" + JSON.stringify(xjInfo));
            //调用接口进行编辑
            editXJ("{{URL::asset('')}}", JSON.stringify(xjInfo), function (ret, err) {
                //提示保存成功
                if (ret.result == true) {
                    $("#tipModalBody").html('<p>宣教信息保存成功</p>');
                    $("#tipModal").modal('show');
                    xjInfo = ret.ret;
                    loadHtml();
                } else {
                    $("#tipModalBody").html("<p>宣教信息保存失败，请联系<span class='text-info'>管理员处理</span></p>");
                    $("#tipModal").modal('show');
                }
            })
        }


        //初始化七牛上传模块
        function initQNUploader(container_dom, input_dom, img_dom) {
            console.log("initQNUploader container_dom:" + container_dom + " input_dom:" + input_dom + " img_dom:" + img_dom);
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: img_dom,         // 上传选择的点选按钮，必需
                container: container_dom,//上传按钮的上级元素ID
                // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
                // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
                // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
                uptoken: "{{$upload_token}}", // uptoken是上传凭证，由其他程序生成
                // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
                // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
                //    // do something
                //    return uptoken;
                // },
                get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                // downtoken_url: '/downtoken',
                // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
                unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
                // save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
                domain: 'http://twst.isart.me/',     // bucket域名，下载资源时用到，必需
                max_file_size: '100mb',             // 最大文件体积限制
                flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                dragdrop: true,                     // 开启可拖曳上传
                drop_element: container_dom,          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                //x_vars : {
                //    查看自定义变量
                //    'time' : function(up,file) {
                //        var time = (new Date()).getTime();
                // do something with 'time'
                //        return time;
                //    },
                //    'size' : function(up,file) {
                //        var size = file.size;
                // do something with 'size'
                //        return size;
                //    }
                //},
                init: {
                    'FilesAdded': function (up, files) {
                        plupload.each(files, function (file) {
                            // 文件添加进队列后，处理相关的事情
//                                            alert(alert(JSON.stringify(file)));
                        });
                    },
                    'BeforeUpload': function (up, file) {
                        // 每个文件上传前，处理相关的事情
//                        console.log("BeforeUpload up:" + up + " file:" + JSON.stringify(file));
                    },
                    'UploadProgress': function (up, file) {
                        // 每个文件上传时，处理相关的事情
//                        console.log("UploadProgress up:" + up + " file:" + JSON.stringify(file));
                    },
                    'FileUploaded': function (up, file, info) {
                        // 每个文件上传成功后，处理相关的事情
                        // 其中info是文件上传成功后，服务端返回的json，形式如：
                        // {
                        //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                        //    "key": "gogopher.jpg"
                        //  }
                        console.log(JSON.stringify(info));
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        //获取上传成功后的文件的Url
                        var sourceLink = domain + res.key;
                        console.log(" input_dom:" + input_dom + " img_dom:" + img_dom + " sourceLink:" + sourceLink);
                        $("#" + input_dom).val(sourceLink);
                        $("#" + img_dom).attr('src', qiniuUrlTool(sourceLink, "ad"));
                    },
                    'Error': function (up, err, errTip) {
                        //上传出错时，处理相关的事情
                        console.log(err + errTip);
                    },
                    'UploadComplete': function () {
                        //队列文件处理完毕后，处理相关的事情
                    },
                    'Key': function (up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在unique_names: false，save_key: false时才生效

                        var key = "";
                        // do something with key here
                        return key
                    }
                }
            });
        }

        //初始化七牛上传模块
        function initQNUploader_video(container_dom, input_dom, video_dom,select_dom) {
            console.log("initQNUploader container_dom:" + container_dom + " input_dom:" + input_dom + " video_dom:" + video_dom+"  select_dom:"+select_dom);
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: select_dom,         // 上传选择的点选按钮，必需
                container: container_dom,//上传按钮的上级元素ID
                // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
                // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
                // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
                uptoken: "{{$upload_token}}", // uptoken是上传凭证，由其他程序生成
                // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
                // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
                //    // do something
                //    return uptoken;
                // },
                get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                // downtoken_url: '/downtoken',
                // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
                unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
                // save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
                domain: 'http://twst.isart.me/',     // bucket域名，下载资源时用到，必需
                max_file_size: '100mb',             // 最大文件体积限制
                flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                dragdrop: true,                     // 开启可拖曳上传
                drop_element: container_dom,          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                //x_vars : {
                //    查看自定义变量
                //    'time' : function(up,file) {
                //        var time = (new Date()).getTime();
                // do something with 'time'
                //        return time;
                //    },
                //    'size' : function(up,file) {
                //        var size = file.size;
                // do something with 'size'
                //        return size;
                //    }
                //},
                init: {
                    'FilesAdded': function (up, files) {
                        plupload.each(files, function (file) {
                            // 文件添加进队列后，处理相关的事情
//                                            alert(alert(JSON.stringify(file)));
                        });
                    },
                    'BeforeUpload': function (up, file) {
                        // 每个文件上传前，处理相关的事情
//                        console.log("BeforeUpload up:" + up + " file:" + JSON.stringify(file));
                    },
                    'UploadProgress': function (up, file) {
                        // 每个文件上传时，处理相关的事情
//                        console.log("UploadProgress up:" + up + " file:" + JSON.stringify(file));
                    },
                    'FileUploaded': function (up, file, info) {
                        // 每个文件上传成功后，处理相关的事情
                        // 其中info是文件上传成功后，服务端返回的json，形式如：
                        // {
                        //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                        //    "key": "gogopher.jpg"
                        //  }
                        console.log(JSON.stringify(info));
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        //获取上传成功后的文件的Url
                        var sourceLink = domain + res.key;
                        console.log(" input_dom:" + input_dom + " video_dom:" + video_dom + " sourceLink:" + sourceLink);
                        $("#" + input_dom).val(sourceLink);
                        $("#" + video_dom).attr('src', qiniuUrlTool(sourceLink, "ad"));
                        alert("上传成功");
                    },
                    'Error': function (up, err, errTip) {
                        //上传出错时，处理相关的事情
                        console.log(err + errTip);
                    },
                    'UploadComplete': function () {
                        //队列文件处理完毕后，处理相关的事情
                    },
                    'Key': function (up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在unique_names: false，save_key: false时才生效

                        var key = "";
                        // do something with key here
                        return key
                    }
                }
            });
        }


    </script>
@endsection