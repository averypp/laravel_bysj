<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--360浏览器优先以webkit内核解析-->


    <title> - <?php echo $__env->yieldContent('title','零壹后台'); ?></title>

    <?php $__env->startSection('global_css'); ?>
        <link rel="shortcut icon" href="favicon.ico">
        <link href="<?php echo e(asset('static/manage/css/bootstrap.min.css?v=3.3.6')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('static/manage/css/font-awesome.css?v=4.4.0')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('static/manage/css/animate.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('static/manage/css/style.css?v=4.1.0')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('static/manage/css/myStyle.css')); ?>" rel="stylesheet">

        <link href="<?php echo e(asset('static/manage/css/bootstrap-select.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('static/manage/js/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('static/manage/css/plugins/iCheck/custom.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldSection(); ?>

    <?php $__env->startSection('my_css'); ?>
    <?php echo $__env->yieldSection(); ?>

</head>

<body class="gray-bg">

    <?php echo $__env->make('manage.comm.message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php $__env->startSection('content'); ?>
        iframe页面
    <?php echo $__env->yieldSection(); ?>



<!-- 全局js -->
<?php $__env->startSection('global_js'); ?>

    <script src="<?php echo e(asset('static/manage/js/jquery.min.js?v=2.1.4')); ?>"></script>
    <script src="<?php echo e(asset('static/manage/js/bootstrap.min.js?v=3.3.6')); ?>"></script>
    <script src="<?php echo e(asset('static/manage/js/plugins/layer/layer.min.js')); ?>"></script>
    <!-- Flot -->
    <script src="<?php echo e(asset('static/manage/js/plugins/flot/jquery.flot.js')); ?>"></script>
    <script src="<?php echo e(asset('static/manage/js/plugins/flot/jquery.flot.tooltip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('static/manage/js/plugins/flot/jquery.flot.resize.js')); ?>"></script>
    <script src="<?php echo e(asset('static/manage/js/plugins/flot/jquery.flot.pie.js')); ?>"></script>
    <script src="<?php echo e(asset('static/manage/js/jquery.cookie.js')); ?>"></script>

    <script src="<?php echo e(asset('static/manage/js/bootstrap-select.js')); ?>"></script>
    <script src="<?php echo e(asset('static/manage/js/plugins/select2/js/select2.min.js')); ?>"></script>

    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-XSRF-TOKEN': $.cookie('XSRF-TOKEN')
            }
        });

        $(function () {
           $('.delObj').click(function () {

               if (confirm('确定要删除吗？') == false ){
                   return false;
               }
           });
        });

        function _spread(_obj) {
            $(_obj).parent().css('display','none');
            $(_obj).parent().next().css('display','inline');
        }
        function _retract(_obj) {
            $(_obj).parent().css('display','none');
            $(_obj).parent().prev().css('display','inline');
        }

        $(function () {
            $('.ratio').on('click','.divBlur li',function () {
                var _obj = $(this);
                _obj.parent().parent().prev().val(_obj.html());
                _obj.parent().parent().prev().prev().val(_obj.attr('data-key'));
                _obj.parent().remove();
            });

//
            $('.ratio').on('blur','.divBlur',function () {
                $("body").click(function(event) {
                    var _this = event.target;
                    var _class = $(_this).attr("class");
                    if(_class!='investmenterSelLi'){
                        $('.investmenterSelLi').parent().remove();
                    }
                });

            });

            $('.select2').select2({
                language: "zh",
            });
            $('.select2-one').select2({
                language: "zh",
                tags: true,
            });
            $('.select2-notags').select2({
                language: "zh",
                tags: false,
            });
            $('.select2-multiple').select2({});
            $('.select2-tags').select2({
                tags: true,
//                tokenSeparators: [',', ' ']
            });


            $('.select2-tags').on("select2:select", function (e) {
                var _obj = $(this);
                var op = _obj.attr('data-op');
                var name = _obj.attr('name');
                var vals = _obj.val();
                var val = vals[vals.length-1];

                if(op != ''){
                    if (val!='' && val!=0 && val.match(/^[0-9]+$/) == null){
                        $.post('<?php echo e(url('/Manage/Tools/ajaxPutObject')); ?>',{op:op,name:val},function(_response) {

                            if (_response.status == 1){
                                var _html = '<input type="hidden" value="'+_response.data.key+'" name="'+name+'"/>';
                                _obj.parent().append(_html);
                            }else {
                                parent.layer.msg(_response.msg);
                            }
                        });
                    }
                }

                return false;
            });

            $('.select2-one').on("select2:select", function (e) {
                var _obj = $(this);
                var op = _obj.attr('data-op');
                var name = _obj.attr('name');
                var val = _obj.val();
                if(op != ''){
                    if (val!='' && val!=0 && val.match(/^[0-9]+$/) == null){
                        $.post('<?php echo e(url('/Manage/Tools/ajaxPutObject')); ?>',{op:op,name:val},function(_response) {

                            if (_response.status == 1){
                                var _html = '<input type="hidden" value="'+_response.data.key+'" name="'+name+'"/>';
                                _obj.parent().append(_html);
                            }else {
                                parent.layer.msg(_response.msg);
                            }
                        });
                    }
                }

                return false;
            });


        });

        function putObject(_obj) {
            var op = $(_obj).parent().prev().attr('data-op');
            var name = $(_obj).parent().prev().val();
            if (name != 0){
                $.post('<?php echo e(url('/Manage/Tools/ajaxPutObject')); ?>',{op:op,name:name}, function (_response) {
                    if (_response.status == 1){
                        var _html = '<option value='+_response.data.key+' selected>'+_response.data.value+'</option>';
                        $(_obj).parent().parent().parent().prev().find('select').append(_html);
                        $(_obj).parent().prev().val('');
                        if (op=='company' && $('#company_name').length > 0){
                            $('#company_name').val(_response.data.value);
                        }
                    }else {
                        parent.layer.msg(_response.msg);
                    }
                });
            }else {
                parent.layer.msg('请先输入');
            }
        }

        function searchMore(_obj) {
            var isShow = $(_obj).attr('data-show');
            if(isShow == 0){
                $('.searchMore').css('display','block');
                $(_obj).attr('data-show',1);
            }else {
                $('.searchMore').css('display','none');
                $(_obj).attr('data-show',0);
            }
        }

        function _lock(_obj,id,module) {
            var isLock = $(_obj).attr('s');
            isLock = isLock==0 ? 1 : 0;
            var op = isLock==1 ? '锁定' : '启用';
            var title = isLock==1 ? '启用' : '锁定';

            var _new = isLock==1 ? 'fa-check' : 'fa-ban';
            var old = isLock==1 ? 'fa-ban' : 'fa-check';

            if (id > 0){
                $.post('/Manage/'+module+'/ajaxLock',{id:id, is_lock:isLock}, function (_response) {
                    if (_response.status == 1){
                        $(_obj).children('i').removeClass(old).addClass(_new);
                        $(_obj).parent().parent().find('.is_lock').html(op);
                        $(_obj).attr('s',isLock);
                        $(_obj).attr('title',title);
                        parent.layer.msg(op+'成功');

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
        //动态添加标签
        function putTag(_obj,categoryId) {
            var op = $(_obj).parent().prev().attr('data-op');
            var name = $(_obj).parent().prev().val();
            if (name != 0){
                $.post('<?php echo e(url('/Manage/Tools/ajaxPutObject')); ?>',{op:op,name:name,categoryId:categoryId}, function (_response) {
                    if (_response.status == 1){
                        var _html = '<option value='+_response.data.key+'>'+_response.data.value+'</option>';
                        $(_obj).parent().parent().parent().prev().find('select').append(_html);
                        $(_obj).parent().prev().val('');

                        var index = $(_obj).parent().parent().parent().parent().children().find('.num').val();
                        var id = _response.data.key;
                        var text = _response.data.value;
                        if ($('.notes-'+index).length > 0){
                            var _html = '<li><div style="width: auto;height: auto;"><p><h4>'+text+'</h4></p><h4></h4><input type="hidden" name="TagIds[]" value="'+id+'"><a href="javascript:void (0)" onclick="removeTag(this)"><i class="fa fa-trash-o "></i></a></div></li>'

                            $('.notes-'+index).append(_html);
                        }else {

                            var indexName =  $(_obj).parent().parent().parent().parent().children().find('.num').find("option:selected").text();

                            var _hmtl = '<div class="col-sm-12" style="padding-left:0; border:1px solid #ccc; margin-bottom: 5px; padding: 10px 0;"><label class="col-sm-1" style="padding-right: 0 ">'+indexName+'</label><div class="col-sm-11" style="padding-left: 0 "><ul class="notes notes-'+index+'"><li><div style="width: auto;height: auto;"><p><h4>'+text+'</h4></p><h4></h4><input type="hidden" name="TagIds[]" value="'+id+'"><a href="javascript:void (0)" onclick="removeTag(this)"><i class="fa fa-trash-o "></i></a></div></li></ul></div></div>';
                            $('#tags-notes').append(_hmtl);
                        }

                    }else {
                        parent.layer.msg(_response.msg);
                    }
                });
            }else {
                parent.layer.msg('请先输入');
            }
        }
        //选择上级分类
        function tagCategorySelect(_obj,index) {

            var num = $(_obj).parent().parent().children().find('.num').find("option:selected").attr('data-children');
            var pid = $(_obj).val();
            $(_obj).parent().nextAll().remove();

            if (pid != 'none'){
                if (index < num) {
                    $.post('<?php echo e(url('/Manage/TagCategory/ajaxGetTagCategoryByPid')); ?>', {pid: pid}, function (_response) {
                        if (_response.status == 1) {
                            var tagCategorys = _response.data;
                            if (tagCategorys.length > 0) {
                                var _html = '<div class="col-sm-3 m-l-n"><select class="form-control m-b"  onchange="tagCategorySelect(this,' + (index+1) + ')" name="TagCategory[parent_ids][]"><option value="none">--请选择分类--</option>';
                                for (var i in tagCategorys) {
                                    _html += '<option value="' + tagCategorys[i].id + '" data-dynamic="'+tagCategorys[i].is_dynamic+'">' + tagCategorys[i].category_name + '</option>'
                                }
                                _html += '</select></div>';

                                $(_obj).parent().after(_html);
                            }

                        } else {
                            parent.layer.msg(_response.msg);
                        }
                    });
                }
                if (index == num){
                    $.post('<?php echo e(url('/Manage/Tag/ajaxGetTagsByCategoryId')); ?>',{categoryId:pid}, function (_response) {
                        if (_response.status == 1){
                            var tags = _response.data;

                            var _html = '<div class="col-sm-3 m-l-n tags"><select class="form-control tag-select" onchange="_selectTag(this)"><option value="">--请选择--</option>';
                            if (tags.length > 0){
                                for(var i in tags){
                                    _html += '<option value="'+tags[i].id+'">'+tags[i].tag_name+'</option>'
                                }
                            }
                            _html += '</select></div>';

                            $(_obj).parent().parent().append(_html);
                            $('.tag-select').select2();

                            var dynamic = $(_obj).find("option:selected").attr('data-dynamic');
                            if (dynamic == 1){
                                var _html = '<div class="col-sm-4 addTag" style="padding-left: 0;margin-bottom: 5px"><div class="input-group"><input type="text" placeholder="" value="" data-op="tags" class="input-sm form-control"><span class="input-group-btn"><a class="btn btn-sm btn-primary" onclick="putTag(this,'+pid+')"> 添加</a></span></div></div>';
                                $(_obj).parent().parent().append(_html);
                            }

                        }else {
                            $(_obj).parent().parent().find('.tags').remove();
                            $(_obj).parent().parent().find('.addTag').remove();
                            parent.layer.msg(_response.msg);
                        }
                    });
                }else {
                    $(_obj).parent().parent().find('.tags').remove();
                    $(_obj).parent().parent().find('.addTag').remove();
                    $('#category_id').val('');
                }
            }else {
                $(_obj).parent().parent().find('.tags').remove();
                $(_obj).parent().parent().find('.addTag').remove();
                $('#category_id').val('');
            }


        }
        //删除标签
        function removeTag(_obj) {
            $(_obj).parent().parent().remove();
        }
        //选择标签
        function _selectTag(_obj) {
            var index = $(_obj).parent().parent().children().find('.num').val();
            var id = $(_obj).val();
            var text = $(_obj).find("option:selected").text();
            if (id >0){
                if ($('.notes-'+index).length > 0){
                    var _html = '<li><div style="width: auto;height: auto;"><p><h4>'+text+'</h4></p><h4></h4><input type="hidden" name="TagIds[]" value="'+id+'"><a href="javascript:void (0)" onclick="removeTag(this)"><i class="fa fa-trash-o "></i></a></div></li>'

                    $('.notes-'+index).append(_html);
                }else {

                    var indexName =  $(_obj).parent().parent().children().find('.num').find("option:selected").text();

                    var _hmtl = '<div class="col-sm-12" style="padding-left:0; border:1px solid #ccc; margin-bottom: 5px; padding: 10px 0;"><label class="col-sm-1" style="padding-right: 0 ">'+indexName+'</label><div class="col-sm-11" style="padding-left: 0 "><ul class="notes notes-'+index+'"><li><div style="width: auto;height: auto;"><p><h4>'+text+'</h4></p><h4></h4><input type="hidden" name="TagIds[]" value="'+id+'"><a href="javascript:void (0)" onclick="removeTag(this)"><i class="fa fa-trash-o "></i></a></div></li></ul></div></div>';
                    $('#tags-notes').append(_hmtl);
                }
            }

        }
        //删除图片
        function delPic(_obj) {
            $(_obj).prev().val('');
            $(_obj).prev().prev().attr('src','/static/manage/img/icon-add.png');
        }

        function _reset() {
            $('form').find('select').val('');
            $('form').find('input[type=checkbox]').prop('checked', false);
            $('form').find('input[type=radio]').prop('checked', false);
            $('form').find('input').val('');
            $('#myFormId').attr("action", "");
        }
    </script>



    <script src="<?php echo e(asset('static/manage/js/pdata.js')); ?>"></script>
    <script type="text/javascript">
        $(function () {
            var html = "<option value=''>== 请选择省份 ==</option>"; $("#search_city").append(html); $("#search_area").append(html);
            $.each(pdata,function(idx,item){
                if (parseInt(item.level) == 0) {
                    html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                }
            });
            $("#search_province").append(html);

            $("#search_province").change(function(){
                if ($(this).val() == "") return;
                $("#search_city option").remove(); $("#search_area option").remove();
                var code = $(this).find("option:selected").attr("exid"); code = code.substring(0,2);
                var html = "<option value=''>== 请选择城市 ==</option>"; $("#search_area").append(html);
                $.each(pdata,function(idx,item){
                    if (parseInt(item.level) == 1 && code == item.code.substring(0,2)) {
                        html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                    }
                });
                $("#search_city").append(html);
            });

            $("#search_city").change(function(){
                if ($(this).val() == "") return;
                $("#search_area option").remove();
                var code = $(this).find("option:selected").attr("exid"); code = code.substring(0,4);
                var html = "<option value=''>== 请选择地区 ==</option>";
                $.each(pdata,function(idx,item){
                    if (parseInt(item.level) == 2 && code == item.code.substring(0,4)) {
                        html += "<option value='" + item.names + "' exid='" + item.code + "'>" + item.names + "</option>";
                    }
                });
                $("#search_area").append(html);
            });
            //绑定
            $("#search_province").val("<?php echo e(isset($search['province']) ? $search['province'] : ''); ?>");$("#search_province").change();
            $("#search_city").val("<?php echo e(isset($search['city']) ? $search['city'] : ''); ?>");$("#search_city").change();
            $("#search_area").val("<?php echo e(isset($search['area']) ? $search['area'] : ''); ?>");


            //排序
            $('.sortBtn').click(function () {
                var dataFiled = $(this).attr('data-filed');
                var sort = $(this).children('font').hasClass('desc') ? 1 : 0;//1:正序，0：倒序
                var _url = location.href;
                _url += _url.indexOf('?')>=0 ? '&orderby='+dataFiled+'&sort='+sort : '?orderby='+dataFiled+'&sort='+sort;
                location.href = _url;
            });
        });
        
        function detailedInformation(id,modelName) {
            if (id > 0){
                $.post('<?php echo e(url('/Manage/Tools/getDetail')); ?>',{id:id,modelName:modelName}, function (_response){
                    if (_response.status == 1){
                        var _html = _response.data.html;
                        if (_html){
                            parent.layer.open({
                                type: 1,
                                skin: 'layui-layer-rim', //加上边框
                                area: ['900px', '600px'], //宽高
                                content: _html
                            });
                        }else {
                            parent.layer.msg('无数据');
                        }

                    }else {
                        parent.layer.msg(_response.msg);
                    }
                });
            }

        }

    </script>
<?php echo $__env->yieldSection(); ?>

<!-- 自定义js -->
<?php $__env->startSection('my_js'); ?>

<?php echo $__env->yieldSection(); ?>


</body>

</html>
