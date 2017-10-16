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


class ExampleController extends BaseController
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

    public $layout = 'companyAuth';

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
        $companys = $this->getResult($search, $this->row);
        foreach ($companys as &$v){
            // $v->position = $this->sexArr[$v->position_id];
            $v->examine = [
                'id'=>$v->id,
                'status'=>$v->status,
                'option'=>$this->option,
            ];
            $v->status = $this->statusArr[$v->status];
            $v->operation = [
                'module'=>'Company',
                'obj'=>$v,
                'option'=>$this->option,
                'filtration'=>[],
            ];
        }
        $selectData = [
            
        ];
        $constant = [
           
        ];
        $form = Html::formCreate($companys, $search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView('companyAuth.index',[
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
        $obj = Company::where('company.id','>',0)
                    ->leftJoin('account', 'company.uid', '=', 'account.uid');
        $obj = $obj->select('company.*', 'account.account');

        // if (isset($search['group_name'])){
        //     $obj = $obj->where('group_name','like','%'.$search['group_name'].'%');
        // }
        // public function scopeLong(  Query $query, $long = 700){
        //       return $query->where( 'pages_count', '>', $long );
        // }
        // public function scopeCheap(  Query $query, $price = 500){
        //       return $query->where( 'price', '<', $price );
        // }
        //App\Book::cheap()->long()->toSql()
        //"select * from `books` where `price` < ? and `pages_count` > ?"


        // if (isset($search['orderby']) && isset($search['sort'])){
        //     $sort = intval($search['sort'])==1 ? 'asc' : 'desc';
        //     $obj = $obj->orderBy('account.'.trim($search['orderby']), $sort);
        // }else{
            $obj = $obj->orderBy('company.id', 'desc');
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
            $company = Company::where(['id'=>$id])->first();
            if (count($company)){
                    $update_data = [
                        'status'=>intval($status),
                        'remark' => trim($remark),
                        'examine_time' => time(),
                        // 'auditor' => $request->session()->get('admin_id'),
                    ];
                    if ( Company::where(['id'=>$id])->update($update_data) ){
                        if($status == 1){
                            $account_data['auth_status'] = 2;
                            $account_data['company_id'] = $company->id;
                            $account_data['updated_at'] = time();
                            Account::where(['uid'=>$company->uid])->update($account_data);
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

    //添加客户
    public function add(Request $request){

        if ($request->isMethod('POST')){

            $this->validate($request,[
                'Customer.account'=>'min:2|max:255|unique:customer,account,NULL,id,is_del,0',
                'Customer.type'=>'required',
                'Customer.is_lock'=>'required',
            ],[
                'required'=>':attribute 为必填项',
                'unique'=>':attribute 已存在',
                'min'=>':attribute 长度不能小于2',
                'max'=>':attribute 长度不能大于20',
            ],[
                'Customer.account'=>'账号',
                'Customer.type'=>'用户类型',
                'Customer.is_lock'=>'是否锁定',
            ]);

            $data = $request->input('Customer');
            $tagIds = $request->input('TagIds');

            $data['age'] = intval($data['age']);
            $data['pwdmd5'] = md5($data['password']);
            unset($data['password']);

            $data['position_ids'] = isset($data['position_ids']) ? DataHandle::implodeWithInteger(',',$data['position_ids']) : '';
            $bindOther = isset($data['bindOther']) ? DataHandle::implodeWithInteger(',',$data['bindOther']) : '';
            $data['tag_ids'] = count($tagIds) ? DataHandle::implodeWithInteger(',',array_unique($tagIds) ) : '';

            $project = isset($data['project']) ? $data['project'] : [];
            unset($data['project']);
            unset($data['bindOther']);

            unset($data['company_name']);

            $data['uid'] = $data['mobile'] ? md5($data['mobile']) : md5($data['real_name'].time());
            $data['annual_income'] = intval($data['annual_income'] * 10000);
            $data['create_admin'] = intval($request->session()->get('admin_id'));

            $customer = Customer::create($data);
            if ($customer){
                $projectData = [];
                if (count($project)){
                    foreach ($project as $v){
                        $projectData[] = [
                            'customer_id'=>$customer->id,
                            'project_id'=>$v,
                            'updated_at'=>time(),
                            'created_at'=>time(),
                        ];
                    }
                }
                relationCustomerProject::insert($projectData);

                //如果有绑定其他账号
                if ($bindOther != ''){
                    $otherCustomers = Customer::where(['is_del'=>0])->whereRaw('id != "'.$customer->id.'"')->whereIn('id',explode(',',$bindOther))->select(['id','mobile','uid'])->get();
                    if (count($otherCustomers)){
                        $allIds[] = $customer->id;
                        foreach ($otherCustomers as $v){
                            $allIds = array_merge($allIds,$this->getCustomerIdsByUid($v->uid));
                        }
                        $uid = md5(implode(',',$allIds).','.time());
                        $updateData = [
                            'uid'=>$uid,
                        ];
                        Customer::where(['is_del'=>0])->whereIn('id',$allIds)->update($updateData);
                    }
                }

                return redirect('Manage/Customer/index')->with('success','添加成功！');
            }else{

                return redirect()->back()->with('error','添加失败，请重试！');
            }
        }

        $customer = new Customer();
        $projects = Project::where(['is_lock'=>0,'is_del'=>0])->select('id', 'project_name')->get();
        $projectIds = [];

        //公司
        $companys = Company::where(['is_del'=>0])->select('id','company_name')->get();
        $companysName = [];
        if (count($companys)){
            foreach ($companys as $v){
                $companysName[$v->id] = $v->company_name;
            }
        }

        //职位
        $positions = Position::where(['is_del'=>0])->select('id','position_name')->get();

        /*********标签**********/
        $tagName = [];
        //标签分类
        $allTagCategorys = TagCategory::where(['pid'=>0,'is_del'=>0])
                            ->where($this->myTags, '>', 0)
                            ->get();

        //允许使用的标签分类
        $allowTagCategorys = [];

        //默认显示的分类
        $defaultTagCategorys = [];

        if (count($allTagCategorys)){
            foreach ($allTagCategorys as $v){
                $allowTagCategorys[] = [
                    'id'=>$v->id,
                    'pid'=>$v->pid,
                    'children'=>$v->children,
                    'category_name'=>$v->category_name,
                ];
                if ($v[$this->myTags] == 2){
                    $defaultTagCategorys[] = [
                        'id'=>$v->id,
                        'pid'=>$v->pid,
                        'children'=>$v->children,
                        'category_name'=>$v->category_name,
                        'tags'=>[],
                    ];
                }
            }
        }
        /*********标签结束**********/

        //客户
        $customers = Customer::where(['is_del'=>0])->select(['id','real_name','mobile','company_id'])->get();
        $customer->bindOther = [];//绑定其他用户
        $customer->photo_show = '';
        $customer->head_img_show = '';
        $customer->business_card_show = '';

        $customer->position_ids = explode(',',$customer->position_ids);

        $adminName = [0=>'系统'];
        $admins = Admin::where(['is_del'=>0])->select(['id','real_name'])->get();
        if (count($admins)){
            foreach ($admins as $v){
                $adminName[$v->id] = $v->real_name;
            }
        }

        $customer->source = $request->session()->get('admin_id');
        $customer->type = 1;
        return $this->myView('customer.add',[
            'customer'=>$customer,
            'customers'=>$customers,
            'projects'=>$projects,
            'projectIds'=>$projectIds,
            'companysName'=>$companysName,
            'positions'=>$positions,
            'allowTagCategorys'=>$allowTagCategorys,
            'defaultTagCategorys'=>$defaultTagCategorys,
            'tagName'=>$tagName,
            'adminName'=>$adminName,
        ]);
    }

    //编辑客户
    public function edit(Request $request, $id) {

        $customer = Customer::find($id);
        if($customer){
            if ($request->isMethod('POST')){
                $this->validate($request,[
                    'Customer.account'=>'min:2|max:255|unique:customer,account,'.$id.',id,is_del,0',
                    'Customer.type'=>'required',
                    'Customer.is_lock'=>'required',
                ],[
                    'required'=>':attribute 为必填项',
                    'unique'=>':attribute 已存在',
                    'min'=>':attribute 长度不能小于2',
                    'max'=>':attribute 长度不能大于20',
                ],[
                    'Customer.account'=>'账号',
                    'Customer.type'=>'用户类型',
                    'Customer.is_lock'=>'是否锁定',
                ]);

                $data = $request->input('Customer');
                $tagIds = $request->input('TagIds');

                $data['age'] = intval($data['age']);

                if ($data['password'] != ''){
                    $customer->pwdmd5 = md5($data['password']);
                }
                unset($data['password']);
                $project = isset($data['project']) ? $data['project'] : [];
                unset($data['project']);

                $bindOtherOld = $this->getCustomerIdsByUid($customer->uid);

                $data['position_ids'] = isset($data['position_ids']) ? DataHandle::implodeWithInteger(',',$data['position_ids']) : '';
                $bindOther = isset($data['bindOther']) ? DataHandle::implodeWithInteger(',',$data['bindOther']) : '';
                $data['tag_ids'] = count($tagIds) ? DataHandle::implodeWithInteger(',',array_unique($tagIds) ) : '';

                if ($bindOther == ''){
                    $data['uid'] = $data['mobile'] ? md5($data['mobile']) : md5($data['real_name'].time());
                }

                $data['annual_income'] = intval($data['annual_income'] * 10000);

                unset($data['company_name']);
                unset($data['bindOther']);

                foreach ($data as $k=>$v){
                    $customer->$k = trim($v);
                }

                $projectData = [];
                if (count($project)){
                    foreach ($project as $v){
                        $projectData[] = [
                            'customer_id'=>$customer->id,
                            'project_id'=>$v,
                            'updated_at'=>time(),
                            'created_at'=>time(),
                        ];
                    }
                }
                relationCustomerProject::where(['customer_id'=>$customer->id])->delete();
                relationCustomerProject::insert($projectData);
                if ($customer->save()){
                    //如果有绑定其他账号
                    if ($bindOther != ''){

                        $allIdsArr = explode(',',$bindOther.','.$customer->id);

                        foreach ($bindOtherOld as $k=>$v){
                            if (in_array($v, $allIdsArr)){
                                unset($bindOtherOld[$k]);
                            }
                        }

                        $uid = md5(implode(',',$allIdsArr).','.time());
                        $updateData = [
                            'uid'=>$uid,
                        ];
                        Customer::where(['is_del'=>0])->whereIn('id',$allIdsArr)->update($updateData);

                        if (count($bindOtherOld)){
                            $otherCustomer = Customer::where(['is_del'=>0])->whereIn('id',$bindOtherOld)->select(['id','mobile','real_name'])->select()->get();
                            if (count($otherCustomer)){
                                foreach ($otherCustomer as $v){
                                    $updateData = [
                                        'uid'=>($v->mobile ? md5($v->mobile) : md5($v->real_name.time())),
                                    ];
                                    Customer::where(['id'=>$v->id,'is_del'=>0])->update($updateData);
                                }

                            }
                        }

                    }else{
                        if ($bindOtherOld){
                            $otherCustomer = Customer::where(['is_del'=>0])->whereIn('id',$bindOtherOld)->select(['id','mobile','real_name'])->select()->get();
                            if (count($otherCustomer)){
                                foreach ($otherCustomer as $v){
                                    $updateData = [
                                        'uid'=>($v->mobile ? md5($v->mobile) : md5($v->real_name.time())),
                                    ];
                                    Customer::where(['id'=>$v->id,'is_del'=>0])->update($updateData);
                                }
                            }

                        }
                    }

                    return redirect('Manage/Customer/index')->with('success','修改成功！');
                }else{
                    return redirect()->back()->with('error','修改失败，请重试！');
                }
            }
        }else{
            return redirect('Manage/Customer/index')->with('error','信息错误！');
        }

        $projectIds = [];
        $projectList = Customer::find($id)->project;
        $projects = Project::where(['is_lock'=>0,'is_del'=>0])->select('id', 'project_name')->get();
        foreach ($projectList as $v){
            $projectIds[] = $v->project_id;
        }

        //公司
        $companys = Company::where(['is_del'=>0])->select('id','company_name')->get();
        $companysName = [];
        if (count($companys)){
            foreach ($companys as $v){
                $companysName[$v->id] = $v->company_name;
            }
        }

        $customerTags = explode(',',$customer->tag_ids);

        /*********标签**********/
        $tagName = [];
        $categoryIds = [];
        $tags = Tag::where(['is_lock'=>0,'is_del'=>0])->whereIn('id',$customerTags)->select(['id','category_id','tag_name'])->get();
        if (count($tags)){
            $tmp = [];
            foreach ($tags as $v){
                $tagName[$v->id] = $v->tag_name;//标签id=》标签名称
                $categoryIds[] = $v->category_id;
                if (isset($tmp[$v->category_id])){
                    $tmp[$v->category_id][] = $v->id;
                }else{
                    $tmp[$v->category_id] = [];
                    $tmp[$v->category_id][] = $v->id;
                }
            }
            $tags = $tmp;//按分类归类
        }

        //标签分类
        $allTagCategorys = TagCategory::where('is_del', 0)
            ->whereIn('id', $categoryIds)
            ->orWhere(function($query){
                $query->where(['pid'=>0,'is_del'=>0])
                    ->where($this->myTags, '>', 0);
            })
            ->orderBy('pid','asc')
            ->get();


        //允许使用的标签分类
        $allowTagCategorys = [];

        //默认显示的分类
        $defaultTagCategorys = [];

        if (count($allTagCategorys)){
            foreach ($allTagCategorys as $v){
                if ($v->pid == 0){
                    $allowTagCategorys[$v->id] = [
                        'id'=>$v->id,
                        'pid'=>$v->pid,
                        'children'=>$v->children,
                        'category_name'=>$v->category_name,
                    ];
                    if ($v[$this->myTags] == 2){
                        $defaultTagCategorys[$v->id] = [
                            'id'=>$v->id,
                            'pid'=>$v->pid,
                            'children'=>$v->children,
                            'category_name'=>$v->category_name,
                            'tags'=>[]
                        ];
                    }
                }else{
                    $parentIds = explode(',',$v->parent_ids);
                    $topId = $parentIds[count($parentIds)-1];
                    if (!isset($defaultTagCategorys[$topId])) {
                        if (!isset($allowTagCategorys[$topId])){
                            continue;
                        }
                        $defaultTagCategorys[$topId] = $allowTagCategorys[$topId];
                        $defaultTagCategorys[$topId]['tags'] = [];
                    }
                    $defaultTagCategorys[$topId]['tags'] = array_unique(isset($tags[$v->id]) ? array_merge($tags[$v->id], $defaultTagCategorys[$topId]['tags']) : $defaultTagCategorys[$topId]['tags']);
                }

            }
        }
        /**********标签结束************/
        //职位
        $positions = Position::where(['is_del'=>0])->select('id','position_name')->get();

        //客户
        $customers = Customer::where(['is_del'=>0])->select(['id','real_name','mobile','company_id'])->get();
        $customer->bindOther = $this->getCustomerIdsByUid($customer->uid);//绑定其他用户

        $customer->position_ids = explode(',',$customer->position_ids);

        $customer->photo_show = '';
        $customer->head_img_show = '';
        $customer->business_card_show = '';
        if ($customer->photo){
            $customer->photo_show = strstr($customer->photo,'http') ? $customer->photo : '/'.$customer->photo;
        }
        if ($customer->head_img){
            $customer->head_img_show = strstr($customer->head_img,'http') ? $customer->head_img : '/'.$customer->head_img;
        }
        if ($customer->business_card){
            $customer->business_card_show = strstr($customer->business_card,'http') ? $customer->business_card : '/'.$customer->business_card;
        }

        $adminName = [0=>'系统'];
        $admins = Admin::where(['is_del'=>0])->select(['id','real_name'])->get();
        if (count($admins)){
            foreach ($admins as $v){
                $adminName[$v->id] = $v->real_name;
            }
        }

        return $this->myView('customer.edit',[
            'customer'=>$customer,
            'customers'=>$customers,
            'projects'=>$projects,
            'projectIds'=>$projectIds,
            'companysName'=>$companysName,
            'tagName'=>$tagName,
            'positions'=>$positions,
            'allowTagCategorys'=>$allowTagCategorys,
            'defaultTagCategorys'=>$defaultTagCategorys,
            'adminName'=>$adminName,
        ]);
    }

    //删除客户
    public function del($id) {

        $customer = Customer::find($id);

        if ($customer){
            $customer->is_del = 1;
            if ($customer->save()){
                return redirect('Manage/Customer/index')->with('success','删除成功！');
            }else{
                return redirect()->back()->with('error','删除失败，请重试！');
            }
        }else{
            return redirect('Manage/Customer/index')->with('error','信息错误！');
        }

    }

    //导出
    public function export(Request $request){
        $search = $request->input();
        foreach ($search as $k=>$v){
            if (!is_array($search[$k]) && ($search[$k] === '' || $search[$k] === 'none')){
                unset($search[$k]);
            }
        }

        $filed = isset($search['Filed']) ? $search['Filed'] : [];
        if (count($filed)){

            $admin = Admin::where(['is_del'=>0])->select(['id','real_name'])->get();
            $tmp = [0=>'系统'];
            foreach ($admin as $v){
                $tmp[$v->id] = $v->real_name;
            }
            $admin = $tmp;

            $position = Position::where('is_del','0')->select(['id','position_name'])->get();
            $tmp = [];
            foreach ($position as $v){
                $tmp[$v->id] = $v->position_name;
            }
            $positionName = $tmp;

            $tags = Tag::where('is_del','0')->select(['id','tag_name'])->get();
            $tmp = [];
            foreach ($tags as $v){
                $tmp[$v->id] = $v->tag_name;
            }
            $tags = $tmp;

            foreach ($search as $k=>$v){
                if (!is_array($search[$k]) && $search[$k] === ''){
                    unset($search[$k]);
                }
            }

            $customers = $this->getResult($search);

            $exportData = [];
            $title = [];
            foreach ($filed as $k=>$v){
                if (isset($this->layoutConfig['formHeader'][$k]['name'])){
                    $title[] = $this->layoutConfig['formHeader'][$k]['name'];
                }else{
                    return redirect('Manage/Customer/index')->with('error','信息错误！');
                }
            }

            foreach ($customers as $val){
                $arr = [];
                foreach ($filed as $fK=>$fV){
                    switch ($fK){
                        case 'sex':
                            $arr['sex'] = $this->sexArr[$val->sex];
                            break;
                        case 'is_lock':
                            $arr['is_lock'] = $this->lockArr[$val->is_lock];
                            break;
                        case 'type':
                            $arr['type'] = $this->typeArr[$val->type];
                            break;
                        case 'is_investor';
                            $arr['is_investor'] = $this->whether[$val->is_investor];
                            break;
                        case 'positions':
                            $positionIds = explode(',',$val->position_ids);
                            $positions = '';
                            foreach ($positionIds as $pV){
                                if ($pV > 0 && isset($positionName[$pV])){
                                    $positions = $positions ? $positions.'|'.$positionName[$pV] : $positionName[$pV];
                                }
                            }
                            $arr['positions'] = '"'.$positions.'"';
                            break;
                        case 'tag_ids':
                            $tagIds = explode(',',$val->tag_ids);
                            $tag = '';
                            foreach ($tagIds as $tV){
                                if ($tV > 0 && isset($tags[$tV])){
                                    $tag = $tag ? $tag.'|'.$tags[$tV] : $tags[$tV];
                                }
                            }
                            $arr['tag_ids'] = '"'.$tag.'"';
                            break;
                        case 'source':
                            $arr['source'] = isset($admin[$val->source]) ? $admin[$val->source] : '无';
                            break;
                        case 'created_at':
                            $arr['created_at'] = DataHandle::timeFormat($val->created_at,'Y-m-d H:i:s');
                            break;
                        default:
                            $arr[$fK] = '"'.$val->$fK.'"';
                    }

                }
                $exportData[] = $arr;
            }

            $fileName = '用户报表'.date('YmdHis');
            DataHandle::exportCsv($exportData, $title, $fileName);
        }else{
            return redirect('Manage/Customer/index')->with('error','请先选择内容！');
        }
    }

    public function ajaxLock(Request $request){
        if ($request->ajax()){

            $id = $request->input('id');
            $isLock = $request->input('is_lock');
            $customer = Customer::find($id);
            if ($customer){
                $customer->is_lock = intval($isLock);
                if ($customer->save()){
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

    

    public function getDetail(Request $request){
        if ($request->ajax()){
            $id = $request->input('id');
            $customer = Customer::find($id);
            if ($customer){
                return $this->json(1,[
                    'introduction'=>$customer->introduction
                ]);

            }else {
                return $this->json(9002);
            }
        }else{
            return $this->json(9003);
        }
    }

    private function getCustomerIdsByUid($uid){
        $customers = Customer::where(['uid'=>$uid,'is_del'=>0])->select(['id'])->get();
        $idsArr = [];
        if (count($customers)){
            foreach ($customers as $v){
                $idsArr[] = $v->id;
            }
        }
        return $idsArr;
    }
}