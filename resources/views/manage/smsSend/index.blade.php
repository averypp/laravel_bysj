@extends('manage.comm.layouts')

@section('my_css')
    <link href="{{ asset('static/manage/css/plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
@stop
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>短信发送列表</h5>
                        {{--@if(in_array('add',$option))--}}
                            {{--<div class="ibox-tools">--}}
                                {{--<a href="{{ url('Manage/SmsTemplate/add') }}" class="collapse-link btn btn-info my-inline">--}}
                                    {{--<i class="fa fa-plus"></i>&nbsp;新建短信模板--}}
                                {{--</a>--}}
                            {{--</div>--}}
                        {{--@endif--}}
                    </div>
                    @include('manage.comm.report')
                </div>
            </div>

        </div>
    </div>
@stop

@section('my_js')
    <!-- Peity -->
    <script src="{{ asset('static/manage/js/plugins/peity/jquery.peity.min.js') }}"></script>

    <!-- 自定义js -->
    <script src="{{ asset('static/manage/js/content.js?v=1.0.0') }}"></script>


    <!-- iCheck -->
    <script src="{{ asset('static/manage/js/plugins/iCheck/icheck.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>

@stop