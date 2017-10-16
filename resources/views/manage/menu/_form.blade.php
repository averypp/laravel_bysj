
<form method="post" class="form-horizontal" action="">

    @include('manage.comm.message')
    @include('manage.comm._formError')
    {{ csrf_field() }}
    <div class="form-group">
        <label class="col-sm-2 control-label">菜单名称</label>

        <div class="col-sm-10">
            <input type="text" name="Menu[name]"
                   value="{{ old('Menu')['name'] ? old('Menu')['name'] : $menu->name }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group">
        <input type="hidden" name="Menu[pid]" value="{{ $menu->pid ? $menu->pid : 0 }}" id="pid" />
        <label class="col-sm-2 control-label">上级菜单</label>
        <div class="col-sm-10">
            <div class="col-sm-4 m-l-n">
                <select class="form-control m-b" onchange="upMenuselect(this)">
                    <option value="none">--顶级菜单--</option>
                    @foreach($topMenus as $v)
                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">模块名称</label>
        <div class="col-sm-10">
            <input type="text" name="Menu[module]"
                   value="{{ old('Menu')['module'] ? old('Menu')['module'] : $menu->module }}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">方法名</label>
        <div class="col-sm-10">
            <input type="text" name="Menu[action]"
                   value="{{ old('Menu')['action'] ? old('Menu')['action'] : $menu->action }}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">图标</label>
        <div class="col-sm-10">
            <input type="text" name="Menu[icon]"
                   value="{{ old('Menu')['icon'] ? old('Menu')['icon'] : $menu->icon }}"
                   class="form-control">
        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <label class="col-sm-2 control-label">优先级</label>
        <div class="col-sm-10">
            <input type="text" name="Menu[priority]"
                   value="{{ intval(old('Menu')['priority'] ? old('Menu')['priority'] : $menu->priority) }}"
                   class="form-control" value="1">
            <span class="help-block m-b-none">数字越小越靠前显示</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">备注</label>
        <div class="col-sm-10">
            <input type="text" name="Menu[remark]"
                   value="{{ old('Menu')['remark'] ? old('Menu')['remark'] : $menu->remark }}"
                   class="form-control">
        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <label class="col-sm-2 control-label">是否锁定
        </label>

        <div class="col-sm-10">

            <div class="radio i-checks">
                <label>
                    <input type="radio" value="1" name="Menu[is_lock]" {{ $menu->is_lock == 1 ? 'checked' : '' }} /> <i></i> 是</label>
            </div>
            <div class="radio i-checks">
                <label>
                    <input type="radio" value="0" name="Menu[is_lock]" {{ $menu->is_lock == 0 ? 'checked' : '' }}> <i></i> 否</label>
            </div>

        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">保存内容</button>
            <a class="btn btn-white" href="{{ url('/Manage/Menu/index') }}">取消</a>
        </div>
    </div>
</form>


<!-- 选择上级菜单 -->
<script type="text/javascript">

    function upMenuselect(_obj) {
        var pid = $(_obj).val();
        $(_obj).parent().nextAll().remove();
        if (pid != 'none'){
            $.post('{{ url('/Manage/Menu/ajaxGetMenuByPid') }}',{pid:pid}, function (_response) {
                if (_response.status == 1){
                    var menus = _response.data;
                    if (menus.length > 0){
                        var _html = '<div class="col-sm-4 m-l-n"><select class="form-control m-b"  onchange="upMenuselect(this)"><option value="none">--顶级分类--</option>';
                        for(var i in menus){
                            _html += '<option value="'+menus[i].id+'">'+menus[i].name+'</option>'
                        }
                        _html += '</select></div>';

                        $(_obj).parent().after(_html);
                    }

                }else {
                    parent.layer.msg(_response.msg);
                }
            });
            $('#pid').val(pid);
        }else {
            var id = $(_obj).parent().prev().find('select').val();
            if (id == undefined){
                $('#pid').val(0);
            }else {
                $('#pid').val(id);
            }
        }

    }
</script>
