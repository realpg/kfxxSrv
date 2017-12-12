<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>康复云平台 | 管理后台</title>
    <link href="{{ URL::asset('img/favor.ico') }}" rel="shortcut icon" type="image/x-icon"/>
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
    <!--login-->
    <link rel="stylesheet" href="{{ URL::asset('css/login.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            background: url("{{URL::asset('/img/web_login_bg.jpg')}}") no-repeat center;
            background-size: cover;
        }

        #darkbannerwrap {
            background: url("{{URL::asset('/img/aiwrap.png')}}");
            width: 18px;
            height: 10px;
            margin: 0 0 20px -58px;
            position: relative;
        }

    </style>
</head>
<body>

<div class="login">
    <div class="message font-size-22 text-white">康复云平台-后台登录</div>
    <div id="darkbannerwrap"></div>

    <form action="" method="post" onsubmit="return checkValid()">
        {{csrf_field()}}
        <input id="phonenum" name="phonenum" placeholder="手机号" required="" type="text">
        <hr class="hr15">
        <input id="password" name="password" placeholder="密码" required="" type="password">
        <hr class="hr15">
        <input value="登录" style="width:100%;" type="submit">
        <hr class="hr20">
    </form>

    @if($msg)
        <div id="error_msg" class="text-danger">
            *{{$msg}}
        </div>
    @endif
</div>

<div class="copyright">© 2017-2018 by
    <a href="http://www.puh3.net.cn/" target="_blank" style=" color: rgba(255, 255, 255, 0.85);">
        北京大学第三医院</a>
</div>

</body>
</html>
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
    //进行表单校验
    function checkValid() {
        var password = $("#password").val();
        //对密码进行md5加密
        $("#password").val(hex_md5(password));
        return true;
    }

</script>