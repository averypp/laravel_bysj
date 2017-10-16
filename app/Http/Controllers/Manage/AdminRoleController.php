<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 12/19/2016
 * Time: 10:51 AM
 */


namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Model\Manage\Admin;
use App\Model\Manage\AdminRole;
use App\Tools\DataHandle;
use App\Tools\Html;
use Illuminate\Http\Request;

class AdminRoleController extends BaseController
{
    public $typeArr = [
        0=>'未定',
//        1=>'内部投资人',
        2=>'零壹小秘',
        3=>'投资人认证管理',
        4=>'零壹汇会员认证管理',
        5=>'课程管理',
        6=>'活动管理',
    ];
    public $layout = 'adminRole';

    public function __construct(Request $request)
    {
        parent::__construct($request, $this->layout);
        $this->_init();
    }

    public function _init(){
        $viewData = [
            'typeArr'=>$this->typeArr,
        ];
        $this->viewData = array_merge ($this->viewData,$viewData);
    }

    //列表
    public function index(Request $request){

        $role = AdminRole::where(['is_del'=>0])->get();

        $adminName = [];
        $admins = Admin::where(['is_del'=>0])->select(['id','real_name'])->get();
        if (count($admins)){
            foreach ($admins as $v){
                $adminName[$v->id] = $v->real_name;
            }
        }

        $tmp = [];
        if (count($role)){
            foreach ($role as &$v){
                $adminIds = explode(',',$v->admin_ids);
                $adminList = [];
                if (count($adminIds)){
                    foreach ($adminIds as $id){
                        if (isset($adminName[$id])){
                            $adminList[] = $adminName[$id];
                        }
                    }
                }
                $tmp[$v->type] = implode(',',$adminList);
            }
        }
        $role = $tmp;
        foreach ($this->typeArr as $k=>$v){
            if ($k != 0){
                $adminRole[] = (object)[
                    'id'=>$k,
                    'type'=>$v,
                    'admin_ids'=>isset($role[$k]) ? $role[$k] : '',
                ];
            }

        }
        $adminRole = (object)$adminRole;

        foreach ($adminRole as $k=>$v){
            $v->operation = [
                'module'=>'AdminRole',
                'obj'=>$v,
                'option'=>$this->option,
                'filtration'=>[],
            ];
        }
        $selectData = [];
        $constant = [
            'type'=>$this->typeArr,
        ];
        $form = Html::formCreate($adminRole,[], $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView('adminRole.index',[
            'form'=>$form,
            'search'=>[],
        ]);
    }

    //编辑
    public function edit(Request $request, $type){

        if (!isset($this->typeArr[$type])){
            return redirect('Manage/AdminRole/index')->with('error','信息错误！');
        }

        if ($request->isMethod('POST')) {
            $data = $request->input('AdminRole');
            $data['admin_ids'] = isset($data['admin_ids']) ? DataHandle::implodeWithInteger(',',$data['admin_ids']) : '';

            AdminRole::where(['type'=>$data['type'],'is_del'=>0])->update(['is_del'=>1]);
            if (AdminRole::create($data)){
                return redirect('Manage/AdminRole/index')->with('success','添加成功！');
            }else{
                return redirect()->back()->with('error','添加失败，请重试！');
            }
        }


        $adminName = [];
        $admins = Admin::where(['is_del'=>0])->select(['id','real_name'])->get();
        if (count($admins)){
            foreach ($admins as $v){
                $adminName[$v->id] = $v->real_name;
            }
        }

        $adminRole = AdminRole::where(['type'=>$type,'is_del'=>0])->first();
        if (count($adminRole) <= 0){
            $adminRole = New AdminRole();
        }
        if ($adminRole->admin_ids){
            $adminRole->admin_ids = explode(',',$adminRole->admin_ids);
        }else{
            $adminRole->admin_ids = [];
        }

        return $this->myView('adminRole.edit',[
            'adminRole'=>$adminRole,
            'adminName'=>$adminName,
            'type'=>$type,
        ]);
    }

}