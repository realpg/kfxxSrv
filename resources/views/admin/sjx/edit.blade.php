@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>新建/编辑数据项</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">数据项管理</li>
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
                            <div class="form-group">
                                <label class="col-sm-2 control-label">录入人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                           value="{{$admin->name}}" disabled>
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="doctor_id" class="col-sm-2 control-label">录入人id</label>
                                <div class="col-sm-10">
                                    <input id="doctor_id" name="doctor_id" type="text" class="form-control"
                                           value="{{$admin->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">名称*</label>

                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control"
                                           placeholder="请输入数据项名称"
                                           value="{{ isset($data->name) ? $data->name : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="desc" class="col-sm-2 control-label">描述</label>
                                <div class="col-sm-10">
                                    <textarea id="desc" name="desc" class="form-control" rows="3"
                                              placeholder="请输入 ...">{{ isset($data->desc) ? $data->desc : '' }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="unit" class="col-sm-2 control-label">单位</label>
                                <div class="col-sm-10">
                                    <select id="unit" name="unit" class="form-control">
                                        <option value="mm" {{ $data->unit==='cm' ? 'selected' : '' }}>毫米(mm)</option>
                                        <option value="cm" {{ $data->unit==='cm' ? 'selected' : '' }}>厘米(cm)</option>
                                        <option value="m" {{ $data->unit==='m' ? 'selected' : '' }}>米(m)</option>
                                        <option value="疼度" {{ $data->unit==='疼度' ? 'selected' : '' }}>疼度</option>
                                        <option value="角度" {{ $data->unit==='角度' ? 'selected' : '' }}>角度</option>
                                        <option value="摄氏度" {{ $data->unit==='摄氏度' ? 'selected' : '' }}>摄氏度</option>
                                    </select>
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
            var name = $("#name").val();
            //合规校验
            if (judgeIsNullStr(name)) {
                $("#name").focus();
                return false;
            }
            return true;
        }



    </script>
@endsection