
<form method="post" class="form-horizontal" action="">

    @include('manage.comm.message')
    @include('manage.comm._formError')
    {{ csrf_field() }}
    <div class="form-group">
        <label class="col-sm-2 control-label">部门名称</label>

        <div class="col-sm-10">
            <input type="text" name="Group[group_name]"
                   value="{{ old('Group')['group_name'] ? old('Group')['group_name'] : $group->group_name }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group">
        <input type="hidden" name="Group[pid]" value="{{ $group->pid ? $group->pid : 0 }}" id="pid" />
        <label class="col-sm-2 control-label">上级部门</label>
        <div class="col-sm-10">
            <?php $k=1;$display='none' ?>
            @foreach($allGroups as $val)
                <div class="col-sm-4 m-l-n">
                    <select class="form-control m-b {{ $k==1 ? 'num' : '' }}" onchange="upGroupSelect(this,{{ $k++ }})" name="Group[parent_ids][]">
                        <option value="none">--顶级部门--</option>
                        @foreach($val as $v)
                            @if($v['id'] != $group->id)
                                <option value="{{ $v['id'] }}" {{ in_array($v['id'], $group->parent_ids) ? 'selected' : '' }}
                                >{{ $v['group_name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>

    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">类型
        </label>

        <div class="col-sm-10">

            <div class="radio i-checks">
                <label>
                    <input type="radio" value="1" name="Group[type]" {{ $group->type == 1 ? 'checked' : '' }} /> <i></i> 超级管理员</label>
            </div>
            <div class="radio i-checks">
                <label>
                    <input type="radio" value="0" name="Group[type]" {{ $group->type == 0 ? 'checked' : '' }}> <i></i> 普通管理员</label>
            </div>

        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">备注</label>
        <div class="col-sm-10">
            <input type="text" name="Group[remark]"
                   value="{{ old('Group')['remark'] ? old('Group')['remark'] : $group->remark }}"
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
                    <input type="radio" value="1" name="Group[is_lock]" {{ $group->is_lock == 1 ? 'checked' : '' }} /> <i></i> 是</label>
            </div>
            <div class="radio i-checks">
                <label>
                    <input type="radio" value="0" name="Group[is_lock]" {{ $group->is_lock == 0 ? 'checked' : '' }}> <i></i> 否</label>
            </div>

        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">保存内容</button>
            <a class="btn btn-white" href="{{ url('/Manage/AdminGroup/index') }}">取消</a>
        </div>
    </div>
</form>


<!-- 选择上级部门 -->
<script type="text/javascript">

    function upGroupSelect(_obj) {
        var pid = $(_obj).val();
        $(_obj).parent().nextAll().remove();
        if (pid != 'none'){
            $.post('{{ url('/Manage/AdminGroup/ajaxGetGroupByPid') }}',{pid:pid}, function (_response) {
                if (_response.status == 1){
                    var groups = _response.data;
                    if (groups.length > 0){
                        var _html = '<div class="col-sm-4 m-l-n"><select class="form-control m-b"  onchange="upGroupSelect(this)" name="Group[parent_ids][]"><option value="none">--顶级部门--</option>';
                        for(var i in groups){
                            _html += '<option value="'+groups[i].id+'">'+groups[i].group_name+'</option>'
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