@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>新建/编辑量表</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">量表管理</li>
            <li class="active">新建/编辑</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form action="" method="post" class="form-horizontal" onsubmit="return checkValid();">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">id*</label>

                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           placeholder="自动生成id" disabled
                                           value="{{ isset($data->id) ? $data->id : '' }}">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="doctor_id" class="col-sm-2 control-label">doctor_id*</label>
                                <div class="col-sm-10">
                                    <input id="doctor_id" name="doctor_id" type="text" class="form-control"
                                           placeholder="录入医生id"
                                           value="{{ isset($data->doctor_id) ? $data->doctor_id : $admin->id}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">标题*</label>
                                <div class="col-sm-10">
                                    <input id="title" name="title" type="text" class="form-control"
                                           placeholder="请输入标题" value="{{ isset($data->name) ? $data->name : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="intro" class="col-sm-2 control-label">简介*</label>

                                <div class="col-sm-10">
                                    <textarea id="desc" name="desc" class="form-control" rows="3"
                                              placeholder="请输入 ...">{{ isset($data->desc) ? $data->desc : '' }}</textarea>
                                </div>
                            </div>


                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info btn-block btn-flat">保存</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (right) -->
            <div class="col-md-6">
                @if (isset($data->id))
                    {{--作品预览信息--}}
                    <div class="white-bg" style="padding: 30px;">
                        <div class="font-size-16">
                            {{$data->title}}
                        </div>
                        <div class="border-t margin-top-10 margin-bottom-10">
                        </div>
                        <div class="font-size-14 grey-font">
                            <span>{{$data->created_at_str}}</span>
                            <span class="margin-left-10 text-info">{{$data->author}}</span>
                            <span class="margin-left-10">阅读 {{$data->show_num}}</span>
                        </div>
                        <div class="grey-bg margin-top-10" style="padding: 10px;">
                            {{$data->desc}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ URL::asset('js/qiniu.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/plupload.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/moxie.js') }}"></script>
    <script type="application/javascript">

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        })

        //合规校验
        function checkValid() {
            var title = $("#name").val();
            //合规校验
            if (judgeIsNullStr(title)) {
                $("#title").focus();
                return false;
            }

            return true;
        }

        $(document).ready(function () {
            //获取七牛token
            //initQNUploader();
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
                //uptoken: "{/{$upload_token}}", // uptoken是上传凭证，由其他程序生成
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