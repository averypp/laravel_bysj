<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 12/29/2016
 * Time: 2:30 PM
 */
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Model\Manage\AdminGroup;
use App\Model\Manage\Auth;
use App\Model\Manage\Menu;
use App\Model\User\Person;
use App\Tools\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AuthController extends BaseController
{

    public $typeArr = [
        '0'=>'普通管理员',
        '1'=>'超级管理员',
    ];

    //权限对应名称
    public $authArrName = [
        'index'=>'浏览',
        'add'=>'增加',
        'edit'=>'修改',
        'del'=>'删除',
        'modify_state'=>'修改状态',
        'import'=>'导入',
        'export'=>'导出',
        'examine'=>'审核',
        'release'=>'发布',
    ];

    public $layout = 'auth';

    public function __construct(Request $request)
    {
        parent::__construct($request, $this->layout);
    }


    public function index(Request $request){
        $search = $request->input();
        $groups = $this->getResult($search, $this->row);

        if (count($groups)){
            foreach ($groups as &$v){
                $v->operation = [
                    'module'=>'Auth',
                    'obj'=>$v,
                    'option'=>$this->option,
                    'filtration'=>[],
                ];
            }
        }
        $selectData = [];
        $constant = [
            'type'=>$this->typeArr,
        ];
        $form = Html::formCreate($groups,$search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView('auth.index',[
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

    public function edit(Request $request, $id){
        $group = AdminGroup::find($id);

        if ($group){
            if ($request->isMethod('POST')){
                $auth = $request->input('Auth');
                $authData = [];
                if ($auth){
                    foreach ($auth as $k=>$v){
                        $arr = [
                            'group_id'=>$id,
                            'module'=>$k,
                            'updated_at'=>time(),
                            'created_at'=>time(),
                        ];
                        foreach ($this->authArr as $authV){
                            $arr[$authV] = isset($v[$authV])&&$v[$authV]==1 ? 1 : 0;
                        }
                        $authData[] = $arr;
                    }
                }
                Auth::where(['group_id'=>$id])->delete();
                if (Auth::insert($authData) ){
                    return redirect('Manage/Auth/index')->with('success','编辑成功！');
                }else{
                    return redirect()->back()->with('error','编辑失败，请重试！');
                }
            }else{
                //权限
                $auth = Auth::where(['group_id'=>$id])->get();
                $authData = [];
                if (count($auth)) {
                    foreach ($auth as $v){
                        foreach ($this->authArr as $authV){
                            $authData[$v->module][$authV] = $v->$authV;
                        }
                    }
                }
                $menus = Menu::where(['is_lock'=>0, 'is_del'=>0])->orderBy('pid','asc')->get();
                if ($menus){
                    foreach ($menus as $key=>$val){
                        $tmp[$val->id] = array(
                            'id'=>$val->id,
                            'pid'=>$val->pid,
                            'name'=>$val->name,
                            'module'=>$val->module,
                            'action'=>$val->action,
                        );
                    }
                    $menus = $tmp;
                }
                $menuInfo = [];
                if ($menus){
                    foreach ($menus as $key=>$val){
                        if ($val['pid'] == 0){
                            $menuInfo[$val['id']] = [
                                'id' => $val['id'],
                                'pid' => $val['pid'],
                                'name' => $val['name'],
                                'module' => $val['module'],
                                'action' => $val['action'],
                                'down'=> [],
                            ];
                        }else{
                            $menuInfo[$val['pid']]['down'][] = [
                                'id' => $val['id'],
                                'pid' => $val['pid'],
                                'name' => $val['name'],
                                'module' => $val['module'],
                                'action' => $val['action'],
                            ];
                        }

                    }
                }

                $option = Config::get('option');
                return $this->myView('auth.edit',[
                    'group'=>$group,
                    'menuInfo'=>$menuInfo,
                    'authData'=>$authData,
                    'authArr'=>$this->authArr,
                    'authArrName'=>$this->authArrName,
                    'optionConfig'=>$option['Manage'],
                ]);
            }

        }else{
            return redirect('Manage/AdminGroup/index')->with('error','信息错误！');
        }

    }

    public function _authMenu($data, $cur, $html='',$auth){
        if (count($data) <= 0){
            return '';
        }
        if(count($data[$cur]) > 0){
            $html .= '<ul style="list-style: none">';
            foreach($data[$cur] as $val){
                $html .= '<li>';
                $checked = '';
                if (in_array('"'.$val['menu'].'"',$auth)){
                    $checked = 'checked';
                }
                $html .= '<div><span>'.$val['name'].'</span>&nbsp<input type="checkbox" name="Group[authority][]" value="'.$val['menu'].'" onclick="checkAuth(this)" '.$checked.' /></div>';

                if(isset($data[$val['id']]) && count($data[$val['id']])>0){
                    $html .= $this->_authMenu($data, $val['id'],'',$auth);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

}