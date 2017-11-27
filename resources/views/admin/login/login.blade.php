<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>孵化器 | 管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ URL::asset('plugins/iCheck/square/blue.css') }}">
    <!-- common -->
    <link rel="stylesheet" href="{{ URL::asset('css/common.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            background-image: url("{{URL::asset('/img/logbg.png')}}") !important;
            background-repeat: no-repeat !important;
            background-size: 100% !important;
        }

    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="">
            <img src="{{URL::asset('img/logo.png')}}" style="width: 36px;height: 36px;">
            <span style="color: white; font-family:'Microsoft YaHei' !important;">孵化器管理后台</span>
        </a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">请输入手机号、密码登录，联系<a href="#">管理员</a></p>

        <form action="" method="post" onsubmit="return checkValid()">
            {{csrf_field()}}
            <div id="phonenum_div" class="form-group has-feedback">
                <input id="phonenum" name="phonenum" type="phonenum" class="form-control" placeholder="手机号">
                <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
            </div>
            <div id="password_div" class="form-group has-feedback">
                <input id="password" name="password" type="password" class="form-control" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            @if($msg)
                <div id="error_msg" class="text-danger" style="margin-bottom: 15px;">
                    *{{$msg}}
                </div>
            @endif
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ URL::asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ URL::asset('plugins/iCheck/icheck.min.js') }}"></script>
<!-- common -->
<script src="{{ URL::asset('js/common.js') }}"></script>
<!-- md5 -->
<script src="{{ URL::asset('js/md5.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    //进行表单校验
    function checkValid() {
        //校验手机号
        var phonenum = $("#phonenum").val();
        console.log("phonenum:" + phonenum);
        if (judgeIsNullStr(phonenum) || !isPoneAvailable(phonenum)) {
            $("#phonenum_div").addClass("has-error");
            return false;
        } else {
            $("#phonenum_div").removeClass("has-error");
        }
        //校验密码
        var password = $("#password").val();
        if (judgeIsNullStr(password) || password.length < 6) {
            $("#password_div").addClass("has-error");
            return false;
        } else {
            $("#password_div").removeClass("has-error");
        }
        $("#password").val(hex_md5(password));
        return true;
    }
</script>
</body>
</html>
