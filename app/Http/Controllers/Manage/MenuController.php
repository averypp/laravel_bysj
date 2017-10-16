<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-11-29
 * Time: 16:12
 */
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Model\Manage\Menu;
use Illuminate\Http\Request;


class MenuController extends BaseController
{

    public $layout = 'menu';

    public function __construct(Request $request)
    {
        parent::__construct($request, $this->layout);
    }

    //菜单列表
    public function index(Request $request){

        $allMenus = Menu::where(['is_del'=>0])->select('id', 'name')->get();
        $menusName = [0=>'顶级菜单'];
        if ($allMenus){
            foreach ($allMenus as $v){
                $menusName[$v->id] = $v->name;
            }
        }

        $search = [];
        if ($request->input('keyword')){
            $keyword = trim($request->input('keyword'));

            $search['keyword'] = $keyword;
            $menus = Menu::where(['is_del'=>0])
                    ->Where('name','like','%'.$keyword.'%')
                    ->paginate($this->row);
        }else {
            $menus = Menu::where(['is_del'=>0])->paginate($this->row);
        }

        return $this->myView('menu.index',[
            'menus'=>$menus,
            'menusName'=>$menusName,
            'search'=>$search,
        ]);
    }

    //添加菜单
    public function add(Request $request){

        if ($request->isMethod('POST')){

            $this->validate($request,[
                'Menu.name'=>'required|min:2|max:20|unique:zero_one_platform_manage.menu,name,NULL,id,is_del,0',
                'Menu.pid'=>'required',
                'Menu.is_lock'=>'required',
            ],[
                'required'=>':attribute 为必填项',
                'unique'=>':attribute 已存在',
                'min'=>':attribute 长度不能小于2',
                'max'=>':attribute 长度不能大于20',
            ],[
                'Menu.name'=>'菜单名称',
                'Menu.pid'=>'上级菜单',
                'Menu.is_lock'=>'是否锁定',
            ]);

            $data = $request->input('Menu');

            if (Menu::create($data)){
                return redirect('Manage/Menu/index')->with('success','添加成功！');
            }else{

                return redirect()->back()->with('error','添加失败，请重试！');
            }
        }

        $menu = new Menu();
        $topMenus = Menu::where(['pid'=>0,'is_del'=>0])->get();
        return $this->myView('menu.add',['topMenus'=>$topMenus,'menu'=>$menu]);
    }

    //编辑菜单
    public function edit(Request $request, $id) {

        $menu = Menu::find($id);

        if($menu){
            if ($request->isMethod('POST')){
                $this->validate($request,[
                    'Menu.name'=>'required|min:2|max:20|unique:zero_one_platform_manage.menu,name,'.$id.',id,is_del,0',
                    'Menu.pid'=>'required',
                    'Menu.is_lock'=>'required',
                ],[
                    'required'=>':attribute 为必填项',
                    'unique'=>':attribute 已存在',
                    'min'=>':attribute 长度不能小于2',
                    'max'=>':attribute 长度不能大于20',
                ],[
                    'Menu.name'=>'菜单名称',
                    'Menu.pid'=>'上级菜单',
                    'Menu.is_lock'=>'是否锁定',
                ]);

                $data = $request->input('Menu');
                foreach ($data as $k=>$v){
                    $menu->$k = trim($v);
                }

                if ($menu->save()){
                    return redirect('Manage/Menu/index')->with('success','修改成功！');
                }else{
                    return redirect()->back()->with('error','修改失败，请重试！');
                }
            }
        }else{
            return redirect('Manage/Menu/index')->with('error','信息错误！');
        }


        $topMenus = Menu::where(['pid'=>0,'is_del'=>0])->get();
        return $this->myView('menu.edit',['topMenus'=>$topMenus,'menu'=>$menu]);
    }

    //删除菜单
    public function del($id) {

        $menu = Menu::find($id);

        if ($menu){
            $menu->is_del = 1;
            if ($menu->save()){
                return redirect('Manage/Menu/index')->with('success','删除成功！');
            }else{
                return redirect()->back()->with('error','删除失败，请重试！');
            }
        }else{
            return redirect('Manage/Menu/index')->with('error','信息错误！');
        }

    }

    //获取下级菜单
    public function ajaxGetMenuByPid(Request $request){
        if ($request->ajax()){

            $pid = $request->input('pid');
            if ($pid > 0){
                $menus = Menu::where(['pid'=>$pid,'is_del'=>0])->select('id', 'name')->get();
                return $this->json(1,$menus);
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
            $menu = Menu::find($id);
            if ($menu){
                $menu->is_lock = intval($isLock);
                if ($menu->save()){
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