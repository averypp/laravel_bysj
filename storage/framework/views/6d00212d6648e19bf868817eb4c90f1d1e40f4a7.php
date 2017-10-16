<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?php echo e(asset('static/manage/css/bootstrap.min.css?v=3.3.6')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/manage/css/font-awesome.css?v=4.4.0')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('static/manage/css/animate.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/manage/css/style.css?v=4.1.0')); ?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <?php echo $__env->make('manage.comm.message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div>

            <h1 class="logo-name"><img src="/static/manage/img/logo.png" /></h1>

        </div>
        <h3>欢迎使用 零壹智服 后台管理系统</h3>

        <form class="m-t" role="form" action="" method="post">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="Admin[password]" id="passwordmd5" value="">
            <input type="hidden" name="Admin[sn]" id="sn" value="">
            <div class="form-group">
                <input type="text" name="Admin[account]" class="form-control" placeholder="用户名" required="">
            </div>
            <div class="form-group">
                <input type="password"  class="form-control" placeholder="密码" required="" id="pwd">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b" id="login">登 录</button>

        </form>
    </div>
</div>

<!-- 全局js -->
<script src="<?php echo e(asset('static/manage/js/jquery.min.js?v=2.1.4')); ?>"></script>
<script src="<?php echo e(asset('static/manage/js/bootstrap.min.js?v=3.3.6')); ?>"></script>

<!--本页js-->

<script type="text/javascript" src="<?php echo e(asset('static/manage/js/jquery.md5.js')); ?>"></script>

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
