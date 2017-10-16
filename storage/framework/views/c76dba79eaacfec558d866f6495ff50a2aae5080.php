<?php if(Session::has('success') || Session::has('error')): ?>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <?php if(Session::has('success')): ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <?php echo e(Session::get('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(Session::has('error')): ?>
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?php echo e(Session::get('error')); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>