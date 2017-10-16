<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * 报表配置
 * Date: 1/12/2017
 * Time: 4:39 PM
 */

return [
    //总后台
    'Manage'=>[
        //内部人员
        'admin'=>[
            'formHeader'=>[
                'account'=>[
                    'name'=>'账号',
                    'class'=>'',
                    'func'=>''
                ],
                'real_name'=>[
                    'name'=>'姓名',
                    'class'=>'',
                    'func'=>''
                ],
                'mobile'=>[
                    'name'=>'手机',
                    'class'=>'',
                    'func'=>''
                ],
                'group_id'=>[
                    'name'=>'部门',
                    'class'=>'',
                    'func'=>''
                ],
                'position'=>[
                    'name'=>'职位',
                    'class'=>'',
                    'func'=>''
                ],
                'is_lock'=>[
                    'name'=>'状态',
                    'class'=>'is_lock',
                    'func'=>''
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'updated_at'=>[
                    'name'=>'修改时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[
                'account',
                'real_name',
                'group_id',
                'position',
                'remark',
            ],
            'formSearch'=>[
                'real_name'=>[
                    'title'=>'姓名',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
                'group_id'=>[
                    'title'=>'部门',
                    'type'=>'select',
                    'class'=>'select2',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],

        //部门
        'adminGroup'=>[
            'formHeader'=>[
                'group_name'=>[
                    'name'=>'部门名称',
                    'class'=>'',
                    'func'=>''
                ],
                'pid'=>[
                    'name'=>'上级部门',
                    'class'=>'',
                    'func'=>''
                ],
                'is_lock'=>[
                    'name'=>'状态',
                    'class'=>'is_lock',
                    'func'=>''
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'updated_at'=>[
                    'name'=>'修改时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'group_name'=>[
                    'title'=>'部门名称',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
                'pid'=>[
                    'title'=>'上级部门',
                    'type'=>'select',
                    'class'=>'select2',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],

            ],
            'detail'=>[],
        ],

        //权限管理
        'auth'=>[
            'formHeader'=>[
                'group_name'=>[
                    'name'=>'部门名称',
                    'class'=>'',
                    'func'=>''
                ],
                'type'=>[
                    'name'=>'类型',
                    'class'=>'',
                    'func'=>''
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'updated_at'=>[
                    'name'=>'修改时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'group_name'=>[
                    'title'=>'部门名称',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],

        //系统日志
        'systemLog'=>[
            'formHeader'=>[
                'adminAccount'=>[
                    'name'=>'管理员账号',
                    'class'=>'',
                    'func'=>''
                ],
                'adminName'=>[
                    'name'=>'管理员姓名',
                    'class'=>'',
                    'func'=>''
                ],
                'adminGroup'=>[
                    'name'=>'部门',
                    'class'=>'',
                    'func'=>''
                ],
                'module'=>[
                    'name'=>'平台',
                    'class'=>'',
                    'func'=>''
                ],
                'controller'=>[
                    'name'=>'模块',
                    'class'=>'',
                    'func'=>''
                ],
                'action'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>''
                ],
                'ip'=>[
                    'name'=>'ip',
                    'class'=>'',
                    'func'=>''
                ],
                'url'=>[
                    'name'=>'访问地址',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'remark',
                    'func'=>'breviary'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'module'=>[
                    'title'=>'平台',
                    'type'=>'select',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
                'controller'=>[
                    'title'=>'模块',
                    'type'=>'select',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
                'action'=>[
                    'title'=>'操作',
                    'type'=>'select',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],
        'account'=>[
            'formHeader'=>[
                'account'=>[
                    'name'=>'账号',
                    'class'=>'',
                    'func'=>''
                ],
                'user_name'=>[
                    'name'=>'姓名',
                    'class'=>'',
                    'func'=>''
                ],
                'status'=>[
                    'name'=>'认证状态',
                    'class'=>'',
                    'func'=>''
                ],
                'sex'=>[
                    'name'=>'性别',
                    'class'=>'',
                    'func'=>''
                ],
                'company_name'=>[
                    'name'=>'公司',
                    'class'=>'',
                    'func'=>''
                ],
                'position_id'=>[
                    'name'=>'职位',
                    'class'=>'',
                    'func'=>'getPositionName'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[
                // 'account',
                // 'real_name',
                // 'group_id',
                // 'position',
                // 'remark',
            ],
            'formSearch'=>[
                'account'=>[
                    'title'=>'账号',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
                'auth_status'=>[
                    'title'=>'验证状态',
                    'type'=>'select',
                    'class'=>'select2',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],
        'personAuth'=>[
            'formHeader'=>[
                'account'=>[
                    'name'=>'账号',
                    'class'=>'',
                    'func'=>''
                ],
                'real_name'=>[
                    'name'=>'真实姓名',
                    'class'=>'',
                    'func'=>''//detail
                ],
                'identification'=>[
                    'name'=>'身份证号码',
                    'class'=>'',
                    'func'=>''
                ],
                'sex'=>[
                    'name'=>'性别',
                    'class'=>'',
                    'func'=>''
                ],
                'status'=>[
                    'name'=>'认证状态',
                    'class'=>'status',
                    'func'=>''
                ],
                'examine'=>[
                    'name'=>'审核',
                    'class'=>'examine',
                    'func'=>'examineHtml'
                ],

                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[
                // 'account',
                // 'real_name',
                // 'group_id',
                // 'position',
                // 'remark',
            ],
            'formSearch'=>[
                // 'real_name'=>[
                //     'title'=>'姓名',
                //     'type'=>'text',
                //     'class'=>'',
                //     'id'=>'',
                //     'display'=>'',
                //     'default'=>'',
                //     'is_show'=>1
                // ],
                // 'group_id'=>[
                //     'title'=>'部门',
                //     'type'=>'select',
                //     'class'=>'select2',
                //     'id'=>'',
                //     'display'=>'',
                //     'default'=>'',
                //     'is_show'=>1
                // ],
            ],
            'detail'=>[],
        ],
        'companyAuth'=>[
            'formHeader'=>[
                'account'=>[
                    'name'=>'账号',
                    'class'=>'',
                    'func'=>''
                ],
                'legal_person'=>[
                    'name'=>'法人',
                    'class'=>'',
                    'func'=>''
                ],
                'company_name'=>[
                    'name'=>'公司',
                    'class'=>'',
                    'func'=>''
                ],
                'status'=>[
                    'name'=>'认证状态',
                    'class'=>'status',
                    'func'=>''
                ],
                'examine'=>[
                    'name'=>'审核',
                    'class'=>'examine',
                    'func'=>'examineHtml'
                ],

                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[
                // 'account',
                // 'real_name',
                // 'group_id',
                // 'position',
                // 'remark',
            ],
            'formSearch'=>[
                // 'real_name'=>[
                //     'title'=>'姓名',
                //     'type'=>'text',
                //     'class'=>'',
                //     'id'=>'',
                //     'display'=>'',
                //     'default'=>'',
                //     'is_show'=>1
                // ],
                // 'group_id'=>[
                //     'title'=>'部门',
                //     'type'=>'select',
                //     'class'=>'select2',
                //     'id'=>'',
                //     'display'=>'',
                //     'default'=>'',
                //     'is_show'=>1
                // ],
            ],
            'detail'=>[],
        ],
        'factorApply'=>[
            'formHeader'=>[
                'uid'=>[
                    'name'=>'账号',
                    'class'=>'',
                    'func'=>'getAccountByUid'
                ],
                'company_id'=>[
                    'name'=>'账号性质',
                    'class'=>'',
                    'func'=>''
                ],
                'service_range_id'=>[
                    'name'=>'服务范围',
                    'class'=>'',
                    'func'=>'getServiceRangeName'
                ],
                'status'=>[
                    'name'=>'认证状态',
                    'class'=>'status',
                    'func'=>''
                ],
                'examine'=>[
                    'name'=>'审核',
                    'class'=>'examine',
                    'func'=>'examineHtml'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[
                // 'account',
                // 'real_name',
                // 'group_id',
                // 'position',
                // 'remark',
            ],
            'formSearch'=>[
                // 'real_name'=>[
                //     'title'=>'姓名',
                //     'type'=>'text',
                //     'class'=>'',
                //     'id'=>'',
                //     'display'=>'',
                //     'default'=>'',
                //     'is_show'=>1
                // ],
                // 'group_id'=>[
                //     'title'=>'部门',
                //     'type'=>'select',
                //     'class'=>'select2',
                //     'id'=>'',
                //     'display'=>'',
                //     'default'=>'',
                //     'is_show'=>1
                // ],
            ],
            'detail'=>[],
        ],
        'demandList'=>[
            'formHeader'=>[
                'demand_sn'=>[
                    'name'=>'需求单号',
                    'class'=>'',
                    'func'=>''
                ],
                'uid'=>[
                    'name'=>'账号',
                    'class'=>'',
                    'func'=>'getAccountByUid'
                ],
                'type'=>[
                    'name'=>'类型',
                    'class'=>'',
                    'func'=>''
                ],
                'demand_title'=>[
                    'name'=>'需求标题',
                    'class'=>'',
                    'func'=>''
                ],
                'status'=>[
                    'name'=>'认证状态',
                    'class'=>'status',
                    'func'=>''
                ],
                'service_range_id'=>[
                    'name'=>'服务范围',
                    'class'=>'',
                    'func'=>'getServiceRangeName'
                ],
                'examine'=>[
                    'name'=>'审核',
                    'class'=>'examine',
                    'func'=>'examineHtml'
                ],
                'show_priv'=>[
                    'name'=>'是否公开',
                    'class'=>'',
                    'func'=>''
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[
                // 'account',
                // 'real_name',
                // 'group_id',
                // 'position',
                // 'remark',
            ],
            'formSearch'=>[
                // 'real_name'=>[
                //     'title'=>'姓名',
                //     'type'=>'text',
                //     'class'=>'',
                //     'id'=>'',
                //     'display'=>'',
                //     'default'=>'',
                //     'is_show'=>1
                // ],
                // 'group_id'=>[
                //     'title'=>'部门',
                //     'type'=>'select',
                //     'class'=>'select2',
                //     'id'=>'',
                //     'display'=>'',
                //     'default'=>'',
                //     'is_show'=>1
                // ],
            ],
            'detail'=>[],
        ],
        //短信模板
        'smsTemplate'=>[
            'formHeader'=>[
                'template_id'=>[
                    'name'=>'模板id',
                    'class'=>'',
                    'func'=>''
                ],
                'template_name'=>[
                    'name'=>'模板名称',
                    'class'=>'',
                    'func'=>''
                ],
                'template_content'=>[
                    'name'=>'内容',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                 'template_id'=>[
                     'title'=>'模板名称',
                     'type'=>'select',
                     'class'=>'',
                     'id'=>'',
                     'display'=>'',
                     'default'=>'',
                     'is_show'=>1
                 ],
            ],
            'detail'=>[],
        ],
        //短信发送报表
        'smsSend'=>[
            'formHeader'=>[
                'mobile'=>[
                    'name'=>'手机号码',
                    'class'=>'',
                    'func'=>''
                ],
                'msg'=>[
                    'name'=>'内容',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'template_id'=>[
                    'name'=>'模板名称',
                    'class'=>'',
                    'func'=>''
                ],
                'status'=>[
                    'name'=>'状态',
                    'class'=>'',
                    'func'=>''
                ],
                'code'=>[
                    'name'=>'验证码',
                    'class'=>'',
                    'func'=>''
                ],
                'plan_time'=>[
                    'name'=>'计划发送时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'send_time'=>[
                    'name'=>'发送时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
//                'operation'=>[
//                    'name'=>'操作',
//                    'class'=>'',
//                    'func'=>'operation2'
//                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'template_id'=>[
                    'title'=>'模板名称',
                    'type'=>'select',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
                'status'=>[
                    'title'=>'状态',
                    'type'=>'select',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],

        //地区
        'district'=>[
            'formHeader'=>[
                'district_name'=>[
                    'name'=>'地区名称',
                    'class'=>'',
                    'func'=>''
                ],
                'district_type'=>[
                    'name'=>'地区类型',
                    'class'=>'',
                    'func'=>''
                ],
                'updated_at'=>[
                    'name'=>'更新时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'district_name'=>[
                    'title'=>'地区名称',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
                'district_type'=>[
                    'title'=>'地区类型',
                    'type'=>'select',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],

        //政策类型
        'policyType'=>[
            'formHeader'=>[
                'type_name'=>[
                    'name'=>'类型名称',
                    'class'=>'',
                    'func'=>''
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'updated_at'=>[
                    'name'=>'更新时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'type_name'=>[
                    'title'=>'类型名称',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],

        //政策范围
        'serviceRange'=>[
            'formHeader'=>[
                'range_name'=>[
                    'name'=>'范围名称',
                    'class'=>'',
                    'func'=>''
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'updated_at'=>[
                    'name'=>'更新时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'range_name'=>[
                    'title'=>'范围名称',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],

        //政策
        'policy'=>[
            'formHeader'=>[
                'id'=>[
                    'name'=>'ID',
                    'class'=>'',
                    'func'=>''
                ],
                'policy_name'=>[
                    'name'=>'项目名称',
                    'class'=>'',
                    'func'=>''
                ],
                'policy_name_web'=>[
                    'name'=>'原件名称',
                    'class'=>'',
                    'func'=>''
                ],
                'province'=>[
                    'name'=>'省',
                    'class'=>'',
                    'func'=>''
                ],
                'city'=>[
                    'name'=>'市',
                    'class'=>'',
                    'func'=>''
                ],
                'area'=>[
                    'name'=>'区/县',
                    'class'=>'',
                    'func'=>''
                ],
//                'policy_conditions'=>[
//                    'name'=>'条件',
//                    'class'=>'',
//                    'func'=>'preHtml'
//                ],
//                'policy_jiangli'=>[
//                    'name'=>'奖励',
//                    'class'=>'',
//                    'func'=>'preHtml'
//                ],
//                'policy_limits'=>[
//                    'name'=>'限制',
//                    'class'=>'',
//                    'func'=>'preHtml'
//                ],
                'start_day'=>[
                    'name'=>'申报开始时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'end_day'=>[
                    'name'=>'截止时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'web_url'=>[
                    'name'=>'原文地址',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'updated_at'=>[
                    'name'=>'更新时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'policy_name'=>[
                    'title'=>'项目名称',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],


        //订单管理
        'policyOrder'=>[
            'formHeader'=>[
                'order_sn'=>[
                    'name'=>'订单号',
                    'class'=>'',
                    'func'=>''
                ],
                'policy_id'=>[
                    'name'=>'政策',
                    'class'=>'',
                    'func'=>''
                ],
                'uid'=>[
                    'name'=>'账号',
                    'class'=>'',
                    'func'=>''
                ],
                'order_status'=>[
                    'name'=>'状态',
                    'class'=>'',
                    'func'=>''
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'updated_at'=>[
                    'name'=>'修改时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'order_sn'=>[
                    'title'=>'订单号',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
                'order_status'=>[
                    'title'=>'状态',
                    'type'=>'select',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],
        //需求服务范围名称
        'demandRange'=>[
            'formHeader'=>[
                'range_name'=>[
                    'name'=>'范围名称',
                    'class'=>'',
                    'func'=>''
                ],
                'remark'=>[
                    'name'=>'备注',
                    'class'=>'',
                    'func'=>'breviary'
                ],
                'updated_at'=>[
                    'name'=>'更新时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'created_at'=>[
                    'name'=>'创建时间',
                    'class'=>'',
                    'func'=>'timeFormat'
                ],
                'operation'=>[
                    'name'=>'操作',
                    'class'=>'',
                    'func'=>'operation2'
                ],
            ],
            'formFiled'=>[],
            'formSearch'=>[
                'range_name'=>[
                    'title'=>'范围名称',
                    'type'=>'text',
                    'class'=>'',
                    'id'=>'',
                    'display'=>'',
                    'default'=>'',
                    'is_show'=>1
                ],
            ],
            'detail'=>[],
        ],
    ],
];