
<form method="post" class="form-horizontal" action="">

    @include('manage.comm.message')
    @include('manage.comm._formError')
    {{ csrf_field() }}

    <div class="form-group">
        <label class="col-sm-2 control-label">类型名称</label>

        <div class="col-sm-10">
            <input type="text" name="Data[range_name]"
                   value="{{ old('Data')['range_name'] ? old('Data')['range_name'] : $info->range_name }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">备注</label>
        <div class="col-sm-10">
            <input type="text" name="Data[remark]"
                   value="{{ old('Data')['remark'] ? old('Data')['remark'] : $info->remark }}"
                   class="form-control">
        </div>
    </div>

    <div class="hr-line-dashed"></div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <button class="btn btn-primary" type="submit">保存内容</button>
            <a class="btn btn-white" href="{{ url('/Manage/DemandRange/index') }}">取消</a>
        </div>
    </div>
</form>
