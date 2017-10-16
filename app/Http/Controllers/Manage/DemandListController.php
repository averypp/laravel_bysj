<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-11-29
 * Time: 16:12
 */
namespace App\Http\Controllers\Manage;
use App\Http\Controllers\BaseController;
use App\Model\Manage\Admin;
use App\Model\Demand\Demand;
use App\Model\Demand\DemandInfo;
use App\Model\Demand\Dynamic;
use App\Model\Demand\ServiceRange;
use App\Tools\DataHandle;
use App\Tools\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class DemandListController extends BaseController
{
    
    public $typeArr = [
        '1'=>'悬红榜',
        '2'=>'企业问诊',
        '3'=>'导师对接',
    ];
    public $statusArr = [
        '0'=>'未审核',
        '1'=>'审核通过',
        '2'=>'审核不通过',
        '3'=>'待处理',
        '4'=>'完成', 
    ];
    // public $myTags = 'is_account';

    public $layout = 'demandList';

    public function __construct(Request $request) {
        parent::__construct($request, $this->layout);
        $this->_init();
    }

    public function _init(){
        $viewData = [
        ];
        $this->viewData = array_merge ($this->viewData,$viewData);
    }
    //客户列表
    public function index(Request $request){
        $search = $request->input();
        $applys = $this->getResult($search, $this->row);
        foreach ($applys as &$v){
            $v->examine = [
                'id'=>$v->id,
                'status'=>$v->status,
                'option'=>$this->option,
            ];
            $v->show_priv = $v->show_priv == 0 ? '公开': '只对服务商开放';
            $v->type = $this->typeArr[$v->type];
            $v->status = $this->statusArr[$v->status];
            $v->operation = [
                'module'=>'DemandList',
                'obj'=>$v,
                'option'=>$this->option,
                'filtration'=>[],
            ];
        }
        $selectData = [
        ];
        $constant = [
        ];
        $form = Html::formCreate($applys, $search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView('demandList.index',[
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
        $obj = Demand::where('id','>',0);
                    // ->leftJoin('account', 'account.uid', '=', 'person.uid');
        // if (isset($search['orderby']) && isset($search['sort'])){
        //     $sort = intval($search['sort'])==1 ? 'asc' : 'desc';
        //     $obj = $obj->orderBy('account.'.trim($search['orderby']), $sort);
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

    //审核
    public function examine(Request $request){
        if ($request->ajax()){
            $id = $request->input('id',0);
            $status = $request->input('status',0);
            $remark = $request->input('remark');
            if ($id == 0){
                return $this->json(1001);
            }
            if ($status != 1 && $status != 2){
                return $this->json(1001);
            }
            $demand = Demand::where(['is_del'=>0,'id'=>$id])->first();
            if (count($demand)){
                    $update_data = [
                        'status'=>intval($status),
                        'remark' => trim($remark),
                        'examine_time' => time(),
                        'auditor' => $request->session()->get('admin_id'),
                    ];
                    if ( Demand::where(['is_del'=>0])->where(['id'=>$id])->update($update_data) ){
                        if($status == 1){
                            $dynamic_data['uid'] = $demand->uid;
                            $dynamic_data['did'] = $demand->id;
                            $dynamic_data['type'] = $demand->type;
                            Dynamic::create($dynamic_data);
                        }
                        return $this->json(1);
                    } else {
                        return $this->json(1004);
                    }
            }else{
                return $this->json(1003);
            }
        } else {
            return $this->json(1002);
        }
    }
}