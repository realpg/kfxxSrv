@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>业务错误，请联系<a href="#">管理员处理</a></small>
        </h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page" style="margin-top: 100px;">
            <h2 class="headline text-red">500</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-red"></i>There is some Error</h3>

                <p>
                    具体错误如下（请向管理员展示）
                </p>
                <p>
                    @if($msg)
                        {{$msg}}
                    @else
                        暂无错误提示，请重现问题并反馈管理员
                    @endif
                </p>
                <p>
                    <a href="#">@ISART 2015-2017</a>
                </p>
            </div>
        </div>

    </section>
@endsection