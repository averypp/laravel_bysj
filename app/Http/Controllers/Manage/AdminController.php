<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-11-28
 * Time: 16:34
 */
namespace App\Http\Controllers\Manage;


use App\Http\Controllers\BaseController;
use App\Model\Manage\Auth;
use App\Model\Manage\Menu;
use App\Tools\Html;
use Illuminate\Http\Request;
use App\Model\Manage\Admin;
use App\Model\Manage\AdminGroup;

class AdminController extends BaseController
{

    public $layout = 'admin';

    public function __construct(Request $request)
    {
        parent::__construct($request, $this->layout);
    }

    //主页面
    public function home(Request $request){
        $groupId = $request->session()->get('admin_group_id');
        $groupInfo = AdminGroup::find($groupId);
        if (!$groupInfo || $groupInfo->is_lock==1 || $groupInfo->is_del==1){
            $request->session()->forget('admin_id');
            $request->session()->forget('admin_name');
            $request->session()->forget('admin_group_id');
            $request->session()->forget('admin_auths');
            return redirect('Manage/login')->with('error','您所在的组已被禁用，请联系系统管理员！');
        }

        $auths = [];//写入session，用于判断是否越权
        $moduleArr = [];//用于提取菜单
        $menus = Menu::where(['is_lock'=>0, 'is_del'=>0])
            ->orderBy('priority', 'asc')
            ->get();

        if ($groupInfo->type != 1){
            $_SESSION['admin_type'] = 0;
            //普通管理员
            $auth = Auth::where(['group_id'=>$groupInfo->id])->get();

            if (count($auth)){
                foreach ($auth as $v){
                    foreach ($this->authArr as $authV){
                        if ($v->$authV == 1){
                            $auths[$v->module][] = $authV;
                        }
                    }
                    if ($v->index == 1){
                        $moduleArr[] = $v->module;
                    }

                }
            }
        }else{
            $_SESSION['admin_type'] = 1;
        }

        $menuInfo = [];
        if ($menus){
            foreach ($menus as $key=>$val){
                $url = ($val->module&&$val->action&&$val->action!='#') ? 'Manage/'.$val->module.'/'.$val->action : '#';

                if ($groupInfo->type==1 || ($url=='#' || in_array($val->module,$moduleArr))){
                    $menuInfo[$val->pid][] = array(
                        'id'=>$val->id,
                        'pid'=>$val->pid,
                        'name'=>$val->name,
                        'url'=>$url,
                        'icon'=>$val->icon,
                    );
                }

            }
        }
        //权限写入session
        $_SESSION['admin_auths'] = $auths;
//        $request->session()->put('admin_auths',$auths);
        foreach ($menuInfo[0] as $k=>$v){
            if (!isset($menuInfo[$v['id']]) || count($menuInfo[$v['id']]) <= 0){
                unset($menuInfo[0][$k]);
            }
        }

        $menuHtml = $this->_recursionMenu($menuInfo,0,'',0);
        return $this->myView('base.base',[
            'menuHtml'=>$menuHtml
        ]);
    }

    //登录
    public function login(Request $request){
        if ($request->isMethod('POST')){

            $data = $request->input('Admin');
            $account = $data['account'];
            $password = $data['password'];
            $sn = $data['sn'];

            if (!$account){
                return redirect('Manage/login')->with('error','请填写账号');
            }
            $rs = Admin::where(['account'=>$account,'is_del'=>0])->first();
            if ($rs){
                if ($password != md5($rs->pwdmd5.$sn)){
                    return redirect('Manage/login')->with('error','账号密码错误');
                }

                if ($rs->is_lock){
                    return redirect('Manage/login')->with('error','您已被锁定，请联系系统管理员');
                }


                //检查组状态
                $groupInfo = AdminGroup::find($rs->group_id);
                if (!$groupInfo || $groupInfo->is_lock==1 || $groupInfo->is_del==1){
                    $request->session()->forget('admin_id');
                    $request->session()->forget('admin_name');
                    $request->session()->forget('admin_group_id');
                    return redirect('Manage/login')->with('error','您所在的组已被禁用，请联系系统管理员！');
                }

                //写入session
                $request->session()->put('admin_id',$rs->id);
                $request->session()->put('admin_account',$rs->account);
                $request->session()->put('admin_name',$rs->real_name);
                $request->session()->put('admin_group_id',$rs->group_id);
                $request->session()->put('admin_group_name',$groupInfo->group_name);

                return redirect('Manage/index');
            }else{
                return redirect('Manage/login')->with('error','账号密码错误');
            }

        }

        return $this->myView('admin.login');
    }

    //登出
    public function logout (Request $request) {
        $request->session()->forget('admin_id');
        $request->session()->forget('admin_account');
        $request->session()->forget('admin_group_id');
        $request->session()->forget('admin_auths');
        return redirect('Manage/login')->with('success','退出成功');
    }

    //锁定
    public function lock(Request $request) {
        if ($request->isMethod('POST')){
            $data = $request->input('Admin');
            $account = $data['account'];
            $password = $data['password'];
            $sn = $data['sn'];

            if (!$account){
                return redirect('Manage/lock')->with('error','信息错误');
            }
            $rs = Admin::where(['account'=>$account,'is_del'=>0])->first();
            if ($rs){
                if ($password != md5($rs->pwdmd5.$sn)){
                    return redirect('Manage/lock')->with('error','密码错误');
                }

                if ($rs->is_lock){
                    return redirect('Manage/lock')->with('error','您已被锁定，请联系系统管理员');
                }

                //销毁锁定状态
                $request->session()->forget('lock');

                //写入session
                $request->session()->put('admin_id',$rs->id);
                $request->session()->put('admin_account',$rs->account);
                $request->session()->put('admin_group_id',$rs->group_id);

                return redirect('Manage/index');
            }else{
                return redirect('Manage/lock')->with('error','账号密码错误');
            }
        }

        //记录锁定状态
        $request->session()->put('lock',1);
        return $this->myView('admin.lock');
    }

    public function _recursionMenu($data, $cur, $html='',$level=0){
        if (count($data) <= 0){
            return '';
        }
        if(count($data[$cur]) > 0){
            foreach($data[$cur] as $key=>$val){

                if ($val['pid'] == 0){
                    $level = 0;

                    $html .= '<li><a href="#" style="padding-left: 12px">';
                    if ($val['icon'] != '') {
                        $html .= '<i class="fa fa fa-'.$val['icon'].'"></i>';
                    }
                    $html .= '<span class="ng-scope">'.$val['name'].'</span>
							<span class="fa arrow"></span>
                        </a>';



                }else{

                    $html .= '<li>';
                    if ($val['url']!='' && $val['url']!='#'){
                        $html .= '<a class="J_menuItem" href="'.url($val['url']).'">';
                        if ($val['icon'] != '') {
                            $html .= '<i class="fa fa fa-'.$val['icon'].'"></i>';
                        }
                        $html .= '<span class="nav-label">'.$val['name'].'</span>
                                </a>';
                    }else{
                        $html .= '<a href="#">';
                        if ($val['icon'] != '') {
                            $html .= '<i class="fa fa fa-'.$val['icon'].'"></i>';
                        }
                        $html .= '<span class="nav-label">'.$val['name'].'</span>
                                <span class="fa arrow"></span>
                                </a>';
                    }


                }

                if(isset($data[$val['id']]) && count($data[$val['id']])>0){
                    if (isset($data[$val['pid']][0]) && $data[$val['pid']][0]['pid']==0){
                        $html .= '<ul class="nav ">';

                    }else{
                        $html .= '<ul class="nav nav-second-level">';
                    }

                    $html .= $this->_recursionMenu($data, $val['id'],'',++$level);
                    $html .= '</ul></li>';

                }
                $html .= '</li>';
            }
        }
        return $html;
    }

    //内部人员列表
    public function index(Request $request){

        $search = $request->input();

        $allGroup = AdminGroup::where(['is_del'=>0])->select('id', 'group_name')->get();
        $groupName = ['none'=>'不限','0'=>'未定义'];
        if ($allGroup){
            foreach ($allGroup as $v){
                $groupName[$v->id] = $v->group_name;
            }
        }

        $admins = $this->getResult($search, $this->row);
        foreach ($admins as &$v){
            $v->operation = [
                'module'=>'Admin',
                'obj'=>$v,
                'option'=>$this->option,
                'filtration'=>[],
            ];
        }
        $selectData = [
            'group_id'=>$groupName,

        ];
        $constant = [
            'group_id'=>$groupName,
            'is_lock'=>$this->lockArr,
        ];
        $form = Html::formCreate($admins, $search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView('admin.index',[
                'form'=>$form,
                'search'=>$search,
            ]);
    }

    //添加人员
    public function add(Request $request){
        if ($request->isMethod('POST')){
            $data = $request->input('Admin');
            $this->validate($request,[
                'Admin.account'=>'required|min:2|max:255|unique:admin,account,NULL,id,is_del,0',
                'Admin.password'=>'required',
                'Admin.real_name'=>'required',
                'Admin.position'=>'required',
                'Admin.group_id'=>'required',
                'Admin.is_lock'=>'required',
            ],[
                'required'=>':attribute 为必填项',
                'unique'=>':attribute 已存在',
                'min'=>':attribute 长度不能小于2',
                'max'=>':attribute 长度不能大于20',
            ],[
                'Admin.account'=>'账号',
                'Admin.password'=>'密码',
                'Admin.real_name'=>'真实姓名',
                'Admin.position'=>'职位',
                'Admin.group_id'=>'部门',
                'Admin.is_lock'=>'是否锁定',
            ]);


            $data['pwdmd5'] = md5($data['password']);
            unset($data['password']);

            if ($data['group_id'] <= 0){
                return redirect()->back()->with('error','请选择部门');
            }
            if (Admin::create($data)){
                return redirect('Manage/Admin/index')->with('success','添加成功！');
            }else{
                return redirect()->back()->with('error','添加失败，请重试！');
            }
        }

        $allGroups = AdminGroup::where(['pid'=>0,'is_del'=>0])->select(['id','group_name','pid','parent_ids'])->get();
        $allGroups = [0=>$allGroups];

        $admin = new Admin();

        $group = new AdminGroup();
        $group->parent_ids = explode(',',$group->parent_ids);

        $customers = Customer::where(['type'=>0,'is_del'=>0])->select(['id','real_name'])->select()->get();

        return $this->myView('admin.add',[
            'admin'=>$admin,
            'allGroups'=>$allGroups,
            'group'=>$group,
            'customers'=>$customers,
        ]);
    }

    //编辑人员
    public function edit(Request $request, $id){
        $admin = Admin::find($id);

        if($admin){
            if ($request->isMethod('POST')){
                $this->validate($request,[
                    'Admin.account'=>'required|min:2|max:255|unique:admin,account,'.$id.',id,is_del,0',
                    'Admin.position'=>'required',
                    'Admin.group_id'=>'required',
                    'Admin.is_lock'=>'required',
                ],[
                    'required'=>':attribute 为必填项',
                    'unique'=>':attribute 已存在',
                    'min'=>':attribute 长度不能小于2',
                    'max'=>':attribute 长度不能大于20',
                ],[
                    'Admin.account'=>'账号',
                    'Admin.position'=>'职位',
                    'Admin.group_id'=>'部门',
                    'Admin.is_lock'=>'是否锁定',
                ]);

                $data = $request->input('Admin');

                if ($data['group_id'] <= 0){
                    return redirect()->back()->with('error','请选择部门');
                }

                if (isset($data['password']) && !empty(trim($data['password']))){
                    $data['pwdmd5'] = md5(trim($data['password']));

                }
                unset($data['password']);
                foreach ($data as $k=>$v){
                    $admin->$k = trim($v);
                }
                if ($admin->save()){
                    return redirect('Manage/Admin/index')->with('success','修改成功！');
                }else{
                    return redirect()->back()->with('error','修改失败，请重试！');
                }
            }
        }else{
            return redirect('Manage/Admin/index')->with('error','信息错误！');
        }


        $group = AdminGroup::where(['id'=>$admin->group_id,'is_del'=>0])->first();
        $group->parent_ids = explode(',',$group->parent_ids);

        $parent = $group->parent_ids;
        $parent[] = 0;
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

        $parent[] = $admin->group_id;
        $group->parent_ids = $parent;

        $customers = Customer::where(['type'=>0,'is_del'=>0])->select(['id','real_name'])->select()->get();

        return $this->myView('admin.add',[
            'admin'=>$admin,
            'allGroups'=>$allGroups,
            'group'=>$group,
            'customers'=>$customers
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

        $obj = Admin::where(['is_del'=>0]);

        if (isset($search['group_id'])){
            $obj = $obj->where('group_id',$search['group_id']);
        }
        if (isset($search['real_name'])){
            $obj = $obj->where('real_name','like','%'.$search['real_name'].'%');
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

    //删除人员
    public function del($id){
        $admin = Admin::find($id);

        if ($admin){
            $admin->is_del = 1;
            if ($admin->save()){
                return redirect('Manage/Admin/index')->with('success','删除成功！');
            }else{
                return redirect()->back()->with('error','删除失败，请重试！');
            }
        }else{
            return redirect('Manage/Admin/index')->with('error','信息错误！');
        }
    }

    //锁定人员
    public function ajaxLock(Request $request){
        if ($request->ajax()){
            $id = $request->input('id');
            $isLock = $request->input('is_lock');
            $admin = Admin::find($id);
            if ($admin){
                $admin->is_lock = intval($isLock);
                if ($admin->save()){
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

    //修改密码
    public function changePwd(Request $request){
        if ($request->ajax()){
            $oldPwd = $request->input('oldPwd');
            $newPwd = $request->input('newPwd');
            $reNewPwd = $request->input('reNewPwd');
            if ($newPwd != $reNewPwd){
                //两次密码不一致
                return $this->json(1001);
            }

            $admin = Admin::where(['id'=>$request->session()->get('admin_id'),'is_del'=>0])->first();
            if ($admin){
                if ($admin->pwdmd5 != md5($oldPwd)){
                    //旧密码错误
                    return $this->json(1002);
                }else{
                    $admin->pwdmd5 = md5($newPwd);
                    if ($admin->save()){
                        $request->session()->forget('admin_id');
                        $request->session()->forget('admin_account');
                        $request->session()->forget('admin_group_id');
                        $request->session()->forget('admin_auths');
                        return $this->json(1);
                    }else{
                        return $this->json(9000);
                    }

                }

            }else {
                return $this->json(9002);
            }
        }else{
            return $this->myView('admin.changePwd');
        }
    }
}