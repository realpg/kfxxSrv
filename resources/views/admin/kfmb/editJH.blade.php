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
                    <!-- form start -->
                    <form action="" method="post" class="form-horizontal" onsubmit="return checkValid();">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="f_id" class="col-sm-2 control-label">父id</label>

                                <div class="col-sm-10">
                                    <input id="f_id" name="f_id" type="text" class="form-control" placeholder="父id"
                                           value="{{ $data->id }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group hidden">
                            <label for="id" class="col-sm-2 control-label">id</label>
                            <div class="col-sm-10">
                                <input id="id" name="id" type="text" class="form-control" placeholder="id"
                                       value="{{ isset($tw->id) ? $tw->id : '' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text" class="col-sm-2 control-label">文字</label>
                            <div class="col-sm-10">
                                    <textarea id="text" name="text" class="form-control" rows="3"
                                              placeholder="请输入 ...">{{ isset($tw->text) ? $tw->text : '' }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="img" class="col-sm-2 control-label">图片</label>

                            <div class="col-sm-10">
                                <input id="img" name="img" type="text" class="form-control" placeholder="图片网路链接地址"
                                       value="{{ isset($tw->img) ? $tw->img : '' }}">
                            </div>
                        </div>
                        <div style="margin-top: 10px;" class="text-center">
                            <div id="container">
                                <img id="pickfiles"
                                     src="{{ isset($tw->img) ? $tw->img : URL::asset('/img/upload.png') }}"
                                     style="width: 240px;">
                            </div>
                            <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传500*260尺寸图片</div>
                        </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info btn-block btn-flat">添加康复计划</button>
                </div>
                <!-- /.box-footer -->
                </form>
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
                    <div class="font-size-14 grey-font">
                        <span>{{$data->created_at_str}}</span>
                        <span class="margin-left-10 text-info">{{$data->doctor->name}}</span>
                    </div>
                    <div class="grey-bg margin-top-10" style="padding: 10px;">
                        {{$data->desc}}
                    </div>
                    <div style="padding: 10px;">
                        <!--作品信息-->
                        @foreach($data->steps as $step)
                            <div>
                                <div class="white-bg margin-b-10" style="background-color: white;">
                                    <div class="padding-10">
                                        @if($step->img)
                                            <img src="{{$step->img}}"
                                                 style="width: 100%;" class="padding-top-10 padding-bottom-10">
                                        @endif
                                    </div>
                                    <div class="padding-bottom-10">
                                        @if($step->text)
                                            {{$step->text}}
                                        @endif
                                    </div>
                                    <div class="padding-bottom-10">
                                        <span class="time"><i
                                                    class="fa fa-clock-o"></i> {{$step->created_at_str}}</span>

                                        <span onclick="clickDel({{$step->id}})"
                                              class="btn btn-danger btn-xs pull-right"
                                              style="margin-left: 10px;">删除</span>

                                        <a href="{{URL::asset('/admin/kfmb/setStep/')}}/{{$data->id}}?tw_id={{$step->id}}"
                                           class="btn btn-warning btn-xs pull-right">编辑</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{--删除对话框--}}
        <div class="modal fade modal-margin-top" id="delConfrimModel" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content message_align">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <h4 class="modal-title">提示信息</h4>
                    </div>
                    <div class="modal-body">
                        <p>您确认要删除该条康复计划吗？</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button id="delConfrimModel_confirm_btn" data-value="" onclick="delTW();"
                                class="btn btn-success"
                                data-dismiss="modal">确定
                        </button>
                    </div>
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
            var text = $("#text").val();
            var img = $("#img").val();
            //合规校验
            if (judgeIsNullStr(text) && judgeIsNullStr(img)) {
                if (judgeIsNullStr(text)) {
                    $("#text").focus();
                }
                if (judgeIsNullStr(img)) {
                    $("#img").focus();
                }
                return false;
            }
            return true;
        }


        //点击删除
        function clickDel(tw_id) {
            console.log("clickDel tw_id:" + tw_id);
            //为删除按钮赋值
            $("#delConfrimModel_confirm_btn").attr("data-value", tw_id);
            $("#delConfrimModel").modal('show');
        }

        //删除轮播
        function delTW() {
            var tw_id = $("#delConfrimModel_confirm_btn").attr("data-value");
            console.log("delTW tw_id:" + tw_id);
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/kfmb/delStep/')}}/" + tw_id;
        }


        $(document).ready(function () {
            //获取七牛token
            initQNUploader();
        });

        //初始化七牛上传模块
        function initQNUploader() {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: 'pickfiles',         // 上传选择的点选按钮，必需
                container: 'container',//上传按钮的上级元素ID
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
                drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
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
//                        console.log(JSON.stringify(info));
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        //获取上传成功后的文件的Url
                        var sourceLink = domain + res.key;
                        $("#img").val(sourceLink);
                        $("#pickfiles").attr('src', qiniuUrlTool(sourceLink, "ad"));
//                        console.log($("#pickfiles").attr('src'));
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