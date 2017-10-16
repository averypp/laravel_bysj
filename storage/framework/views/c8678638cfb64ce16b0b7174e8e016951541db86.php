
<form method="post" class="form-horizontal" action="">

    <?php echo $__env->make('manage.comm.message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('manage.comm._formError', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-sm-2 control-label">账户名</label>

        <div class="col-sm-10">
            <input type="text" name="account[account]"
                   value="<?php echo e(old('account')['account'] ? old('account')['account'] : $account->account); ?>"
                   class="form-control">
        </div>
    </div>      

    <div class="form-group">
        <label class="col-sm-2 control-label">姓名</label>
        <div class="col-sm-10">
            <input type="text" name="account[user_name]"
                   value="<?php echo e(old('account')['user_name'] ? old('account')['user_name'] : $account->user_name); ?>"
                   class="form-control">
        </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <label class="col-sm-2 control-label">性别
        </label>
        <div class="col-sm-10">
            <div class="radio i-checks">
                <label>
                    <input type="radio" value="1" name="account[sex]" <?php echo e($account->sex == 1 ? 'checked' : ''); ?> /> <i></i>男</label>
            </div>
            <div class="radio i-checks">
                <label>
                    <input type="radio" value="0" name="account[sex]" <?php echo e($account->sex == 2 ? 'checked' : ''); ?>> <i></i>女</label>
            </div>

        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">职位</label>
        <div class="col-sm-10">
            <input type="text" name="account[position]"
                   value="<?php echo e(old('account')['position'] ? old('account')['position'] : $account->position); ?>"
                   class="form-control">
        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <label class="col-sm-2 control-label">公司名称</label>
        <div class="col-sm-10">
            <input type="text" name="account[company]"
                   value="<?php echo e(old('account')['company'] ? old('account')['company'] : $account->company); ?>"
                   class="form-control" value="1">
        </div>
    </div>
    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <label class="col-sm-2 control-label">是否锁定
        </label>
        <div class="col-sm-10">
            <div class="radio i-checks">
                <label>
                    <input type="radio" value="1" name="account[is_lock]" <?php echo e($account->is_lock == 1 ? 'checked' : ''); ?> /> <i></i> 是</label>
            </div>
            <div class="radio i-checks">
                <label>
                    <input type="radio" value="0" name="account[is_lock]" <?php echo e($account->is_lock == 0 ? 'checked' : ''); ?>> <i></i> 否</label>
            </div>

        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-2 control-label">名片</label>
        <div class="col-sm-2 upPic" id="file-data-1">
            <img src="<?php echo e($account->img  ? $account->img  : '/static/manage/img/icon-add.png'); ?>" onclick="file_select(1)" />
            <input type="hidden" name="account[img]"
                   value="<?php echo e($account->img  ? $account->img : ''); ?>"
                   class="img">
            <a class="close-link" onclick="delPic(this)">删除</a>
        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">保存内容</button>
            <a class="btn btn-white" href="<?php echo e(url('/Manage/Account/index')); ?>">取消</a>
        </div>
    </div>
</form>


<form method="post" class="form-horizontal" action="" enctype="multipart/form-data" id="upload_file">
    <?php echo e(csrf_field()); ?>

    <input type="file" name="files" id="file" accept="*" multiple="" style="display: none" data-key="0" onchange="_upload(this)">
</form>

<script type="text/javascript">

    function file_select(index) {
        $('#file').attr('data-key',index);
        $('#file').trigger('click');
        return;
    }

    function _upload(_obj) {
        //通过表单对象创建 FormData
        var fd = new FormData(document.getElementById("upload_file"));
        //XMLHttpRequest 方式发送请求
        var xhr = new XMLHttpRequest();
        xhr.open("POST" ,"<?php echo e(route('manage.tools.uploadImg')); ?>" , true);
        xhr.send(fd);
        xhr.onload = function(e) {
            if (this.status == 200) {
                var response = $.parseJSON(this.responseText);
                if(response.status == 1){
                    var index = $('#file').attr('data-key');
                    $("#file-data-"+index).find('img').attr('src','/'+$.trim(response.data.path));
                    $("#file-data-"+index).find('.img').val($.trim(response.data.path));
                }
                parent.layer.msg(response.msg);
            };
        };
    }

    function selectCompany(_obj) {
        var company_name = $(_obj).find("option:selected").text();
        $('#company_name').val(company_name);
    }


</script>