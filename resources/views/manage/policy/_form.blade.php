
<form method="post" class="form-horizontal" action="">

    @include('manage.comm.message')
    @include('manage.comm._formError')
    {{ csrf_field() }}

    <div class="form-group">
        <label class="col-sm-2 control-label">项目名称</label>
        <div class="col-sm-10">
            <input type="text" name="Data[policy_name]"
                   value="{{ old('Data')['policy_name'] ? old('Data')['policy_name'] : $info->policy_name }}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">政策原件编号</label>
        <div class="col-sm-10">
            <input type="text" name="Data[policy_no]"
                   value="{{ old('Data')['policy_no'] ? old('Data')['policy_no'] : $info->policy_no }}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">政策类型</label>
        <div class="col-sm-10">
            <select class="form-control select2-tags" multiple name="Data[policy_type_ids][]">
                @foreach($policyType as $v)
                    <option value="{{ $v->id }}" {{ in_array($v->id,$info->policy_type_ids) ? 'selected' : '' }}>
                        {{ $v->type_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">政策原件标题</label>
        <div class="col-sm-10">
            <input type="text" name="Data[policy_name_web]"
                   value="{{ old('Data')['policy_name_web'] ? old('Data')['policy_name_web'] : $info->policy_name_web }}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">政策所属地区</label>
        <div class="col-sm-10">
            <input type="text" name="Data[policy_name_web]"
                   value="{{ old('Data')['policy_name_web'] ? old('Data')['policy_name_web'] : $info->policy_name_web }}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">政策条件</label>
        <div class="col-sm-10">
            <textarea name="Data[policy_conditions]" class="form-control">{{ old('Data')['policy_conditions'] ? old('Data')['policy_conditions'] : $info->policy_conditions }}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">政策奖励</label>
        <div class="col-sm-10">
            <textarea name="Data[policy_jiangli]" class="form-control">{{ old('Data')['policy_jiangli'] ? old('Data')['policy_jiangli'] : $info->policy_jiangli }}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">政策限制</label>
        <div class="col-sm-10">
            <textarea name="Data[policy_limits]" class="form-control">{{ old('Data')['policy_limits'] ? old('Data')['policy_limits'] : $info->policy_limits }}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">政策申报材料</label>
        <div class="col-sm-10">
            <textarea name="Data[request_datas]" class="form-control">{{ old('Data')['request_datas'] ? old('Data')['request_datas'] : $info->request_datas }}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">奖金</label>
        <div class="col-sm-2">
            <input type="text" name="Data[amount]"
                   value="{{ old('Data')['amount'] ? old('Data')['amount'] : $info->amount }}"
                   class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">申报时间</label>
        <div class="col-sm-10">
                <input name="Data[start_day]"
                       value="{{ old('Data')['start_day'] ? old('Data')['start_day'] : ($info->start_day?date('Y-m-d',$info->start_day):'') }}"
                       class="form-control layer-date" placeholder="YYYY-MM-DD" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">
            ~
                <input name="Data[end_day]"
                       value="{{ old('Data')['end_day'] ? old('Data')['end_day'] : ($info->end_day?date('Y-m-d',$info->end_day):'') }}"
                       class="form-control layer-date" placeholder="YYYY-MM-DD" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">

        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">原件链接</label>
        <div class="col-sm-10">
            <input type="text" name="Data[web_url]"
                   value="{{ old('Data')['web_url'] ? old('Data')['web_url'] : $info->web_url }}"
                   class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">匹配条件</label>
        <div class="col-sm-10">
            <div class="form-group col-sm-12" style="padding-left: 0">
                <label class="col-sm-2 control-label">基本信息</label>
                <div class="col-sm-10">
                    <ul>
                        <li>
                            <div class="finance">
                                <div class="col-sm-12" style="padding-left: 0">
                                    <span class="col-sm-2 control-label" style="padding-left: 0">注册资本（万元）</span>
                                    <div class="col-sm-2">
                                        <input type="text" name="Condition[registered_capital]" value="{{ old('Condition')['registered_capital'] ? old('Condition')['registered_capital'] : $policyCondition->registered_capital }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="finance">
                                <div class="col-sm-12" style="padding-left: 0">
                                    <span class="col-sm-2 control-label" style="padding-left: 0">职工人数</span>
                                    <div class="col-sm-2">
                                        <input type="text" name="Condition[workers_num]" value="{{ old('Condition')['workers_num'] ? old('Condition')['workers_num'] : $policyCondition->workers_num }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="finance">
                                <div class="col-sm-12" style="padding-left: 0">
                                <span class="col-sm-2 control-label" style="padding-left: 0">科技人员人数</span>
                                    <div class="col-sm-2">
                                        <input type="text" name="Condition[technology_workers_num]"  value="{{ old('Condition')['technology_workers_num'] ? old('Condition')['technology_workers_num'] : $policyCondition->technology_workers_num }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="finance">
                                <div class="col-sm-12" style="padding-left: 0">
                                    <span class="col-sm-2 control-label" style="padding-left: 0">注册时长（月）</span>
                                    <div class="col-sm-2">
                                        <input type="text" name="Condition[register_month]"  value="{{ old('Condition')['register_month'] ? old('Condition')['register_month'] : $policyCondition->register_month }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>创业者身份
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[entrepreneur_type]" {{ $policyCondition->entrepreneur_type == 0 ? 'checked' : '' }} /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[entrepreneur_type]" {{ $policyCondition->entrepreneur_type == 1 ? 'checked' : '' }}  /> <i></i> 在穗普通高等学校、职业学校、技工院校学生（在校及毕业5年内）</label>
                                <label><input type="radio" value="2" name="Condition[entrepreneur_type]" {{ $policyCondition->entrepreneur_type == 2 ? 'checked' : '' }}  /> <i></i> 出国（境）留学回国人员（领取毕业证5年内）</label>
                                <label><input type="radio" value="3" name="Condition[entrepreneur_type]" {{ $policyCondition->entrepreneur_type == 3 ? 'checked' : '' }}  /> <i></i> 毕业超过5年的创业者</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-group col-sm-12" style="padding-left: 0">
                <label class="col-sm-2 control-label">财务信息</label>
                <div class="col-sm-10">
                    <ul>
                        <li>
                            <b>◈</b>为招用人员连续缴纳3个月以上社会保险..
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[social_insurance]" {{ $policyCondition->social_insurance == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[social_insurance]" {{ $policyCondition->social_insurance == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>与招用人员签订1年以上劳动合同
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[labor_contract]" {{ $policyCondition->labor_contract == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[labor_contract]" {{ $policyCondition->labor_contract == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>上一个会计年度至目前：信用贷款部分占整个贷款金额比例超过 50%<i class="hint"></i>
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[credit_loan]" {{ $policyCondition->credit_loan == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[credit_loan]" {{ $policyCondition->credit_loan == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>上一个会计年度至目前：在科技保险机构购买科技保险基础险种以及经中国保监会广东监管局备案确认的科技保险险种
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[technology_insurance]" {{ $policyCondition->technology_insurance == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[technology_insurance]" {{ $policyCondition->technology_insurance == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>企业上年度经营状况：
                            <div class="finance">
                                <div class="col-sm-12" style="padding-left: 0">
                                    <div class="col-sm-6">
                                        <span class="col-sm-4 control-label">净资产（万元）</span>
                                        <div class="col-sm-8">
                                            <input type="text" name="Condition[net_assets]"  value="{{ old('Condition')['net_assets'] ? old('Condition')['net_assets'] : $policyCondition->net_assets }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="col-sm-4 control-label">研发费用（万元）</span>
                                        <div class="col-sm-8">
                                            <input type="text" name="Condition[r_d_expenses]" value="{{ old('Condition')['r_d_expenses'] ? old('Condition')['r_d_expenses'] : $policyCondition->r_d_expenses }}"  class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" style="padding-left: 0">
                                    <div class="col-sm-6">
                                        <span class="col-sm-4 control-label">净利润（万元）</span>
                                        <div class="col-sm-8">
                                            <input type="text" name="Condition[net_profit]" value="{{ old('Condition')['net_profit'] ? old('Condition')['net_profit'] : $policyCondition->net_profit }}"  class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="col-sm-4 control-label">销售收入（万元）</span>
                                        <div class="col-sm-8">
                                            <input type="text" name="Condition[sales_revenue]" value="{{ old('Condition')['sales_revenue'] ? old('Condition')['sales_revenue'] : $policyCondition->sales_revenue }}"  class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-group  col-sm-12" style="padding-left: 0">
                <label class="col-sm-2 control-label">知识产权（近3年内获得的自主知识产权数）</label>
                <div class="col-sm-10">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <span class="col-sm-4 control-label">发明专利（件）</span>
                            <div class="col-sm-8">
                                <input type="text" name="Condition[invention_patent]"  value="{{ old('Condition')['invention_patent'] ? old('Condition')['invention_patent'] : $policyCondition->invention_patent }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="col-sm-4 control-label">实用新型专利（件）</span>
                            <div class="col-sm-8">
                                <input type="text" name="Condition[utility_model_patent]"  value="{{ old('Condition')['utility_model_patent'] ? old('Condition')['utility_model_patent'] : $policyCondition->utility_model_patent }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <span class="col-sm-4 control-label">外观专利（件）</span>
                            <div class="col-sm-8">
                                <input type="text" name="Condition[appearance_patent]"  value="{{ old('Condition')['appearance_patent'] ? old('Condition')['appearance_patent'] : $policyCondition->appearance_patent }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="col-sm-4 control-label">新品种（件）</span>
                            <div class="col-sm-8">
                                <input type="text" name="Condition[new_variety]"  value="{{ old('Condition')['new_variety'] ? old('Condition')['new_variety'] : $policyCondition->new_variety }}" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <span class="col-sm-4 control-label">注册商标（件）</span>
                            <div class="col-sm-8">
                                <input type="text" name="Condition[registered_trademark]" value="{{ old('Condition')['registered_trademark'] ? old('Condition')['registered_trademark'] : $policyCondition->registered_trademark }}"  class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <span class="col-sm-4 control-label">软件著作权（件）</span>
                            <div class="col-sm-8">
                                <input type="text" name="Condition[software_copyright]" value="{{ old('Condition')['software_copyright'] ? old('Condition')['software_copyright'] : $policyCondition->software_copyright }}"  class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <span class="col-sm-4 control-label">集成电路（件）</span>
                            <div class="col-sm-8">
                                <input type="text" name="Condition[integrated_circuit]"  value="{{ old('Condition')['integrated_circuit'] ? old('Condition')['integrated_circuit'] : $policyCondition->integrated_circuit }}" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-12" style="padding-left: 0">
                <label class="col-sm-2 control-label">研发管理</label>
                <div class="col-sm-10">
                    <ul>
                        <li>
                            <div class="col-sm-12" style="padding-left: 0">
                                <b>◈</b>研发场地面积
                                <div class="radio i-checks">
                                    <label><input type="radio" value="0" name="Condition[r_d_site_area]" {{ $policyCondition->r_d_site_area == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                    <label><input type="radio" value="1" name="Condition[r_d_site_area]" {{ $policyCondition->r_d_site_area == 1 ? 'checked' : '' }}  /> <i></i> 0~50m²</label>
                                    <label><input type="radio" value="2" name="Condition[r_d_site_area]" {{ $policyCondition->r_d_site_area == 2 ? 'checked' : '' }}  /> <i></i> 50m²~100m²</label>
                                    <label><input type="radio" value="3" name="Condition[r_d_site_area]" {{ $policyCondition->r_d_site_area == 3 ? 'checked' : '' }}  /> <i></i> 100m²~200m²</label>
                                    <label><input type="radio" value="4" name="Condition[r_d_site_area]" {{ $policyCondition->r_d_site_area == 4 ? 'checked' : '' }}  /> <i></i> 200m²~300mm²</label>
                                    <label><input type="radio" value="5" name="Condition[r_d_site_area]" {{ $policyCondition->r_d_site_area == 5 ? 'checked' : '' }}  /> <i></i> 300m²~500mm²</label>
                                    <label><input type="radio" value="6" name="Condition[r_d_site_area]" {{ $policyCondition->r_d_site_area == 6 ? 'checked' : '' }}  /> <i></i> 500m²以上</label>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-sm-12" style="padding-left: 0">
                                <div class="col-sm-6">
                                    <span class="col-sm-4 control-label">研发设备原值（万元）</span>
                                    <div class="col-sm-8">
                                        <input type="text" name="Condition[r_d_equipment_cost]" value="{{ old('Condition')['r_d_equipment_cost'] ? old('Condition')['r_d_equipment_cost'] : $policyCondition->r_d_equipment_cost }}"  class="form-control">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="col-sm-12" style="padding-left: 0">
                                <div class="col-sm-6">
                                    <span class="col-sm-4 control-label">近三年企业自主立项数目（件）</span>
                                    <div class="col-sm-8">
                                        <input type="text" name="Condition[independent_project]"  value="{{ old('Condition')['independent_project'] ? old('Condition')['independent_project'] : $policyCondition->independent_project }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="form-group col-sm-12" style="padding-left: 0">
                <label class="col-sm-2 control-label">其他信息</label>
                <div class="col-sm-10">
                    <ul>
                        <li>
                            <b>◈</b>上一个会计年度至目前在广州股权交易中心科创板挂牌
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[branch_board]" {{ $policyCondition->branch_board == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[branch_board]" {{ $policyCondition->branch_board == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>上一个会计年度至目前与券商签订推荐挂牌新三板协议
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[new_three_plate_agreement]" {{ $policyCondition->new_three_plate_agreement == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[new_three_plate_agreement]" {{ $policyCondition->new_three_plate_agreement == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>上一个会计年度至目前完成股份制改造
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[shareholding_system_reform]" {{ $policyCondition->shareholding_system_reform == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[shareholding_system_reform]" {{ $policyCondition->shareholding_system_reform == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>企业注册在科技企业孵化器内
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[incubator]" {{ $policyCondition->incubator == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[incubator]" {{ $policyCondition->incubator == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            ①     在银行贷款过程中支付担保费用且担保费支付时间在上一个会计年度内
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[guarantee_fee]" {{ $policyCondition->guarantee_fee == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[guarantee_fee]" {{ $policyCondition->guarantee_fee == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            ②     目前实际租用孵化器场地在两年内
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[rent_time]" {{ $policyCondition->rent_time == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[rent_time]" {{ $policyCondition->rent_time == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                        <li>
                            <b>◈</b>在市科技创新委发布的科技创新服务机构和创新服务目录内购买科技服务
                            <div class="radio i-checks">
                                <label><input type="radio" value="0" name="Condition[buy_technology_services]" {{ $policyCondition->buy_technology_services == 0 ? 'checked' : '' }}  /> <i></i> 不限</label>
                                <label><input type="radio" value="1" name="Condition[buy_technology_services]" {{ $policyCondition->buy_technology_services == 1 ? 'checked' : '' }}  /> <i></i> 必要条件</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
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
            <a class="btn btn-white" href="{{ url('/Manage/Policy/index') }}">取消</a>
        </div>
    </div>
</form>
