<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 9/23/2017
 * Time: 11:32 AM
 */
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Model\Policy\District;
use App\Model\Policy\Policy;
use App\Model\Policy\PolicyCondition;
use App\Model\Policy\PolicyType;
use App\Tools\DataHandle;
use App\Tools\Html;
use Illuminate\Http\Request;

class PolicyController extends BaseController
{
    public $layout = 'policy';

    protected $module = 'Policy';

    public $model;


    public function __construct(Request $request)
    {
        $this->model = new Policy();
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

        $district = District::getDistrict(1);
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
            'province'=>[0=>''] + $district['province'],
            'city'=>[0=>''] + $district['city'],
            'area'=>[0=>''] + $district['area'],
        ];
        $form = Html::formCreate($list,$search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView($this->layout.'.index',[
            'form'=>$form,
            'search'=>$search,
        ]);
    }

    public function add(Request $request){
        if ($request->isMethod('POST')){

            $data = $request->input('Data');
            $conditions = $request->input('Condition');

            $data['policy_type_ids'] = DataHandle::implodeWithInteger(',',$data['policy_type_ids']);
            $data['start_day'] = $data['start_day']!='' ? DataHandle::_strtotime($data['start_day'],'Y-m-d') : 0;
            $data['end_day'] = $data['end_day']!='' ? DataHandle::_strtotime($data['end_day'],'Y-m-d') : 0;
            $data['amount'] = intval($data['amount']);

            $policy = $this->model->create($data);
            if ($policy){
                foreach ($conditions as $k=>$v){
                    $conditions[$k] = intval($v);
                }
                $conditions['policy_id'] = $policy->id;

                PolicyCondition::create($conditions);
                return redirect('Manage/'.$this->module.'/index')->with('success','添加成功！');
            }else{

                return redirect()->back()->with('error','添加失败，请重试！');
            }
        }

        $info = $this->model;

        $info->policy_type_ids = explode(',',$info->policy_type_ids);

        $policyType = PolicyType::where(['is_del'=>0])->select(['id','type_name'])->get();

        $policyCondition = new PolicyCondition();
        return $this->myView($this->layout.'.add',[
            'info'=>$info,
            'policyType'=>$policyType,
            'policyCondition'=>$policyCondition,
        ]);
    }

    public function edit(Request $request, $id){
        $info = $this->model->getInfo($id);
        if($info){
            if ($request->isMethod('POST')){


                $data = $request->input('Data');
                $conditions = $request->input('Condition');

                $data['policy_type_ids'] = DataHandle::implodeWithInteger(',',$data['policy_type_ids']);
                $data['start_day'] = $data['start_day']!='' ? strtotime($data['start_day']) : 0;
                $data['end_day'] = $data['end_day']!='' ? strtotime($data['end_day']) : 0;

                foreach ($data as $k=>$v){
                    $info->$k = trim($v);
                }
//                $info->updated_at = time();
                $conditions['policy_id'] = $info->id;
                if ($info->save()){
                    foreach ($conditions as $k=>$v){
                        $conditions[$k] = intval($v);
                    }
                    PolicyCondition::where(['policy_id'=>$conditions['policy_id']])->delete();
                    PolicyCondition::create($conditions);
//                    return redirect('Manage/'.$this->module.'/index')->with('success','修改成功！');
                    return redirect('Manage/'.$this->module.'/edit/'.($id+1))->with('success','修改成功！');
                }else{
                    return redirect()->back()->with('error','修改失败，请重试！');
                }
            }
        }else{
            return redirect('Manage/'.$this->module.'/index')->with('error','信息错误！');
        }

        $info->policy_type_ids = explode(',',$info->policy_type_ids);

        $policyType = PolicyType::where(['is_del'=>0])->select(['id','type_name'])->get();

        $policyCondition = PolicyCondition::getInfo($info->id);
        if (!$policyCondition){
            $policyCondition = new PolicyCondition();
        }
        return $this->myView($this->layout.'.edit',[
            'info'=>$info,
            'policyType'=>$policyType,
            'policyCondition'=>$policyCondition,
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

        if (isset($search['policy_name'])){
            $obj = $obj->where('policy_name','like','%'.$search['policy_name'].'%');
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