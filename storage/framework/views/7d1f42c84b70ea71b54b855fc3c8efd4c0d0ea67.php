<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title> 零壹后台 </title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?php echo e(asset('static/manage/css/bootstrap.min.css?v=3.3.6')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/manage/css/font-awesome.min.css?v=4.4.0')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/manage/css/animate.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/manage/css/style.css?v=4.1.0')); ?>" rel="stylesheet">
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i>
        </div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <div class="font-bold"><?php echo e(Session::get('admin_group_name')); ?></div>
                                        <i class="fa fa-user"></i> <strong class="font-bold"><?php echo e(Session::get('admin_account')); ?></strong>
                                    </span>
                                </span>
                        </a>
                    </div>
                    <div class="logo-element"><?php echo e(Session::get('admin_account')); ?>

                    </div>
                </li>
                <?php echo $menuHtml; ?>



            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="#"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">


                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" href="javascript:void (0)" onclick="changePwd()"><i class="fa fa-wrench"></i> 修改密码</a>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" href="<?php echo e(route('lock')); ?>"><i class="fa fa-lock"></i> 锁定</a>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" href="<?php echo e(route('logout')); ?>"><i class="fa fa-power-off"></i> 登出</a>
                    </li>


                
                </ul>
            </nav>
        </div>
        <div class="row J_mainContent" id="content-main">
            <iframe id="J_iframe" width="100%" height="100%" src="<?php echo e(route('manage.home.index')); ?>" frameborder="0" data-id="index_v1.html" seamless></iframe>
        </div>
    </div>
    <!--右侧部分结束-->
</div>

<!-- 全局js -->
<script src="<?php echo e(asset('static/manage/js/jquery.min.js?v=2.1.4')); ?>"></script>
<script src="<?php echo e(asset('static/manage/js/bootstrap.min.js?v=3.3.6')); ?>"></script>
<script src="<?php echo e(asset('static/manage/js/plugins/metisMenu/jquery.metisMenu.js')); ?>"></script>
<script src="<?php echo e(asset('static/manage/js/plugins/slimscroll/jquery.slimscroll.min.js')); ?>"></script>
<script src="<?php echo e(asset('static/manage/js/plugins/layer/layer.min.js')); ?>"></script>

<!-- 自定义js -->
<script src="<?php echo e(asset('static/manage/js/hAdmin.js?v=4.1.0')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('static/manage/js/index.js')); ?>"></script>

<script type="text/javascript">
    function changePwd() {
//        var _html = '<form method="post" action="" class="form-horizontal" >\
//                        <div class="form-group col-sm-10">\
//                            <label class="col-sm-3 control-label">旧密码</label>\
//                            <div class="col-sm-8">\
//                                <input type="text" name="Menu[name]" value="" class="form-control">\
//                            </div>\
//                        </div>\
//                        <div class="form-group col-sm-10">\
//                            <label class="col-sm-3 control-label">新密码</label>\
//                            <div class="col-sm-8">\
//                                <input type="text" name="Menu[name]" value="" class="form-control">\
//                            </div>\
//                        </div>\
//                        <div class="form-group col-sm-10">\
//                            <label class="col-sm-3 control-label">确认新密码</label>\
//                            <div class="col-sm-8">\
//                                <input type="text" name="Menu[name]" value="" class="form-control">\
//                            </div>\
//                        </div>\
//                        <div class="form-group col-sm-10">\
//                            <div class="col-sm-4 col-sm-offset-2">\
//                                <button class="btn btn-primary" type="submit">确定</button>\
//                            </div>\
//                        </div>\
//                    </form>';
//
//        //自定页
//        parent.layer.open({
//            type: 1,
//            skin: 'layui-layer-demo', //样式类名
//            closeBtn: true, //不显示关闭按钮
//            shift: 3,
//            shadeClose: true, //开启遮罩关闭
//            content: _html
//        });

        parent.layer.open({
            type: 2,
            title: '修改密码',
            shadeClose: true,
            shade: 0.8,
            area: ['640px', '40%'],
            content: "<?php echo e(route('admin.changePwd')); ?>" //iframe的url
        });
    }


    function goEdit(url) {
            $('.layui-layer-close1').trigger('click');
            $('#J_iframe').attr('src',url);
    }

</script>

<!-- 第三方插件 -->


</body>

</html>


