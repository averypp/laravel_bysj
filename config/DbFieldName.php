<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 1/22/2017
 * Time: 11:31 AM
 */

return [
    //管理后台
    'Manage' => [
        'Template' => [
            'id' => [
                'name' => 'ID',
                'class' => '',
                'func' => ''
            ],
            'account' => [
                'name' => '账号',
                'class' => '',
                'func' => ''
            ],
            'real_name' => [
                'name' => '姓名',
                'class' => '',
                'func' => ''
            ],
            'sex' => [
                'name' => '性别',
                'class' => '',
                'func' => ''
            ],
            'mobile' => [
                'name' => '手机号码',
                'class' => '',
                'func' => ''
            ],
            'email' => [
                'name' => '邮箱',
                'class' => '',
                'func' => ''
            ],
            'head_img' => [
                'name' => '头像',
                'class' => '',
                'func' => 'imgHtml'
            ],
            'company_id' => [
                'name' => '公司',
                'class' => '',
                'func' => 'getCompanyName'
            ],
            'position_ids' => [
                'name' => '职位',
                'class' => '',
                'func' => 'getPositionName'
            ],
            'age' => [
                'name' => '年龄',
                'class' => '',
                'func' => ''
            ],
            'identification' => [
                'name' => '身份证号码',
                'class' => '',
                'func' => ''
            ],
            'province' => [
                'name' => '省份',
                'class' => '',
                'func' => ''
            ],
            'city' => [
                'name' => '城市',
                'class' => '',
                'func' => ''
            ],
            'area' => [
                'name' => '地区',
                'class' => '',
                'func' => ''
            ],
            'business_card' => [
                'name' => '名片',
                'class' => '',
                'func' => 'imgHtml'
            ],
            'industry_age' => [
                'name' => '业界年限',
                'class' => '',
                'func' => ''
            ],
            'educational_background' => [
                'name' => '教育背景',
                'class' => '',
                'func' => ''
            ],
            'education' => [
                'name' => '学历',
                'class' => '',
                'func' => ''
            ],
            'positional_titles' => [
                'name' => '职称',
                'class' => '',
                'func' => ''
            ],
            'rank' => [
                'name' => '头衔',
                'class' => '',
                'func' => ''
            ],
            'record' => [
                'name' => '个人履历',
                'class' => '',
                'func' => ''
            ],
            'commendation' => [
                'name' => '荣誉嘉奖',
                'class' => '',
                'func' => ''
            ],
            'cooperate_company_ids' => [
                'name' => '已合作企业',
                'class' => '',
                'func' => 'getCompanyName'
            ],
            'extend_company_ids' => [
                'name' => '上下游企业',
                'class' => '',
                'func' => 'getCompanyName'
            ],
//            'unionid' => [
//                'name' => '微信unionid',
//                'class' => '',
//                'func' => ''
//            ],
//            'openid' => [
//                'name' => '微信openid',
//                'class' => '',
//                'func' => ''
//            ],
//            'type' => [
//                'name' => '类型',
//                'class' => '',
//                'func' => ''
//            ],
            'is_investor' => [
                'name' => '是否是投资人',
                'class' => '',
                'func' => 'getWhether'
            ],
            'tag_ids' => [
                'name' => '标签',
                'class' => '',
                'func' => 'getTagsName'
            ],
            'is_lock' => [
                'name' => '状态',
                'class' => '',
                'func' => ''
            ],
            'remark' => [
                'name' => '备注',
                'class' => '',
                'func' => ''
            ],
            'updated_at' => [
                'name' => '更新时间',
                'class' => '',
                'func' => 'timeFormat'
            ],
            'created_at' => [
                'name' => '创建时间',
                'class' => '',
                'func' => 'timeFormat'
            ],
            'introduction' => [
                'name' => '个人介绍',
                'class' => '',
                'func' => ''
            ],
        ],

    ],

];