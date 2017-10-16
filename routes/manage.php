<?php

/*
|--------------------------------------------------------------------------
| Manage Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * 总后台路由
 */

Route::group(['middleware'=>'manage'],function (){


    Route::group(['prefix' => 'Manage'], function() {
        /****登录、主页*****/
        Route::get('/',['uses'=>'AdminController@home']);//后台主页
        Route::get('/index',['uses'=>'AdminController@home', 'as'=>'manage.home']);//后台主页
        Route::any('/login',['uses'=>'AdminController@login', 'as'=>'login']);//登录
        Route::any('/logout',['uses'=>'AdminController@logout', 'as'=>'logout']);//登出
        Route::any('/lock',['uses'=>'AdminController@lock', 'as'=>'lock']);//锁定


        Route::any('Home/index',['uses'=>'HomeController@index', 'as'=>'manage.home.index']);//后台默认页面

        /****菜单*****/
        Route::any('/Menu/index',['uses'=>'MenuController@index', 'as'=>'menu.index']);//菜单列表
        Route::any('/Menu/edit/{id}',['uses'=>'MenuController@edit', 'as'=>'menu.edit']);//编辑菜单
        Route::any('/Menu/add',['uses'=>'MenuController@add', 'as'=>'menu.add']);//添加菜单
        Route::any('/Menu/del/{id}',['uses'=>'MenuController@del', 'as'=>'menu.del']);//删除菜单
        Route::any('/Menu/ajaxGetMenuByPid',['uses'=>'MenuController@ajaxGetMenuByPid']);//ajax获取下级菜单
        Route::any('/Menu/ajaxLock',['uses'=>'MenuController@ajaxLock']);//ajax修改菜单状态

        /****部门管理*****/
        Route::any('/AdminGroup/index',['uses'=>'AdminGroupController@index', 'as'=>'adminGroup.index']);//部门列表
        Route::any('/AdminGroup/edit/{id}',['uses'=>'AdminGroupController@edit', 'as'=>'adminGroup.edit']);//编辑部门
        Route::any('/AdminGroup/add',['uses'=>'AdminGroupController@add', 'as'=>'adminGroup.add']);//添加部门
        Route::any('/AdminGroup/del/{id}',['uses'=>'AdminGroupController@del', 'as'=>'adminGroup.del']);//删除部门
        Route::any('/AdminGroup/ajaxGetGroupByPid',['uses'=>'AdminGroupController@ajaxGetGroupByPid']);//ajax获取下级部门
        Route::any('/AdminGroup/ajaxLock',['uses'=>'AdminGroupController@ajaxLock']);//ajax修改部门状态

        /****权限管理*****/
        Route::any('/Auth/index',['uses'=>'AuthController@index']);//部门权限
        Route::any('/Auth/edit/{id}',['uses'=>'AuthController@edit']);//部门权限编辑

        /****人员管理*****/
        Route::any('/Admin/index',['uses'=>'AdminController@index', 'as'=>'admin.index']);//人员列表
        Route::any('/Admin/edit/{id}',['uses'=>'AdminController@edit', 'as'=>'admin.edit']);//编辑人员
        Route::any('/Admin/add',['uses'=>'AdminController@add', 'as'=>'admin.add']);//添加人员
        Route::any('/Admin/del/{id}',['uses'=>'AdminController@del', 'as'=>'admin.del']);//删除人员
        Route::any('/Admin/ajaxLock',['uses'=>'AdminController@ajaxLock']);//ajax修改人员状态
        Route::any('/Admin/changePwd',['uses'=>'AdminController@changePwd','as'=>'admin.changePwd']);//ajax修改人员状态

        
        
        /****客户管理*****/
        Route::any('/Account/index',['uses'=>'AccountController@index', 'as'=>'account.index']);
        Route::any('/Account/ajaxLock',['uses'=>'AccountController@ajaxLock', 'as'=>'account.ajaxLock']);
        Route::any('/Account/del/{id}',['uses'=>'AccountController@del', 'as'=>'account.del']);
        Route::any('/Account/edit/{id}',['uses'=>'AccountController@edit', 'as'=>'account.edit']);
        Route::any('/Account/add',['uses'=>'AccountController@add', 'as'=>'account.add']);//

        /****个人认证*****/
        Route::any('/PersonAuth/index',['uses'=>'PersonAuthController@index', 'as'=>'personAuth.index']);
        Route::any('/PersonAuth/examine',['uses'=>'PersonAuthController@examine', 'as'=>'personAuth.examine']);

        /****企业认证*****/
        Route::any('/CompanyAuth/index',['uses'=>'CompanyAuthController@index', 'as'=>'companyAuth.index']);
        Route::any('/CompanyAuth/examine',['uses'=>'CompanyAuthController@examine', 'as'=>'companyAuth.examine']);

        /****需求服务商管理*****/
        Route::any('/FactorApply/index',['uses'=>'FactorApplyController@index', 'as'=>'factorApply.index']);
        Route::any('/FactorApply/examine',['uses'=>'FactorApplyController@examine', 'as'=>'factorApply.examine']);

        /****需求管理*****/
        Route::any('/DemandList/index',['uses'=>'DemandListController@index', 'as'=>'demandList.index']);
        Route::any('/DemandList/edit/{id}',['uses'=>'DemandListController@edit', 'as'=>'demandList.edit']);
        Route::any('/DemandList/examine',['uses'=>'DemandListController@examine', 'as'=>'demandList.examine']);

        // Route::any('/Account/add',['uses'=>'AccountController@add', 'as'=>'account.add']);//添加客户
        // Route::any('/Account/del/{id}',['uses'=>'AccountController@del', 'as'=>'account.del']);//删除客户
        // Route::any('/Account/ajaxLock',['uses'=>'AccountController@ajaxLock']);//ajax修改状态
        // Route::any('/Account/getDetail',['uses'=>'AccountController@getDetail']);//获取个人介绍
        // Route::any('/Account/export',['uses'=>'AccountController@export']);//导出

        // Route::any('/PersonAuth/index',['uses'=>'PersonAuthController@index', 'as'=>'personAuth.index']);//客户列表
        // Route::any('/CompanyAuth/index',['uses'=>'CompanyAuthController@index', 'as'=>'companyAuth.index']);//编辑客户
        



        
        /****工具*****/
        Route::any('/Tools/upload',['uses'=>'ToolsController@upload', 'as'=>'manage.tools.upload']);//上传文件
        Route::any('/Tools/uploadImg',['uses'=>'ToolsController@uploadImg', 'as'=>'manage.tools.uploadImg']);//上传图片
        Route::any('/Tools/getDataList',['uses'=>'ToolsController@getDataList', 'as'=>'manage.tools.getDataList']);//获取列表数据
        Route::any('/Tools/getDetail',['uses'=>'ToolsController@getDetail', 'as'=>'manage.tools.getDetail']);//获取详细数据
        Route::any('/Tools/ajaxPutObject',['uses'=>'ToolsController@ajaxPutObject', 'as'=>'manage.tools.ajaxPutObject']);//异步添加数据

        
       
        /****文档管理*****/
        Route::any('/Document/index',['uses'=>'DocumentController@index', 'as'=>'manage.document.index']);//列表
        Route::any('/Document/edit/{id}',['uses'=>'DocumentController@edit', 'as'=>'manage.document.edit']);//编辑
        Route::any('/Document/add',['uses'=>'DocumentController@add', 'as'=>'manage.ad.add']);//添加
        Route::any('/Document/del/{id}',['uses'=>'DocumentController@del', 'as'=>'manage.document.del']);//删除
        Route::any('/Document/ajaxGetDetail',['uses'=>'DocumentController@ajaxGetDetail', 'as'=>'manage.document.ajaxGetDetail']);//获取内容
        Route::any('/Document/ajaxLock',['uses'=>'DocumentController@ajaxLock', 'as'=>'manage.document.ajaxLock']);//ajax修改状态

        

        /****内部管理角色分配*****/
        Route::any('/AdminRole/index',['uses'=>'AdminRoleController@index', 'as'=>'manage.adminRole.index']);
        Route::any('/AdminRole/edit/{type}',['uses'=>'AdminRoleController@edit', 'as'=>'manage.adminRole.edit']);


        /****系统日志*****/
        Route::any('/SystemLog/index',['uses'=>'SystemLogController@index', 'as'=>'manage.systemLog.index']);//列表


        /****短信模块*****/
        Route::any('/SmsTemplate/index',['uses'=>'SmsTemplateController@index', 'as'=>'manage.smsTemplate.index']);
        Route::any('/SmsTemplate/add',['uses'=>'SmsTemplateController@add', 'as'=>'manage.smsTemplate.add']);
        Route::any('/SmsTemplate/edit/{id}',['uses'=>'SmsTemplateController@edit', 'as'=>'manage.smsTemplate.edit']);
        Route::any('/SmsTemplate/del/{id}',['uses'=>'SmsTemplateController@del', 'as'=>'manage.smsTemplate.del']);

        Route::any('/SmsSend/index',['uses'=>'SmsSendController@index', 'as'=>'manage.smsSend.index']);//列表

        /****政策模块*****/
        Route::any('/District/index',['uses'=>'DistrictController@index', 'as'=>'manage.district.index']);
        Route::any('/District/add',['uses'=>'DistrictController@add', 'as'=>'manage.district.add']);
        Route::any('/District/edit/{id}',['uses'=>'DistrictController@edit', 'as'=>'manage.district.edit']);
        Route::any('/District/del/{id}',['uses'=>'DistrictController@del', 'as'=>'manage.district.del']);

        Route::any('/PolicyType/index',['uses'=>'PolicyTypeController@index', 'as'=>'manage.policyType.index']);
        Route::any('/PolicyType/add',['uses'=>'PolicyTypeController@add', 'as'=>'manage.policyType.add']);
        Route::any('/PolicyType/edit/{id}',['uses'=>'PolicyTypeController@edit', 'as'=>'manage.policyType.edit']);
        Route::any('/PolicyType/del/{id}',['uses'=>'PolicyTypeController@del', 'as'=>'manage.policyType.del']);

        Route::any('/Policy/index',['uses'=>'PolicyController@index', 'as'=>'manage.policy.index']);
        Route::any('/Policy/add',['uses'=>'PolicyController@add', 'as'=>'manage.policy.add']);
        Route::any('/Policy/edit/{id}',['uses'=>'PolicyController@edit', 'as'=>'manage.policy.edit']);
        Route::any('/Policy/del/{id}',['uses'=>'PolicyController@del', 'as'=>'manage.policy.del']);

        Route::any('/PolicyOrder/index',['uses'=>'PolicyOrderController@index', 'as'=>'manage.policyOrder.index']);
        Route::any('/PolicyOrder/del/{id}',['uses'=>'PolicyOrderController@del', 'as'=>'manage.policyOrder.del']);

        /****需求服务范围*****/
        Route::any('/DemandRange/index',['uses'=>'DemandRangeController@index', 'as'=>'manage.demandRange.index']);
        Route::any('/DemandRange/add',['uses'=>'DemandRangeController@add', 'as'=>'manage.demandRange.add']);
        Route::any('/DemandRange/edit/{id}',['uses'=>'DemandRangeController@edit', 'as'=>'manage.demandRange.edit']);
        Route::any('/DemandRange/del/{id}',['uses'=>'DemandRangeController@del', 'as'=>'manage.demandRange.del']);
        /****需求订单*****/
        Route::any('/DemandOrder/index',['uses'=>'DemandOrderController@index', 'as'=>'manage.demandOrder.index']);
        Route::any('/DemandOrder/add',['uses'=>'DemandOrderController@add', 'as'=>'manage.demandOrder.add']);
        Route::any('/DemandOrder/edit/{id}',['uses'=>'DemandOrderController@edit', 'as'=>'manage.demandOrder.edit']);
        Route::any('/DemandOrder/del/{id}',['uses'=>'DemandOrderController@del', 'as'=>'manage.demandOrder.del']);
    });

});
