
<form method="post" class="form-horizontal" action="">

    @include('manage.comm.message')
    @include('manage.comm._formError')
    {{ csrf_field() }}

    <div class="form-group">
        <label class="col-sm-2 control-label">模板名称</label>
        <div class="col-sm-4">
            <select class="form-control " name="Data[template_id]">
                <option value="">--请选择--</option>
                @foreach($templateIds as $key=>$val)
                        <option value="{{ $key }}" {{ $key==$info->template_id ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">模板内容</label>

        <div class="col-sm-10">
            <textarea name="Data[template_content]" class="form-control diff-textarea">{{ old('Data')['template_content'] ? old('Data')['template_content'] : $info->template_content }}</textarea>
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
            <a class="btn btn-white" href="{{ url('/Manage/SmsTemplate/index') }}">取消</a>
        </div>
    </div>
</form>
