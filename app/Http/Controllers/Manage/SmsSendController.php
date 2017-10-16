<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 9/23/2017
 * Time: 11:32 AM
 */
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Model\Sms\SmsSend;
use App\Tools\Html;
use Illuminate\Http\Request;

class SmsSendController extends BaseController
{
    public $layout = 'smsSend';

    protected $module = 'SmsSend';

    public $model;

    protected $templateIds = [
        '100001'=>'注册验证码',
        '100002'=>'注册验证码2',
    ];

    protected $statusArr = [
        0=>'待发送',
        1=>'发送成功',
        2=>'发送失败'
    ];

    public function __construct(Request $request)
    {
        $this->model = new SmsSend();
        $this->_init();
        parent::__construct($request, $this->layout);
    }

    protected function _init(){
        $viewData = [
            'templateIds'=>$this->templateIds
        ];
        $this->viewData = array_merge ($this->viewData,$viewData);
    }

    public function index(Request $request){
        $search = $request->input();

        $list = $this->getResult($search, $this->row);

        if (count($list)){
            foreach ($list as &$v){
                $v->template_name = $this->templateIds[$v->template_id];
                $v->operation = [
                    'module'=>$this->module,
                    'obj'=>$v,
                    'option'=>$this->option,
                    'filtration'=>[],
                ];
            }
        }

        $templateIds = ['none'=>'不限'] + $this->templateIds;
        $selectData = [
            'template_id'=>$templateIds,
            'status'=> ['none'=>'不限'] + $this->statusArr
        ];
        $constant = [
            'template_id'=>$this->templateIds,
            'status'=> $this->statusArr
        ];
        $form = Html::formCreate($list,$search, $selectData, $constant, $this->option, $this->layoutConfig);

        return $this->myView($this->layout.'.index',[
            'form'=>$form,
            'search'=>$search,
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

        $obj = $this->model;

        if (isset($search['template_id'])){
            $obj = $obj->where('template_id',$search['template_id']);
        }
        if (isset($search['status'])){
            $obj = $obj->where('status',$search['status']);
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