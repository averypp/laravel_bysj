<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-02
 * Time: 10:22
 */

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Model\Manage\AdminGroup;
use App\Tools\DataHandle;
use App\Tools\Html;
use Illuminate\Http\Request;

class AdminGroupController extends BaseController
{

    public $layout = 'adminGroup';

    public function __construct(Request $request)
    {
        parent::__construct($request, $this->layout);
    }

    //部门列表
    public function index(Request $request){
        $search = $request->input();


        $allGroup = AdminGroup::where(['is_del'=>0])->select('id', 'group_name')->get();
        $groupName = ['none'=>'不限','0'=>'顶级管理'];
        if ($allGroup){
            foreach ($allGroup as $v){
                $groupName[$v->id] = $v->group_name;
            }
        }

        $groups = $this->getResult($search, $this->row);

        if (count($groups)){
            foreach ($groups as &$v){
                $v->pid = $groupName[$v->pid];
                $v->operation = [
                    'module'=>'AdminGroup',
                    'obj'=>$v,
                    'option'=>$this->option,
                    'filtration'=>[],
                ];
            }
        }
        $selectData = [
            'pid'=>$groupName
        ];
        $constant = [
            'is_lock'=>$this->lockArr,
        ];
        $form = Html::formCreate($groups,$search, $selectData, $constant, $this->option, $this->layoutConfig);
        return $this->myView('adminGroup.index',[
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

        $obj = AdminGroup::where(['is_del'=>0]);

        if (isset($search['pid'])){
            $obj = $obj->where('pid',$search['pid']);
        }
        if (isset($search['group_name'])){
            $obj = $obj->where('group_name','like','%'.$search['group_name'].'%');
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

    //新增部门
    public function add(Request $request){

        if ($request->isMethod('POST')){

            $this->validate($request,[
                'Group.group_name'=>'required|min:2|max:255|unique:admin_group,group_name,NULL,id,is_del,0',
                'Group.pid'=>'required',
                'Group.is_lock'=>'required',
            ],[
                'required'=>':attribute 为必填项',
                'unique'=>':attribute 已存在',
                'min'=>':attribute 长度不能小于2',
                'max'=>':attribute 长度不能大于20',
            ],[
                'Group.group_name'=>'部门名称',
                'Group.pid'=>'上级部门',
                'Group.is_lock'=>'是否锁定',
            ]);

            $data = $request->input('Group');
            $data['parent_ids'] = isset($data['parent_ids']) ? DataHandle::implodeWithInteger(',',array_reverse($data['parent_ids'])) : 0;

            if (AdminGroup::create($data)){
                return redirect('Manage/AdminGroup/index')->with('success','添加成功！');
            }else{

                return redirect()->back()->with('error','添加失败，请重试！');
            }
        }

        $group = new AdminGroup();
        $allGroups = AdminGroup::where(['pid'=>0,'is_del'=>0])->select(['id','group_name','pid','parent_ids'])->get();
        $allGroups = [0=>$allGroups];

        $group->parent_ids = explode(',',$group->parent_ids);

        return $this->myView('adminGroup.add',[
            'allGroups'=>$allGroups,
            'group'=>$group
        ]);
    }

    //修改部门
    public function edit(Request $request, $id){
        $group = AdminGroup::find($id);

        if($group){
            if ($request->isMethod('POST')){
                $this->validate($request,[
                    'Group.group_name'=>'required|min:2|max:255|unique:admin_group,group_name,'.$id.',id,is_del,0',
                    'Group.pid'=>'required',
                    'Group.is_lock'=>'required',
                ],[
                    'required'=>':attribute 为必填项',
                    'unique'=>':attribute 已存在',
                    'min'=>':attribute 长度不能小于2',
                    'max'=>':attribute 长度不能大于20',
                ],[
                    'Group.group_name'=>'部门名称',
                    'Group.pid'=>'上级部门',
                    'Group.is_lock'=>'是否锁定',
                ]);

                $data = $request->input('Group');
                $data['parent_ids'] = isset($data['parent_ids']) ? DataHandle::implodeWithInteger(',',array_reverse($data['parent_ids'])) : 0;

                foreach ($data as $k=>$v){
                    $group->$k = trim($v);
                }

                if ($group->save()){
                    return redirect('Manage/AdminGroup/index')->with('success','修改成功！');
                }else{
                    return redirect()->back()->with('error','修改失败，请重试！');
                }
            }
        }else{
            return redirect('Manage/AdminGroup/index')->with('error','信息错误！');
        }


        $group->parent_ids = explode(',',$group->parent_ids);

        $parent = $group->parent_ids;
        $parent[0] = 0;
        $allGroups = AdminGroup::where(['is_del'=>0])
            ->whereIn('pid', $parent)
            ->select(['id','group_name','pid','parent_ids'])->get();
        $tmp = [];

        if (count($allGroups)){
            foreach ($allGroups as $val){
                if (!isset($tmp[$val->pid])){
                    $tmp[$val->pid] = [];
                }
                $tmp[$val->pid][] = [
                    'id'=>$val->id,
                    'group_name'=>$val->group_name,
                    'pid'=>$val->pid,
                    'parent_ids'=>$val->parent_ids,
                ];
            }
        }
        $allGroups = $tmp;
        return $this->myView('adminGroup.edit',[
            'allGroups'=>$allGroups,
            'group'=>$group
        ]);
    }

    //删除部门
    public function del($id) {

        $group = AdminGroup::find($id);

        if ($group){
            $group->is_del = 1;
            if ($group->save()){
                return redirect('Manage/AdminGroup/index')->with('success','删除成功！');
            }else{
                return redirect()->back()->with('error','删除失败，请重试！');
            }
        }else{
            return redirect('Manage/AdminGroup/index')->with('error','信息错误！');
        }

    }

    //获取下级部门
    public function ajaxGetGroupByPid(Request $request){
        if ($request->ajax()){

            $pid = $request->input('pid');
            if ($pid > 0){
                $groups = AdminGroup::where(['pid'=>$pid,'is_del'=>0])->select('id', 'group_name')->get();
                return $this->json(1,$groups);
            }else {
                return $this->json(9002);
            }

        }else {
            return $this->json(9003);
        }

    }


    public function ajaxLock(Request $request){
        if ($request->ajax()){

            $id = $request->input('id');
            $isLock = $request->input('is_lock');
            $group = AdminGroup::find($id);
            if ($group){
                $group->is_lock = intval($isLock);
                if ($group->save()){
                    return $this->json(1);
                }else{
                    return $this->json(9000);
                }

            }else {
                return $this->json(9002);
            }

        }else {
            return $this->json(9003);
        }
    }


}