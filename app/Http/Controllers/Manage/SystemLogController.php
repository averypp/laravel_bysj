<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-05
 * Time: 16:13
 */
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Model\Manage\Admin;
use App\Model\Manage\AdminGroup;
use App\Model\Manage\SystemLog;
use App\Tools\Html;
use Illuminate\Http\Request;


class SystemLogController extends BaseController
{



    //模块对应名称
    public $moduleNameArr = [
        'none'=>'不限',
        'Manage'=>'总平台',
        'Customer'=>'客户平台',
        'Api'=>'接口',
    ];

    //控制器对应名称
    public $controllerNameArr = [
        'none'=>'不限',
        'index'=>'后台主页',
        'lock'=>'导出',
        'Home'=>'主页',
        'login'=>'登陆',
        'logout'=>'登出',
        'Menu'=>'菜单',
        'AdminGroup'=>'部门管理',
        'Auth'=>'权限管理',
        'Admin'=>'人员管理',
        'Tag'=>'标签管理',
        'TagCategory'=>'标签分类管理',
        'Company'=>'公司管理',
        'Industry'=>'行业管理',
        'Customer'=>'客户管理',
        'Project'=>'项目管理',
        'ProjectBase'=>'项目基本信息',
        'ProjectFinancing'=>'项目融资信息',
        'ProjectEstimate'=>'项目评估信息',
        'ProjectDynamic'=>'项目动态',
        'ProjectDemand'=>'项目需求',
        'Fund'=>'基金管理',
        'FundDynamic'=>'基金动态',
        'Tools'=>'工具',
        'Question'=>'调查问卷',
        'Position'=>'职位管理',
        'News'=>'资讯管理',
        'Activity'=>'活动管理',
        'SmsTemplate'=>'短信模板管理',
        'Ad'=>'广告管理',
        'Document'=>'文档管理',
        'QuestionCategory'=>'问卷分类管理',
        'ActivitySignUp'=>'活动报名',
        'LeaguerApplication'=>'零壹汇会员申请管理',
        'SmsLog'=>'短信报表',
        'AdminRole'=>'内部管理角色分配',
        'SystemLog'=>'系统日志',
        'QuestionCheck'=>'投资人问卷审核',
    ];
    //方法对应名称
    public $actionNameArr = [
        'none'=>'不限',
        'index'=>'浏览',
        'add'=>'增加',
        'edit'=>'修改',
        'del'=>'删除',
        'ajaxLock'=>'修改状态',
        'import'=>'导入',
        'export'=>'导出',
        'examine'=>'审核',
        'changePwd'=>'修改密码',
        'getDetail'=>'获取详情',
        'upload'=>'上传文件',
        'uploadImg'=>'上传图片',
        'ajaxPutObject'=>'异步添加数据',
    ];

    public $layout = 'systemLog';

    public function __construct(Request $request)
    {
        parent::__construct($request, $this->layout);
    }

    //列表
    public function index(Request $request){
        $search = $request->input();


        $allGroup = AdminGroup::where(['is_del'=>0])->select('id', 'group_name')->get();
        $groupName = ['0'=>'未定义'];
        if ($allGroup){
            foreach ($allGroup as $v){
                $groupName[$v->id] = $v->group_name;
            }
        }

        $admins = Admin::where(['is_del'=>0])->select(['id','account','real_name','group_id'])->get();
        $tmp = [
            0=>[
                'id'=>$v->id,
                'account'=>'未登录',
                'real_name'=>'未登录',
                'groupName'=>'未登录',
            ]
        ];
        if (count($admins)){
            foreach ($admins as $v){
                $tmp[$v->id] = [
                    'id'=>$v->id,
                    'account'=>$v->account,
                    'real_name'=>$v->real_name,
                    'groupName'=>isset($groupName[$v->group_id]) ? $groupName[$v->group_id] : '',
                ];
            }
        }
        $admins = $tmp;
        $systemLogs = $this->getResult($search, $this->row);

        if (count($systemLogs)){
            foreach ($systemLogs as &$v){
                $v->adminAccount = isset($admins[$v->admin_id]) ? $admins[$v->admin_id]['account'] : '已删除';
                $v->adminName = isset($admins[$v->admin_id]) ? $admins[$v->admin_id]['real_name'] : '已删除';
                $v->adminGroup = isset($admins[$v->admin_id]) ? $admins[$v->admin_id]['groupName'] : '已删除';
            }
        }
        $selectData = [
            'module'=>$this->moduleNameArr,
            'controller'=>$this->controllerNameArr,
            'action'=>$this->actionNameArr,
        ];
        $constant = [
            'module'=>$this->moduleNameArr,
            'controller'=>$this->controllerNameArr,
            'action'=>$this->actionNameArr,
        ];
        $form = Html::formCreate($systemLogs,$search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView('systemLog.index',[
            'form'=>$form,
            'search'=>$search,
        ]);
    }

    public function getResult($search, $row=0){
        foreach ($search as $k=>$v){
            if (!is_array($search[$k]) && ($search[$k] === '' || $search[$k] === 'none')){
                unset($search[$k]);
            }else{
                if (!is_array($search[$k])){
                    $search[$k] = trim($v);
                }
            }
        }

        $obj = SystemLog::orderBy('id', 'desc');

        if (isset($search['module'])){
            $obj = $obj->where('module',$search['module']);
        }
        if (isset($search['controller'])){
            $obj = $obj->where('controller',$search['controller']);
        }
        if (isset($search['action'])){
            $obj = $obj->where('action',$search['action']);
        }

        if (isset($search['orderby']) && isset($search['sort'])){
            $sort = intval($search['sort'])==1 ? 'asc' : 'desc';
            $obj = $obj->orderBy(trim($search['orderby']), $sort);
        }else{
            $obj = $obj->orderBy('id', 'desc');
        }

        if ($row == 0){
            $obj = $obj->get();
        }else{
            $obj = $obj->paginate($row);
        }
        return $obj;
    }


}