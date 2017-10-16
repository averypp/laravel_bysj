<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 锁定</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico">
    <link href="{{ asset('static/manage/css/bootstrap.min.css?v=3.3.6') }}" rel="stylesheet">
    <link href="{{ asset('static/manage/css/font-awesome.css?v=4.4.0') }}" rel="stylesheet">

    <link href="{{ asset('static/manage/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('static/manage/css/style.css?v=4.1.0') }}" rel="stylesheet">
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>

</head>

<body class="gray-bg">

<div class="lock-word animated fadeInDown">
</div>
<div class="middle-box text-center lockscreen animated fadeInDown">
    <div>
        @include('manage.comm.message')
        <div class="m-b-md">
            <img alt="image" class="img-circle circle-border" src="/static/manage/img/logo_v.jpg">
        </div>
        <h3>{{ Session::get('admin_account') }}</h3>


        <p>解锁密码</p>
        <form class="m-t" role="form" action="" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="Admin[account]" value="{{ Session::get('admin_account') }}">
            <input type="hidden" name="Admin[password]" id="passwordmd5" value="">
            <input type="hidden" name="Admin[sn]" id="sn" value="">
            <div class="form-group">
                <input type="password" class="form-control" placeholder="******" required="" id="pwd">
            </div>
            <button type="submit" class="btn btn-primary block full-width" id="login">解锁</button>
        </form>
    </div>
</div>

<!-- 全局js -->
<script src="{{ asset('static/manage/js/jquery.min.js?v=2.1.4') }}"></script>
<script src="{{ asset('static/manage/js/bootstrap.min.js?v=3.3.6') }}"></script>


<!--本页js-->

<script type="text/javascript" src="{{ asset('static/manage/js/jquery.md5.js') }}"></script>

<script>

    $(function(){
        $("#login").click(function(){
            var sn = parseInt(Math.random()*100001,10);
            var password = $.md5( $.md5($('#pwd').val()) + sn );
            $("#sn").val(sn);
            $('#passwordmd5').val(password);

        });
    });
</script>

</body>

</html>
