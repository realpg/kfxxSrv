@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>管理用户病例</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">患者管理</li>
            <li class="active">管理用户病例</li>
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
                    <form action="" method="post" class="form-horizontal"
                          onsubmit="return checkValid();">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">病例id</label>

                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           placeholder="病例id"
                                           value="{{ isset($data->id) ? $data->id : '' }}">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="user_id" class="col-sm-2 control-label">患者id</label>
                                <div class="col-sm-10">
                                    <input id="user_id" name="user_id" type="text" class="form-control" placeholder="id"
                                           value="{{ $user->id }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">患者</label>
                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control" placeholder="请输入患者"
                                           value="{{ $user->real_name}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="zz_doctor_id" class="col-sm-2 control-label">主治医师</label>
                                <div class="col-sm-10">
                                    <select id="zz_doctor_id" name="zz_doctor_id" class="form-control">
                                        @foreach($zz_doctors as $zz_doctor)
                                            <option value="{{$zz_doctor->id}}">{{$zz_doctor->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="zz_doctor_id" class="col-sm-2 control-label">康复医师</label>
                                <div class="col-sm-10">
                                    <select id="zz_doctor_id" name="zz_doctor_id" class="form-control">
                                        @foreach($kf_doctors as $kf_doctor)
                                            <option value="{{$kf_doctor->id}}">{{$kf_doctor->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="btime_type" class="col-sm-2 control-label">康复模板</label>
                                <div class="col-sm-10">
                                    <select id="kfmb_id" name="btime_type" class="form-control">
                                        @foreach($kfmbs as $kfmb)
                                            <option value="{{$kfmb->id}}">{{$kfmb->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="ss_time" class="col-sm-2 control-label">手术时间</label>
                                <div class="col-sm-10">
                                    <input id="ss_time" name="ss_time" type="date" class="form-control"
                                           placeholder="请选择手术时间"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="wt_time" class="col-sm-2 control-label">弯腿时间</label>
                                <div class="col-sm-10">
                                    <input id="wt_time" name="wt_time" type="date" class="form-control"
                                           placeholder="请选择弯腿时间"
                                           value="">
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info btn-block btn-flat">设置用户病例</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
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
            var name = $("#name").val();
            //合规校验
            if (judgeIsNullStr(text)) {
                $("#text").focus();
                return false;
            }
            return true;
        }

        //采集数据form的合规校验
        function checkCJSJValid() {
            var min_value = $("#min_value").val();
            //合规校验
            if (judgeIsNullStr(min_value)) {
                $("#min_value").focus();
                return false;
            }
            var max_value = $("#max_value").val();
            //合规校验
            if (judgeIsNullStr(max_value)) {
                $("#max_value").focus();
                return false;
            }
            return true;
        }

        //关联宣教
        function clickGLJHSJ(jh_id) {
            console.log("clickGLJHSJ jh_id:" + jh_id);
            //为删除按钮赋值
            $("#addJHXJModal_confirm_btn").attr("data-value", jh_id);
            $("#glxj_jh_id").val(jh_id);
            $("#addJHXJModal").modal('show');
        }

        //添加采集数据
        function clickTJJHSJ(jh_id) {
            console.log("clickGLJHSJ jh_id:" + jh_id);
            $("#max_value").val("");
            $("#min_value").val("");
            //为删除按钮赋值
            $("#addCJSJModal").attr("data-value", jh_id);
            $("#cjsj_jh_id").val(jh_id);
            $("#addCJSJModal").modal('show');
        }


    </script>
@endsection