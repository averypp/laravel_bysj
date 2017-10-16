

<?php $__env->startSection('my_css'); ?>
    <link href="<?php echo e(asset('static/manage/css/plugins/dataTables/dataTables.bootstrap.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">

        <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>菜单列表</h5>
                    <?php if(in_array('add',$option)): ?>
                        <div class="ibox-tools">
                            <a href="<?php echo e(url('Manage/Menu/add')); ?>" class="collapse-link btn btn-info my-inline">
                                <i class="fa fa-plus"></i>&nbsp;新建菜单
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="ibox-content">
                    <div class="row">

                        <div class="col-sm-3">
                            <form method="get" action="">
                                <div class="input-group">
                                    <?php echo e(csrf_field()); ?>

                                    <input type="text" placeholder="请输入关键词" name="keyword" value="<?php echo e(isset($search['keyword']) ? $search['keyword'] : ''); ?>" class="input-sm form-control"> <span class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-primary"> 搜索</button> </span>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>显示名称</th>
                                    <th>菜单</th>
                                    <th>链接</th>
                                    <th>上级菜单</th>
                                    <th>优先级</th>
                                    <th>状态</th>
                                    <th>备注</th>
                                    <th>修改时间</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                <tr class="text-c">
                                    <td><?php echo e($v->name); ?></td>
                                    <td><?php echo e($v->menu); ?></td>
                                    <td><?php echo e($v->url); ?></td>
                                    <td><?php echo e(isset($menusName[$v->pid]) ? $menusName[$v->pid] : '不存在'); ?></td>
                                    <td><?php echo e($v->priority); ?></td>
                                    <td><span class="is_lock"><?php echo e($v->is_lock==0 ? '启用' : '锁定'); ?></span></td>
                                    <td><?php echo \App\Tools\Html::breviary($v->remark); ?></td>
                                    <td><?php echo e(date('Y-m-d H:i:s',$v->updated_at)); ?></td>
                                    <td><?php echo e(date('Y-m-d H:i:s',$v->created_at)); ?></td>

                                    <td class="td-manage">
                                        <?php echo App\Tools\Html::operation('Menu', $v, $option); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                            </tbody>
                        </table>


                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                <?php echo e($menus->render()); ?>

                            </div>
                        </div>
                    </div>




                </div>

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