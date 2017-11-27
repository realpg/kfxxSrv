@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>新建/编辑资讯</small>
        </h1>
        <ol class="breadcrumb">

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
                            <div class="form-group">
                                <label for="id" class="col-sm-2 control-label">*id</label>

                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           placeholder="自动生成id" disabled
                                           value="{{ isset($data->id) ? $data->id : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">*标题</label>
                                <div class="col-sm-10">
                                    <input id="title" name="title" type="text" class="form-control"
                                           placeholder="请输入名称" value="{{ isset($data->title) ? $data->title : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-2 control-label">*描述</label>
                                <div class="col-sm-10">
                                    <input id="desc" name="desc" type="text" class="form-control"
                                           placeholder="请输入简要描述" value="{{ isset($data->desc) ? $data->desc : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-2 control-label">地址</label>
                                <div class="col-sm-10">
                                    <input id="desc" name="desc" type="text" class="form-control"
                                           placeholder="请输入办公区地址" value="{{ isset($data->addr) ? $data->addr : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="show_price" class="col-sm-2 control-label">展示价</label>
                                <div class="col-sm-10">
                                    <input id="show_price" name="show_price" type="text" class="form-control"
                                           placeholder="例如 3000元/年"
                                           value="{{ isset($data->show_price) ? $data->show_price : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price" class="col-sm-2 control-label">*实际价</label>

                                <div class="col-sm-10">
                                    <input id="price" name="price" type="number" class="form-control"
                                           value="{{ isset($data->price) ? $data->price : 0 }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price" class="col-sm-2 control-label"></label>

                                <div class="col-sm-10 text-danger">
                                    *请注意，价格需要使用（分）为单位，3000元请写为300000
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="count" class="col-sm-2 control-label">*数量</label>

                                <div class="col-sm-10">
                                    <input id="count" name="count" type="number" class="form-control"
                                           value="{{ isset($data->count) ? $data->count : 0 }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="seq" class="col-sm-2 control-label">序号</label>

                                <div class="col-sm-10">
                                    <input id="seq" name="seq" type="number" class="form-control"
                                           value="{{ isset($data->seq) ? $data->seq : 0 }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="img" class="col-sm-2 control-label">*封皮</label>

                                <div class="col-sm-10">
                                    <input id="img" name="img" type="text" class="form-control" placeholder="图片网路链接地址"
                                           value="{{ isset($data->img) ? $data->img : '' }}">
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="text-center">
                                <div id="container">
                                    <img id="pickfiles"
                                         src="{{ isset($data->img) ? $data->img.'?imageView2/2/w/500/h/260/interlace/1/q/75|imageslim' : URL::asset('/img/upload.png')}}"
                                         style="width: 240px;">
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传500*260尺寸图片</div>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="flag" class="col-sm-2 control-label">推荐</label>

                                <div class="col-sm-10">
                                    <input id="flag" name="flag" type="checkbox" {{$data->flag == '1'? 'checked':''}}>
                                    是否推荐
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
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ URL::asset('js/qiniu.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/plupload.full.min.js') }}"></script>
    <script src="{{ URL::asset('js/plupload/moxie.js') }}"></script>
    <script type="application/javascript">

        //合规校验
        function checkValid() {
            var title = $("#title").val();
            //合规校验
            if (judgeIsNullStr(title)) {
                $("#title").focus();
                return false;
            }
            var img = $("#img").val();
            if (judgeIsNullStr(img)) {
                $("#img").focus();
                return false;
            }
            return true;
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