<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-11-29
 * Time: 10:19
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Manage\SystemLog;
use App\Tools\Tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class BaseController extends Controller
{
    //模板目录
    public $viewPath = 'manage';

    public $viewData = [];

    //分页条数
    public $row = 12;

    //金额单位换算（10000代表：万）
    public $unit = 10000;

    //权限值
    public $authArr = [
        'index',
        'add',
        'edit',
        'del',
        'modify_state',
        'import',
        'export',
        'examine',
        'release',
    ];


    //操作选项
    public $option=[];

    //页面可用标签参数
    public $myTags = '';

    /****表单配置*****/
    //表头[$k=>$v]
    public $formHeader = [];
    //显示字段
    public $formFiled = [];
    //搜索内容[name=>[type=>*,display=>*,default=>*]]
    public $formSearch = [];
    //页面配置文件
    public $layoutConfig = '';

    public $whether = [
        'none'=>'不限',
        0=>'否',
        1=>'是',
    ];
    public $lockArr = [
        0=>'启用',
        1=>'锁定',
    ];

    public $sexArr = [
        'none'=>'不限',
        '0'=>'未知',
        '1'=>'男',
        '2'=>'女',
    ];

    /**
     * BaseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request, $layout='')
    {


        if (isset($_SESSION['admin_type']) && $_SESSION['admin_type']==1){
            //超级管理员
            $option = $this->authArr;
        }else{
            $uri = explode('/',$request->getPathInfo());
            $option = [];
            if (isset($_SESSION['admin_auths'])){
                $key = isset($uri[2]) ? $uri[2] : NULL;
                if (isset($_SESSION['admin_auths'][$key])){
                    foreach ($this->authArr as $v){
                        if (in_array($v,$_SESSION['admin_auths'][$key])){
                            $option[] = $v;
                        }
                    }
                    $action = isset($uri[3]) ? $uri[3] : NULL;
                    if (in_array($action,['index','add','edit','del','examine','import','export'])){
                        if (!in_array($action,$_SESSION['admin_auths'][$key])){
                            echo '权限不足';die;
                        }
                    }
                }

            }
        }
        $optionConfig = Config::get('option');
        $option = isset($optionConfig['Manage'][$layout]) ? array_intersect($option, $optionConfig['Manage'][$layout]) : [];

        $this->option = $option;
        $this->viewData['option'] = $option;

        $this->formConfigure($layout);
    }

    //注入变量
    public function myView($blade,$data = []){

        if (count($this->viewData)){
            foreach ($this->viewData as $k=>$v) {
                $data[$k] = $v;
            }
        }
        return view($this->viewPath.'.'.$blade, $data);
    }

    //模板配置
    public function formConfigure($layout){
        $formConfig = Config::get('form');
        $this->layoutConfig = isset($formConfig['Manage'][$layout]) ? $formConfig['Manage'][$layout] : [];
    }

    public function json($status=1,$data=[],$myMsg=''){
        $msgArr = Config::get('msg');
        if ($myMsg != ''){
            $msg = $myMsg;
        }else{
            $msg = isset($msgArr[$status]) ? $msgArr[$status] : '未知错误';
        }

        $rData = [
            'status'=>$status,
            'msg'=>$msg,
            'data'=>$data
        ];
        return response()->json($rData);
    }


}