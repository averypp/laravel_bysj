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
use App\Model\Demand\ServiceApply;
use App\Model\Demand\ServiceAuth;
use App\Model\Demand\ServiceRange;
use App\Model\User\Account;
use App\Tools\DataHandle;
use App\Tools\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class FactorApplyController extends BaseController
{
    
    public $statusArr = [
        '0'=>'等待认证',
        '1'=>'认证通过',
        '2'=>'认证未通过',
    ];
    // public $myTags = 'is_account';

    public $layout = 'factorApply';

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
            $v->company_id = $v->company_id == 0 ? '个人' : '公司' ;
            $v->examine = [
                'id'=>$v->id,
                'status'=>$v->status,
                'option'=>$this->option,
            ];
            $v->status = $this->statusArr[$v->status];
            $v->operation = [
                'module'=>'FactorApply',
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

        return $this->myView('factorApply.index',[
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
        $obj = ServiceApply::where('id','>',0);
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
            $apply = ServiceApply::where(['is_del'=>0])->where(['id'=>$id])->first();
            if (count($apply)){
                    $update_data = [
                        'status'=>intval($status),
                        'remark' => trim($remark),
                        'examine_time' => time(),
                        'auditor' => $request->session()->get('admin_id'),
                    ];
                    if ( ServiceApply::where(['is_del'=>0])->where(['id'=>$id])->update($update_data) ){
                        if($status == 1){
                            $existedAuth = ServiceAuth::where(['uid'=>$apply['uid']])->first();
                            if(count($existedAuth)){
                                return $this->json(1005);
                            }
                            $auth_data['uid'] = $apply['uid'];
                            $auth_data['company_id'] = $apply['company_id'];
                            $auth_data['service_range_id'] = $apply['service_range_id'];
                            $auth_data['created_at'] = time();
                            ServiceAuth::insert($auth_data);
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