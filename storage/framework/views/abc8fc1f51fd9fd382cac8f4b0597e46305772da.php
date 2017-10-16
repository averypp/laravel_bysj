

<?php $__env->startSection('my_css'); ?>
    <link href="<?php echo e(asset('static/manage/css/plugins/dataTables/dataTables.bootstrap.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>服务商列表</h5>
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
        $(function () {
            $('#export').click(function () {
                $('#myFormId').attr("action", "<?php echo e(url('/Manage/Company/export')); ?>");
            });
            $('#submit').click(function () {
                $('#myFormId').attr("action", "");
            });
        });
    </script>

    <script type="text/javascript">
        function detail(id) {
            if (id > 0){
                $.post('<?php echo e(url('/Manage/PersonAuth/getDetail')); ?>',{id:id}, function (_response) {
                    if (_response.status == 1){
                        var _html = _response.data.introduce;

                        if (_html){
                            parent.layer.open({
                                type: 1,
                                skin: 'layui-layer-rim', //加上边框
                                area: ['800px', '600px'], //宽高
                                content: _html
                            });
                        }else {
                            parent.layer.msg('无数据');
                        }

                    }else {
                        parent.layer.msg(_response.msg);
                    }
                });
            }else {
                parent.layer.msg('非法参数');
            }

        }
    </script>

    <script type="text/javascript">
        <?php if(in_array('examine',$option)): ?>
        function examine(_obj,id,status) {
            var remark = '';
            if (status == 2){
                remark = prompt("请输入原因:");
            }
            if (remark != null){

                if (status == 1 ){
                    if (confirm('确定吗？') == false ){
                        return false;
                    }
                }else {
                    if (remark == ''){
                        alert('原因为必填');
                        return false;
                    }
                }

                var admin = "<?php echo e(Session::get('admin_name')); ?>";
                $.post('<?php echo e(url('/Manage/FactorApply/examine')); ?>',{id:id, status:status,remark:remark}, function (_response) {
                    if (_response.status == 1){
                        op = status==1 ? '通过' : '不通过';
                        $(_obj).parent().parent().find('.status').html(op);
                        $(_obj).parent().parent().find('.oper').html(admin);
                        $(_obj).parent().html('');
                        parent.layer.msg('操作成功');

                    }else {
                        parent.layer.msg(_response.msg);
                    }
                });
            }
        }
        <?php endif; ?>


        $(function () {
            $('#export').click(function () {
                $('#myFormId').attr("action", "<?php echo e(url('/Manage/ActivitySignUp/export')); ?>");
            });
            $('#submit').click(function () {
                $('#myFormId').attr("action", "");
            });
        });


        function selectAll(id){
            $(".table-striped").find("input[type='checkbox']").each( function() {
                $(this).prop('checked', true);
                $(this).parents('.icheckbox_square-green').addClass('checked');
            });
        }

        function invertSelect(id){
            $(".table-striped").find("input[type='checkbox']").each( function() {
                if($(this).prop('checked')) {
                    $(this).prop('checked', false);
                    $(this).parents('.icheckbox_square-green').removeClass('checked');
                } else {
                    $(this).prop('checked', true);
                    $(this).parents('.icheckbox_square-green').addClass('checked');
                }
            });
        }

        function doSign(_obj,id) {
            $.post('<?php echo e(url('/Manage/ActivitySignUp/doSign')); ?>',{id:id}, function (_response) {
                if (_response.status == 1){
                    $(_obj).parent().html('已签到');
                    parent.layer.msg('操作成功');

                }else {
                    parent.layer.msg(_response.msg);
                }
            });
        }

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('manage.comm.layouts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>