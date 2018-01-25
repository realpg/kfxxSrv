@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">修改密码</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="submit" class="btn btn-primary" onclick="formSubmit();">
                    保存密码
                </button>
            </div>
        </div>
    </section>
    <section class="content">
        <form id="form" action="{{URL::asset('/admin/changePassword')}}" method="post" class="form-horizontal">
            {{csrf_field()}}
            <div class="row">
                <div class="col-sm-5 text-right">
                    请输入新密码
                </div>
                <div class="col-sm-7">
                    <input id="password" type="password" name="password">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5 text-right">
                    请再次输入密码
                </div>
                <div class="col-sm-7">
                    <input id="password1" type="password">
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script src="{{ URL::asset('js/md5.js') }}"></script>
    <script type="application/javascript">
        function checkValid() {
            var password = $("#password").val();
            var password1 = $("#password1").val();
            //合规校验
            if (judgeIsNullStr(password)) {
                $("#password").focus();
                return false;
            }
            if (password !== password1) {
                $("#password1").focus();
                return false;
            }

            $("#password").val(hex_md5(password));
            $("#password1").val(hex_md5(password));
            return true;
        }

        function formSubmit() {
            if (checkValid()){
                var param=$("#form").serialize();
                param+='&admin_id={{$admin->id}}';
                console.log(param,typeof(param));
                changePassword("{{URL::asset('')}}", param, function (ret, err) {
                    console.log(JSON.stringify(ret));
                    alert(ret.ret);
                });
            }
        }
    </script>

@endsection