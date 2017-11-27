@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>系统配置面板-推荐使用最新版Chrome、QQ、FireFox、风行浏览器</small>
        </h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">服务器配置</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>配置项</th>
                                <th>配置值</th>
                            </tr>
                            <tr>
                                <td>主机</td>
                                <td>{{$serverInfo['pc']}}</td>
                            </tr>
                            <tr>
                                <td>操作系统</td>
                                <td>{{$serverInfo['osname']}}</td>
                            </tr>
                            <tr>
                                <td>服务器语言</td>
                                <td>{{$serverInfo['language']}}</td>
                            </tr>
                            <tr>
                                <td>PHP服务器</td>
                                <td>{{$serverInfo['os']}}</td>
                            </tr>
                            <tr>
                                <td>PHP版本</td>
                                <td>{{$serverInfo['php_version']}}</td>
                            </tr>
                            <tr>
                                <td>MySQL版本</td>
                                <td>{{$serverInfo['mysql_version']}}</td>
                            </tr>
                            <tr>
                                <td>服务器时间</td>
                                <td>{{$serverInfo['time']}}</td>
                            </tr>
                            <tr>
                                <td>服务器端口</td>
                                <td>{{$serverInfo['port']}}</td>
                            </tr>
                            <tr>
                                <td>MySQL已用大小</td>
                                <td>{{$serverInfo['mysql_size']}}</td>
                            </tr>
                            <tr>
                                <td>服务器最大上传文件大小</td>
                                <td>{{$serverInfo['max_upload']}}</td>
                            </tr>
                            <tr>
                                <td>服务器脚本最大执行时间</td>
                                <td>{{$serverInfo['max_ex_time']}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
@endsection