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
                <input id="id" name="id" value="@{{=it.id }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="col-sm-2 control-label text-right">姓名</label>
            <div class="col-sm-9">
                <input id="name" name="name" value="@{{=it.name }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="Img" class="col-sm-2 control-label text-right">头像</label>
            <div class="col-sm-9">
                <input id="avatar" name="avatar" type="text" class="form-control"
                       placeholder="图片网路链接地址"
                       value="@{{=it.avatar}}">
            </div>
        </div>
        <div style="margin-top: 10px;" class="text-center">
            <div id="Container">
                @{{? it.avatar}}
                <img id="Pickfiles" src="@{{=it.avatar}}" style="width: 120px;height: 120px">
                @{{??}}
                <img id="Pickfiles" src="{{URL::asset('/img/upload.png')}}"
                     style="width: 120px;height: 120px">
                @{{?}}
            </div>
            <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传260*260尺寸图片</div>
        </div>

        <div class="form-group">
            <label for="phonenum" class="col-sm-2 control-label text-right">电话</label>
            <div class="col-sm-9">
                <input id="phonenum" name="phonenum" value="@{{=it.phonenum }}" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="gender" class="col-sm-2 control-label">性别</label>
            <div class="col-sm-9">
                <select id="gender" name="gender" class="form-control">
                    <option value="1" @{{?it.gender=="1" }}selected="true" @{{? }}>男</option>
                    <option value="2" @{{?it.gender=="2" }}selected="true" @{{? }}>女</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="gender" class="col-sm-2 control-label"></label>
            <div class="col-sm-9">
                <button type="button" class="btn btn-info btn-block btn-flat" onclick="submitForm();">
                    保存
                </button>
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

        var doctor_info;

        //入口函数
        $(document).ready(function () {
            //tooltip
            $('[data-toggle="tooltip"]').tooltip()
            //从url中获取id
            var id = '{{$admin->id}}';
            var param = {'id': id}
            getDoctorById("{{URL::asset('')}}", param, function (ret, err) {
                if (ret.result) {
                    doctor_info = ret.ret;
                    console.log(ret);
                    loadHtml();
                    initQNUploader('Container', 'avatar', 'Pickfiles');
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
                console.log("name:" + name);
                $("#name").focus();
                return false;
            }
            var phonenum = $("#phonenum").val();
            if (judgeIsNullStr(phonenum) || !isPoneAvailable(phonenum)) {
                console.log("phonenum:" + phonenum);
                $("#phonenum").focus();
                return false;
            }
            var avatar = $("#avatar").val();
            if (judgeIsNullStr(avatar)) {
                console.log("avatar:" + avatar);
                $("#avatar").focus();
                return false;
            }
            return true;
        }

        //提交优化结果
        function submitForm() {
            console.log("click Submit Form");
            if (checkValid()) {
                console.log("submit");
                document.getElementById("editDoctor").submit();
            }

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


    </script>
@endsection