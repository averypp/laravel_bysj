

<?php $__env->startSection('my_css'); ?>
    <link href="<?php echo e(asset('static/manage/css/plugins/iCheck/custom.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>修改服务范围</h5>
                    </div>
                    <div class="ibox-content">
                        <?php echo $__env->make('manage.demandRange._form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('my_js'); ?>
    <!-- 自定义js -->
    <script src="<?php echo e(asset('static/manage/js/content.js?v=1.0.0')); ?>"></script>

    <!-- iCheck -->
    <script src="<?php echo e(asset('static/manage/js/plugins/iCheck/icheck.min.js')); ?>"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('manage.comm.layouts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>