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


class AccountController extends BaseController
{
    public $sexArr = [
        '0'=>'未知',
        '1'=>'男',
        '2'=>'女',
    ];
    public $statusArr = [
        '0'=>'未认证',
        '1'=>'个人',
        '2'=>'企业',
    ];
    public $layout = 'account';

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
        $accounts = $this->getResult($search, $this->row);
        foreach ($accounts as &$v){
            $v->sex = $this->sexArr[$v->sex];
            $v->status = $this->statusArr[$v->auth_status];
            $v->operation = [
                'module'=>'Account',
                'obj'=>$v,
                'option'=>$this->option,
                'filtration'=>[],
            ];
        }
        $selectData = [
            'auth_status'=>$this->statusArr,
        ];
        $constant = [
        ];
        $form = Html::formCreate($accounts, $search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView('account.index',[
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
        $obj = Account::where(['account.is_del'=>0])
                    ->leftJoin('account_info', 'account.uid', '=', 'account_info.uid');
        $obj = $obj->select('account.*','account_info.user_name','account_info.sex',
            'account_info.acct_photo_url','account_info.position_id','account_info.company_name');

        if (isset($search['account'])){
            $obj = $obj->where('account.account','like',"%".$search['account']."%");
        }        
        if (isset($search['auth_status'])){
            $obj = $obj->where('account.auth_status',intval($search['auth_status']));
        }
        // if (isset($search['orderby']) && isset($search['sort'])){
        //     $sort = intval($search['sort'])==1 ? 'asc' : 'desc';
        //     $obj = $obj->orderBy('account.'.trim($search['orderby']), $sort);
        // }else{
            $obj = $obj->orderBy('account.id', 'desc');
        // }

        if ($row == 0){
            $obj = $obj->get();
        }else{
            $obj = $obj->paginate($row);
        }

        return $obj;
    }
    //添加客户
    public function add(Request $request){
        if ($request->isMethod('POST')){
            $this->validate($request,[
                'account.account'=>'min:2|max:11|unique:zero_one_platform_user.account,account,NULL,id,is_del,0',
                'account.is_lock'=>'required',
            ],[
                'required'=>':attribute 为必填项',
                'unique'=>':attribute 已存在',
                'min'=>':attribute 长度不能小于2',
                'max'=>':attribute 长度不能大于11',
            ],[
                'account.account'=>'账号',
                'account.is_lock'=>'是否锁定',
            ]);
            $data = $request->input('account');
            $account_data['pwd'] = md5($data['account']);
            $account_data['account'] = $data['account'];
            $account_data['uid'] = md5($data['account'].time());
            $account = Account::create($account_data);
            if ($account){
                $account_info_data['uid'] = $account_data['uid'];
                $account_info_data['user_name'] = $data['user_name'];
                $account_info_data['sex'] = $data['sex'];
                $account_info_data['mobile'] = $data['account'];
                $account_info_data['acct_photo_url'] = $data['img'];
                $account_info_data['position_id'] = Position::getPositionId($data['position']);
                $account_info_data['company_name'] = $data['company'];
                AccountInfo::create($account_info_data);
                return redirect('Manage/Account/index')->with('success','添加成功！');
            }else{
                return redirect()->back()->with('error','添加失败，请重试！');
            }
        }
        $account = new Account();
        $account->acct_photo_url = '';
        return $this->myView('account.add',[
            'account'=>$account,
        ]);
    }

    //编辑客户
    public function edit(Request $request, $id) {
        $account = Account::find($id);
        $account_info = AccountInfo::where(['uid'=>$account->uid])->first();
        if($account && $account_info){
            if ($request->isMethod('POST')){
                $this->validate($request,[
                    'account.is_lock'=>'required',
                ],[
                    'required'=>':attribute 为必填项',
                    'unique'=>':attribute 已存在',
                    'min'=>':attribute 长度不能小于2',
                    'max'=>':attribute 长度不能大于11',
                ],[
                    'account.account'=>'账号',
                    'account.is_lock'=>'是否锁定',
                ]);
                $data = $request->input('account');
                $account_data['is_lock'] = $data['is_lock'];
                $account_info_data['user_name'] = $data['user_name'];
                $account_info_data['sex'] = $data['sex'];
                $account_info_data['acct_photo_url'] = $data['img'];
                $account_info_data['position_id'] = Position::getPositionId($data['position']);
                $account_info_data['company_name'] = $data['company'];
                if ($account_info->update($account_info_data) && $account->update($account_data)){
                    return redirect('Manage/Account/index')->with('success','修改成功！');
                }else{
                    return redirect()->back()->with('error','修改失败，请重试！');
                }
            }
        }else{
            return redirect('Manage/Customer/index')->with('error','信息错误！');
        }
        $account->user_name = $account_info->user_name;
        $account->sex = $account_info->sex;
        $account->position = Position::getPositionNameById($account_info->position_id);
        $account->company = $account_info->company_name;
        $account->img = strstr($account_info->acct_photo_url,'http') ? $account_info->acct_photo_url : '/'.$account_info->acct_photo_url;
        return $this->myView('account.edit',[
            'account'=>$account,
        ]);
    }

    //删除客户
    public function del($id) {
        $account = Account::find($id);
        if ($account){
            $account->is_del = 1;
            if ($account->save()){
                return redirect('Manage/Account/index')->with('success','删除成功！');
            }else{
                return redirect()->back()->with('error','删除失败，请重试！');
            }
        }else{
            return redirect('Manage/Account/index')->with('error','信息错误！');
        }
    }
    public function ajaxLock(Request $request){
        if ($request->ajax()){

            $id = $request->input('id');
            $isLock = $request->input('is_lock');
            $account = Account::find($id);
            if ($account){
                $account->is_lock = intval($isLock);
                if ($account->save()){
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