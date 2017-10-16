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
use App\Model\User\Account;
use App\Model\User\AccountInfo;
use App\Model\User\Person;
use App\Model\User\Company;
use App\Model\User\Position;
use App\Tools\DataHandle;
use App\Tools\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class PersonAuthController extends BaseController
{
    public $sexArr = [
        '0'=>'未知',
        '1'=>'男',
        '2'=>'女',
    ];
    public $statusArr = [
        '0'=>'等待认证',
        '1'=>'认证通过',
        '2'=>'认证未通过',
    ];
    // public $myTags = 'is_account';

    public $layout = 'personAuth';

    public function __construct(Request $request) {
        parent::__construct($request, $this->layout);
        $this->_init();
    }

    public function _init(){
        $viewData = [
            'sexArr'=>$this->sexArr,
        ];
        $this->viewData = array_merge ($this->viewData,$viewData);
    }
    //客户列表
    public function index(Request $request){
        $search = $request->input();
        $persons = $this->getResult($search, $this->row);
        foreach ($persons as &$v){  
            $v->examine = [
                'id'=>$v->id,
                'status'=>$v->status,
                'option'=>$this->option,
            ];
            $v->sex = $this->sexArr[$v->sex];
            $v->status = $this->statusArr[$v->status];
            $v->operation = [
                'module'=>'Account',
                'obj'=>$v,
                'option'=>$this->option,
                'filtration'=>[],
            ];
        }
        $selectData = [
        ];
        $constant = [
        ];
        $form = Html::formCreate($persons, $search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView('personAuth.index',[
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

        $obj = Person::where('person.id','>',0)
                    ->leftJoin('account', 'account.uid', '=', 'person.uid');
        $obj = $obj->select('person.*', 'account.account');
        // if (isset($search['orderby']) && isset($search['sort'])){
        //     $sort = intval($search['sort'])==1 ? 'asc' : 'desc';
        //     $obj = $obj->orderBy('account.'.trim($search['orderby']), $sort);
        // }else{
            $obj = $obj->orderBy('person.id', 'desc');
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
            $person = Person::where(['id'=>$id])->first();
            if (count($person)){
                    $update_data = [
                        'status'=>intval($status),
                        'remark' => trim($remark),
                        'examine_time' => time(),
                        // 'auditor' => $request->session()->get('admin_id'),
                    ];
                    if ( Person::where(['id'=>$id])->update($update_data) ){
                        if($status == 1){
                            $account_data['auth_status'] = 1;
                            $account_data['updated_at'] = time();
                            Account::where(['uid'=>$person->uid])->update($account_data);
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