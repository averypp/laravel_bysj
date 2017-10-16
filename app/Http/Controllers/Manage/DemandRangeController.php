<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 9/23/2017
 * Time: 11:32 AM
 */
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Model\Demand\ServiceRange;
use App\Tools\Html;
use Illuminate\Http\Request;

class DemandRangeController extends BaseController
{
    public $layout = 'demandRange';

    protected $module = 'DemandRange';

    public $model;


    public function __construct(Request $request)
    {
        $this->model = new ServiceRange();
        $this->_init();
        parent::__construct($request, $this->layout);
    }

    protected function _init(){
        $viewData = [
        ];
        $this->viewData = array_merge ($this->viewData,$viewData);
    }

    public function index(Request $request){
        $search = $request->input();

        $list = $this->getResult($search, $this->row);

        if (count($list)){
            foreach ($list as &$v){
                $v->operation = [
                    'module'=>$this->module,
                    'obj'=>$v,
                    'option'=>$this->option,
                    'filtration'=>[],
                ];
            }
        }

        $selectData = [
        ];
        $constant = [
        ];
        $form = Html::formCreate($list,$search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView($this->layout.'.index',[
            'form'=>$form,
            'search'=>$search,
        ]);
    }

    public function add(Request $request){
        if ($request->isMethod('POST')){

            $this->validate($request,[
                'Data.range_name'=>'required|unique:zero_one_platform_demand.service_range,range_name,NULL,id,is_del,0',
            ],[
                'required'=>':attribute 为必填项',
                'unique'=>':attribute 已存在',
                'min'=>':attribute 长度不能小于2',
                'max'=>':attribute 长度不能大于20',
            ],[
                'Data.range_name'=>'服务范围名称',
            ]);

            $data = $request->input('Data');

            if ($this->model->create($data)){
                return redirect('Manage/'.$this->module.'/index')->with('success','添加成功！');
            }else{

                return redirect()->back()->with('error','添加失败，请重试！');
            }
        }

        $info = $this->model;

        return $this->myView($this->layout.'.add',[
            'info'=>$info,
        ]);
    }

    public function edit(Request $request, $id){
        $info = $this->model->getInfo($id);
        if($info){
            if ($request->isMethod('POST')){
                $this->validate($request,[
                    'Data.range_name'=>'required|unique:zero_one_platform_demand.service_range,range_name,'.$id.',id,is_del,0',
                ],[
                    'required'=>':attribute 为必填项',
                    'unique'=>':attribute 已存在',
                    'min'=>':attribute 长度不能小于2',
                    'max'=>':attribute 长度不能大于20',
                ],[
                    'Data.range_name'=>'服务范围名称',
                ]);

                $data = $request->input('Data');

                foreach ($data as $k=>$v){
                    $info->$k = trim($v);
                }

                if ($info->save()){
                    return redirect('Manage/'.$this->module.'/index')->with('success','修改成功！');
                }else{
                    return redirect()->back()->with('error','修改失败，请重试！');
                }
            }
        }else{
            return redirect('Manage/'.$this->module.'/index')->with('error','信息错误！');
        }

        return $this->myView($this->layout.'.edit',[
            'info'=>$info,
        ]);
    }

    //删除
    public function del($id) {

        $info = $this->model->getInfo($id);

        if ($info){
            $info->is_del = 1;
            if ($info->save()){
                return redirect('Manage/'.$this->module.'/index')->with('success','删除成功！');
            }else{
                return redirect()->back()->with('error','删除失败，请重试！');
            }
        }else{
            return redirect('Manage/'.$this->module.'/index')->with('error','信息错误！');
        }

    }

    protected function getResult($search, $row=0){
        foreach ($search as $k=>$v){
            if (!is_array($search[$k]) && ($search[$k] === '' || $search[$k] === 'none')){
                unset($search[$k]);
            }else{
                if (!is_array($search[$k])){
                    $search[$k] = trim($v);
                }

            }
        }

        $obj = $this->model->where(['is_del'=>0]);

        if (isset($search['range_name'])){
            $obj = $obj->where('range_name','like','%'.$search['range_name'].'%');
        }

        // if (isset($search['orderby']) && isset($search['sort'])){
        //     $sort = intval($search['sort'])==1 ? 'asc' : 'desc';
        //     $obj = $obj->orderBy(trim($search['orderby']), $sort);
        // }else{
            $obj = $obj->orderBy('id', 'desc');
        // }

        if ($row == 0){
            $obj = $obj->get();
        }else{
            $obj = $obj->paginate($row);
        }
        return $obj;
    }
}   