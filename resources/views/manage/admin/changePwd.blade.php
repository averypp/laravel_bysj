@extends('manage.comm.layouts')

@section('my_css')
    <link href="{{ asset('static/manage/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">

                        <form method="post" class="form-horizontal" action="">

                            @include('manage.comm.message')
                            @include('manage.comm._formError')
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-sm-2 control-label">旧密码</label>
                                <div class="col-sm-10">
                                    <input type="password" id="oldPwd"
                                           value=""
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">新密码</label>
                                <div class="col-sm-10">
                                    <input type="password" id="newPwd"
                                           value=""
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">确认新密码</label>
                                <div class="col-sm-10">
                                    <input type="password" id="reNewPwd"
                                           value=""
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-primary" onclick="_submit()">确定</a>
                                </div>
                            </div>
                        </form>

        </div>

    </div>
@stop

@section('my_js')
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

        function _submit() {
            var oldPwd = $('#oldPwd').val();
            var newPwd = $('#newPwd').val();
            var reNewPwd = $('#reNewPwd').val();
            if (newPwd != reNewPwd){
                parent.layer.msg('两次密码不一致');
            }else {
                $.post('{{ url('/Manage/Admin/changePwd') }}',{oldPwd:oldPwd,newPwd:newPwd,reNewPwd:reNewPwd}, function (_response) {
                    if (_response.status == 1){
                        parent.layer.msg('修改成功，请重新登录');
                        setTimeout(function () {
                            window.top.location.href = "{{ route('login') }}";
                        },1500);

                    }else {
                        parent.layer.msg(_response.msg);
                    }
                });
            }
        }
    </script>

@stop