
<form method="post" class="form-horizontal" action="">

    @include('manage.comm.message')
    @include('manage.comm._formError')
    {{ csrf_field() }}
    <div class="form-group">
        <label class="col-sm-2 control-label">账号</label>

        <div class="col-sm-10">
            <input type="text" name="Admin[account]"
                   value="{{ old('Admin')['account'] ? old('Admin')['account'] : $admin->account }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">密码</label>

        <div class="col-sm-10">
            <input type="password" name="Admin[password]"
                   value=""
                   class="form-control">
            @if(Route::currentRouteName()=='admin.edit')
            <span class="help-block m-b-none">不填写则不修改</span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">真实姓名</label>

        <div class="col-sm-10">
            <input type="text" name="Admin[real_name]"
                   value="{{ old('Admin')['real_name'] ? old('Admin')['real_name'] : $admin->real_name }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">手机号码</label>

        <div class="col-sm-10">
            <input type="text" name="Admin[mobile]"
                   value="{{ old('Admin')['mobile'] ? old('Admin')['mobile'] : $admin->mobile }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">职位</label>

        <div class="col-sm-10">
            <input type="text" name="Admin[position]"
                   value="{{ old('Admin')['position'] ? old('Admin')['position'] : $admin->position }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group">
        <input type="hidden" name="Admin[group_id]" value="{{ $admin->group_id ? $admin->group_id : 0 }}" id="group_id" />
        <label class="col-sm-2 control-label">所属部门</label>
        <div class="col-sm-10">
            <?php $k=1;$display='none' ?>
            @foreach($allGroups as $val)
                <div class="col-sm-4 m-l-n">
                    <select class="form-control m-b {{ $k==1 ? 'num' : '' }}" onchange="groupSelect(this,{{ $k++ }})">
                        <option value="none">--请选择部门--</option>
                        @foreach($val as $v)
                                <option value="{{ $v['id'] }}" {{ in_array($v['id'], $group->parent_ids) ? 'selected' : '' }}
                                >{{ $v['group_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>

    </div>


    <div class="form-group">
        <label class="col-sm-2 control-label">绑定用户（微信接受消息）</label>
        <div class="col-sm-10">
            <div class="col-sm-4 m-l-n">
                <select class="form-control select2"  name="Admin[bind_wechat_user]">
                    <option value="0">--选择用户--</option>
                    @foreach($customers as $v)
                        <option value="{{ $v->id }}" {{ ($v->id == (old('Admin')['bind_wechat_user']?old('Admin')['bind_wechat_user']:$admin->bind_wechat_user) ) ? 'selected' : '' }}>{{ $v->real_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">备注</label>
        <div class="col-sm-10">
            <input type="text" name="Admin[remark]"
                   value="{{ old('Admin')['remark'] ? old('Admin')['remark'] : $admin->remark }}"
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
                    <input type="radio" value="1" name="Admin[is_lock]" {{ $admin->is_lock == 1 ? 'checked' : '' }} /> <i></i> 是</label>
            </div>
            <div class="radio i-checks">
                <label>
                    <input type="radio" value="0" name="Admin[is_lock]" {{ $admin->is_lock == 0 ? 'checked' : '' }}> <i></i> 否</label>
            </div>

        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">保存内容</button>
            <a class="btn btn-white" href="{{ url('/Manage/Admin/index') }}">取消</a>
        </div>
    </div>
</form>


<!-- 选择上级部门 -->
<script type="text/javascript">

    function groupSelect(_obj) {
        var pid = $(_obj).val();
        $(_obj).parent().nextAll().remove();
        if (pid != 'none'){
            $.post('{{ url('/Manage/AdminGroup/ajaxGetGroupByPid') }}',{pid:pid}, function (_response) {
                if (_response.status == 1){
                    var groups = _response.data;
                    if (groups.length > 0){
                        var _html = '<div class="col-sm-4 m-l-n"><select class="form-control m-b"  onchange="groupSelect(this)"><option value="none">--请选择部门--</option>';
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
            $('#group_id').val(pid);
        }else {
            var id = $(_obj).parent().prev().find('select').val();
            if (id == undefined){
                $('#group_id').val(0);
            }else {
                $('#group_id').val(id);
            }
        }

    }
</script>