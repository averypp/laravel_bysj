

<?php $__env->startSection('my_css'); ?>
    <link href="<?php echo e(asset('static/manage/css/plugins/dataTables/dataTables.bootstrap.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>部门列表</h5>
                        <?php if(in_array('add',$option)): ?>
                            <div class="ibox-tools">
                                <a href="<?php echo e(url('Manage/AdminGroup/add')); ?>" class="collapse-link btn btn-info my-inline">
                                    <i class="fa fa-plus"></i>&nbsp;新建部门
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php echo $__env->make('manage.comm.report', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('my_js'); ?>
    <!-- Peity -->
    <script src="<?php echo e(asset('static/manage/js/plugins/peity/jquery.peity.min.js')); ?>"></script>

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