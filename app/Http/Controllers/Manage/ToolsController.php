<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 12/16/2016
 * Time: 11:20 AM
 */
namespace App\Http\Controllers\Manage;

use App\Http\Controllers\BaseController;
use App\Tools\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ToolsController extends BaseController
{
    public $config;
    //允许上传的文件类型
    public $fileExt = ['pdf','doc','docx','ppt','pptx','xls','xlsx'];
    //允许上传的图片类型
    public $imgExt = ['png','jpg','jpeg','gif','PNG','JPG','JPEG','GIF'];

    public function __construct(Request $request)
    {
        $this->config = Config::get('filesystems');
        parent::__construct($request);
    }

    //文件上传
    public function upload(Request $request){
        $file = $request->file('files');
        if ($file){
            //文件是否上传成功
            if ($file->isValid()){
                //源文件名
                $originalName = $file->getClientOriginalName();
                //文件拓展名
                $ext = $file->getClientOriginalExtension();
                //MimeType
                $type = $file->getClientMimeType();
                //源文件路径
                $realPath = $file->getRealPath();

                //判断文件格式
                if (!in_array($ext, $this->fileExt)){
                    return $this->json(2001);
                }

                $now = time();
                $fileName = date('Y/m/d',$now).'/'.date('YmdHis',$now).uniqid().'.'.$ext;


                $bool = Storage::disk('file')->put($fileName,file_get_contents($realPath));
                if ($bool){
                    $response = [
                        'name'=>$originalName,
                        'path'=>$this->config['disks']['file']['root'].'/'.$fileName,
                    ];
                    return $this->json(1,$response,'上传成功');
                }else{
                    return $this->json(9001);
                }
            }else{
                return $this->json(9001);
            }
        }else{
            return $this->json(9002);
        }

    }

    //图片上传
    public function uploadImg(Request $request){
        $file = $request->file('files');
        if ($file){
            //文件是否上传成功
            if ($file->isValid()){
                //源文件名
                $originalName = $file->getClientOriginalName();
                //文件拓展名
                $ext = $file->getClientOriginalExtension();
                //MimeType
                $type = $file->getClientMimeType();
                //源文件路径
                $realPath = $file->getRealPath();

                //判断文件格式
                if (!in_array($ext, $this->imgExt)){
                    return $this->json(2001);
                }

                $now = time();
                $fileName = date('Y/m/d',$now).'/'.date('YmdHis',$now).uniqid().'.'.$ext;


                $bool = Storage::disk('image')->put($fileName,file_get_contents($realPath));
                if ($bool){
                    $response = [
                        'name'=>$originalName,
                        'path'=>$this->config['disks']['image']['root'].'/'.$fileName,
                    ];
                    return $this->json(1,$response,'上传成功');
                }else{
                    return $this->json(9001);
                }
            }else{
                return $this->json(9001);
            }
        }else{
            return $this->json(9002);
        }

    }

    //获取列表数据
    public function getDataList(Request $request){
        if ($request->ajax()){
            $typeId = $request->input('typeId');
            $keyword = $request->input('keyword');
            $isInvestor = $request->input('isInvestor');

            $list = NULl;
            $data = [];
            if ($typeId == 1){
                //基金
                $list = Fund::where(['is_del'=>0]);
                if ($keyword != ''){
                    $list = $list->where('fund_name','like','%'.$keyword.'%');
                }
                $list = $list->select(DB::raw('id, fund_name as name'))->get();

            }elseif ($typeId == 2){
                //公司
                $list = Company::where(['is_del'=>0]);
                if ($keyword != ''){
                    $list = $list->where('company_name','like','%'.$keyword.'%');
                }
                $list = $list->select(DB::raw('id, company_name as name'))->get();
            }elseif($typeId == 3){
                //客户
                $list = Customer::where('customer.is_del','=',0)->leftJoin('company', 'customer.company_id', '=', 'company.id');
                if ($isInvestor == 1){
                    $list = $list->where('customer.is_investor','=',1);
                }
                if ($keyword != ''){
                    $list = $list->where('customer.real_name','like','%'.$keyword.'%');
                }
                $list = $list->select(DB::raw('zc_customer.id, zc_customer.real_name as name,zc_customer.mobile,zc_company.company_name'))->get();
            }elseif ($typeId == 4){
                //职位
                $list = Position::where(['is_del'=>0]);
                if ($keyword != ''){
                    $list = $list->where('position_name','like','%'.$keyword.'%');
                }
                $list = $list->select(DB::raw('id, position_name as name'))->get();
            }

            if ($list){
                foreach ($list as $v){
                    $data[] = [
                        'key'=>$v->id,
                        'value'=>$v->name.(isset($v->company_name)?'-'.$v->company_name:'').($v->mobile?'-'.$v->mobile:''),
                    ];
                }
            }
            return $this->json(1,[
                'list'=>$data
            ]);

        }else{
            return $this->json(9003);
        }
    }

    public function ajaxPutObject(Request $request){
        if ($request->ajax()){
            $op = $request->input('op');
            $name = trim($request->input('name'));
            $categoryId = intval($request->input('categoryId'));

            if ($name == ''){
                return $this->json(9002);
            }
            $object = NULL;

            switch ($op){
                case 'tags':
                    $object = Tag::where(['tag_name'=>$name,'category_id'=>$categoryId,'is_del'=>0])->first();
                    if (!$object){
                        $data = [
                            'tag_name'=>$name,
                            'category_id'=>$categoryId,
                            'pid'=>0,
                            'remark'=>'',
                        ];
                        $object = Tag::create($data);
                    }
                    break;
                case 'company':
                    $object = Company::where(['company_name'=>$name,'is_del'=>0])->first();
                    if (!$object){
                        $data = [
                            'company_name'=>$name,
                            'abbreviation'=>'',
                            'corporation'=>0,
                            'tel'=>'',
                            'code'=>'',
                            'province'=>'',
                            'city'=>'',
                            'area'=>'',
                            'remark'=>'',
                        ];
                        $object = Company::create($data);
                    }

                    break;

                case 'fund':
                    $object = Fund::where(['fund_name'=>$name,'is_del'=>0])->first();
                    if (!$object){
                        $data = [
                            'fund_name'=>$name,
                            'remark'=>'',
                        ];
                        $object = Fund::create($data);
                    }

                    break;
                case 'customer':
                    $object = Customer::where(['real_name'=>$name,'is_del'=>0])->first();
                    if (!$object){
                        $data = [
                            'uid'=>md5($name.time()),
                            'real_name'=>$name,
                            'remark'=>'',
                        ];
                        $object = Customer::create($data);
                    }

                    break;
                case 'position':
                    $object = Position::where(['position_name'=>$name,'is_del'=>0])->first();
                    if (!$object){
                        $data = [
                            'position_name'=>$name,
                            'remark'=>'',
                        ];
                        $object = Position::create($data);
                    }
                    break;

                case 'industry_tags':
                    $object = Tag::where(['tag_name'=>$name,'type'=>2,'is_del'=>0])->first();
                    if (!$object){
                        $data = [
                            'tag_name'=>$name,
                            'type'=>2,
                            'remark'=>'',
                        ];
                        $object = Tag::create($data);
                    }
                    break;

                case 'business_tags':
                    $object = Tag::where(['tag_name'=>$name,'type'=>3,'is_del'=>0])->first();
                    if (!$object){
                        $data = [
                            'tag_name'=>$name,
                            'type'=>3,
                            'remark'=>'',
                        ];
                        $object = Tag::create($data);
                    }
                    break;

                case 'customer_tags':
                    $object = Tag::where(['tag_name'=>$name,'is_del'=>0])->first();
                    if (!$object){
                        $data = [
                            'tag_name'=>$name,
                            'remark'=>'',
                        ];
                        $object = Tag::create($data);
                    }
                    break;

                default:
                    return $this->json(9002);
            }

            if ($object){
                return $this->json(1,[
                    'key'=>$object->id,
                    'value'=>$name,
                ]);
            }else{
                return $this->json(3001);
            }
        }else{
            return $this->json(9003);
        }
    }

    public function getDetail(Request $request){
        if ($request->ajax()){
            $id = $request->input('id');
            $modelName = $request->input('modelName');
            if ($modelName == 'ProjectBase'){
                $objName = 'App\Model\Manage\Project';
            }else{
                $objName = 'App\Model\Manage\\'.$modelName;
            }

            $model = new $objName();
            $data = $model::find($id);

            if (count($data)){
                if (isset($data->is_del) && $data->is_del===1){
                    return $this->json(1,[
                        'html'=>'信息已被删除'
                    ]);
                }
                $formConfig = Config::get('DbFieldName');
                $layoutConfig = isset($formConfig['Manage'][$modelName]) ? $formConfig['Manage'][$modelName] : [];

                $constant = [
                    'is_lock'=>$this->lockArr,
                    'sex'=>$this->sexArr,
                ];
                $html = Html::detailLayoutCreate($data, $constant, $layoutConfig);
                $btn = '';
                if (isset($_SESSION['admin_type']) && $_SESSION['admin_type']==1){
                    $btn = '<div><button onclick="goEdit('."'".'/Manage/'.$modelName.'/edit/'.$id."'".')" class="btn btn-sm btn-info">编辑</button></div><br/>';
                }else{
                    if (isset($_SESSION['admin_auths'][$modelName]) && in_array('edit',$_SESSION['admin_auths'][$modelName])){
                        $btn = '<div><button onclick="goEdit('."'".'/Manage/'.$modelName.'/edit/'.$id."'".')" class="btn btn-sm btn-info">编辑</button></div><br/>';
                    }
                }

                return $this->json(1,[
                    'html'=>$btn.$html
                ]);

            }else {
                return $this->json(9002);
            }
        }else{
            return $this->json(9003);
        }

    }
}