<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-12
 * Time: 16:28
 */

namespace App\Tools;

use App\Model\Manage\Admin;
use App\Model\User\Company;
use App\Model\User\Position;
use App\Model\User\Account;
use App\Model\User\AccountInfo;
use App\Model\Demand\ServiceRange;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class Html
{

    //项目评级
    public static $projectGradeArr = [
        0=>'未定',
        1=>'A',
        2=>'B',
        3=>'C',
        4=>'D',
    ];

    //项目类型
    public static $projectTypeArr = [
        0=>'未定',
        1=>'外投BP',
        2=>'自己客户'
    ];

    public static $whether = [
        0=>'否',
        1=>'是',
    ];
    /**
     * 生成报表
     * @param $data
     * @param $search
     * @param $selectData
     * @param $constant
     * @param $option
     * @param $layoutConfig
     * @return array
     */
    public static function formCreate($data, $search, $selectData, $constant, $option, $layoutConfig){
        $form = [
            'formSearch'=>'',
            'formHeader'=>'',
            'formBody'=>'',
            'page'=>'',
        ];

        $filed = isset($search['Filed']) ? $search['Filed'] : [];

        $formSearchMore = '';
        $formSearchMoreShow = 'none';

        if (count($layoutConfig['formFiled'])){
            $form['formSearch'] .= '<div class="col-sm-12"><label class="">内容：</label>';
            foreach ($layoutConfig['formFiled'] as $v) {
                $name = isset($layoutConfig['formHeader'][$v]['name']) ? $layoutConfig['formHeader'][$v]['name'] : '';
                $form['formSearch'] .= '<label class="checkbox-inline i-checks">
                                        <input type="checkbox" value="1" name="Filed['.$v.']" '.( (isset($filed[$v])&&$filed[$v]==1)?'checked':'' ).'>'.$name.'</label>';

//                if (isset($filed[$v]) && $filed[$v]!=='' && $filed[$v]!=='none'){
//                    $formSearchMoreShow = 'block';
//                }
            }
            $form['formSearch'] .= '</div>';
        }

        if (count($layoutConfig['formSearch'])){
            foreach ($layoutConfig['formSearch'] as $k=>$v){
                $h = '';
                switch ($v['type']){
                    case 'text':
                        $span = $v['title'] ? '<span class="input-group-addon">'.$v['title'].'</span>' : '';
                        $h .= '<div class="col-sm-3 m-b-xs"><div class="input-group">'.$span.'<input type="text" placeholder="'.$v['display'].'" name="'.$k.'" value="'.(isset($search[$k]) ? $search[$k] : $v['default']).'" class="input-sm form-control '.$v['class'].'"></div></div>';
                        break;

                    case 'select':
                        $span = $v['title'] ? '<span class="input-group-addon">'.$v['title'].'</span>' : '';
                        $optionStr = '';
                        if (isset($selectData[$k])){
                            foreach ($selectData[$k] as $sK=>$sV){
                                $optionStr .= '<option value="'.$sK.'" '.( (string)$sK==(isset($search[$k]) ? $search[$k] : $v['default']) ? 'selected' : '').'>'.$sV.'</option>';
                            }
                        }
                        $h .= '<div class="col-sm-3 m-b-xs"><div class="input-group">'.$span.'<select id="'.$v['id'].'" name="'.$k.'" class="form-control '.$v['class'].'">'.$optionStr.'</select></div></div>';
                        break;

                    default:
                        break;
                }

                if ($v['is_show'] == 1){
                    $form['formSearch'] .= $h;
                }else{
                    if (isset($search[$k]) && $search[$k]!=='' && $search[$k]!=='none'){
                        $formSearchMoreShow = 'block';
                    }
                    $formSearchMore .= $h;
                }
            }
        }


        if ($form['formSearch'] != ''){
            $export = in_array('export',$option) ? '<button type="submit" name="export" value="1" class="btn btn-sm btn-success" id="export"> 导出</button>' : '';
            $examine = in_array('examine',$option) ? '&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)"  class="btn btn-sm btn-primary" onclick="selectAll()">全选</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-primary" onclick="invertSelect()">反选</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-primary" onclick="examineAll(2)">通过</a>
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-primary" onclick="examineAll(3)">不通过</a>
                                        ' : '';
            $button = '<div class="col-sm-12 m-b-xs"><button type="submit" class="btn btn-sm btn-info" id="submit"> 搜索</button>&nbsp;<button type="submit" onclick="_reset()" class="btn btn-sm btn-danger"> 重置</button>&nbsp;'.$export.$examine.'</div>';

            if ($formSearchMore != ''){
                $formSearchMore = '<div class="col-sm-12 searchMore" style="display: '.$formSearchMoreShow.'; padding-left:0">'.$formSearchMore.'</div>';
                $form['formSearch'] = '<a href="javascript:void(0)" onclick="searchMore(this)" data-show="0" class="searchMoreButton"><i class="fa fa-navicon"></i>更多搜索条件</a><form method="get" action="" id="myFormId" name="myForm">'.csrf_field().$form['formSearch'].$formSearchMore.$button.'</form>';

            }else{
                $form['formSearch'] = '<form method="get" action="" id="myFormId" name="myForm">'.csrf_field().$form['formSearch'].$button.'</form>';
            }

        }

        if (count($filed)){
            foreach ($layoutConfig['formHeader'] as $k=>$v){
                if (!isset($filed[$k]) || $filed[$k]!=1){
                    unset($layoutConfig['formHeader'][$k]);
                }
            }
        }else{
            foreach ($layoutConfig['formHeader'] as $k=>$v){
                if (isset($v['is_show']) && $v['is_show']==0){
                    unset($layoutConfig['formHeader'][$k]);
                }
            }
        }



        $form['formHeader'] .= '<tr>';

        $orderBy = isset($_GET['orderby']) ? trim($_GET['orderby']) : '';

        foreach ($layoutConfig['formHeader'] as $k=>$v){
            if ($orderBy == $k && isset($_GET['sort'])){
                if (intval($_GET['sort'])===0){
                    $sortClass = 'desc';
                }else{
                    $sortClass = 'asc';
                }
            }else{
                $sortClass = 'sorting';
            }
            $sortAttr = isset($v['sort'])&&$v['sort']==1 ? 'class="sortBtn '.$v['class'].'" data-filed="'.$k.'"' : 'class="'.$v['class'].'"';
            $sortFont = isset($v['sort'])&&$v['sort']==1 ? '<font class="'.$sortClass.'"></font>' : '';
            $form['formHeader'] .= '<th '.$sortAttr.'>'.$v['name'].$sortFont.'</th>';
        }
        $form['formHeader'] .= '</tr>';
        if (count($data)){
            foreach ($data as $val){
                $form['formBody'] .= '<tr class="text-c">';
                foreach ($layoutConfig['formHeader'] as $k=>$v){
                    if ($v['func'] != ''){
                        $func = $v['func'];
                        $form['formBody'] .= '<td class="'.$v['class'].'">'.(self::$func($val->$k) ).'</td>';
                    }else{
                        $span = isset($constant[$k][$val->$k]) ? $constant[$k][$val->$k] : $val->$k;
                        $form['formBody'] .= '<td class="'.$v['class'].'">'.$span.'</td>';
                    }

                }

                $form['formBody'] .= '</tr>';
            }

        }
        $pageTotalHtml = method_exists($data,'total') ?
            '<div class="dataTables_info" id="DataTables_Table_0_info" role="alert" style="position: absolute;left: 1.5em;" aria-live="polite" aria-relevant="all">共 '.$data->appends($search)-> total().' 条记录</div>'
            : '';
        $form['page'] = $pageTotalHtml . (method_exists($data,'render') ? $data->appends($search)-> render() : '');

        return $form;
    }

    /**
     * 生成详情页面
     * @param $data
     * @param $layoutConfig
     * @return string
     */
    public static function detailLayoutCreate($data, $constant, $layoutConfig){
        $html = '';

        if (count($layoutConfig)){
            foreach ($layoutConfig as $k=>$v){
                if ($v['func'] != ''){
                    $func = $v['func'];
                    $html .= '<tr class="text-c"><td style="width: 8em"><label>'.$v['name'].'</label></td><td class="'.$v['class'].'">'.(self::$func($data->$k) ).'</td></tr>';
                }else{
                    $span = isset($constant[$k][$data->$k]) ? $constant[$k][$data->$k] : $data->$k;
                    $html .= '<tr class="text-c"><td style="width: 8em"><label>'.$v['name'].'</label></td><td class="'.$v['class'].'">'.$span.'</td></tr>';
                }

            }
        }

        if ($html != ''){
            $html = '<div class="table-responsive">
                        <table class="table">
                            '.$html.'
                        </table>
                    </div>';
        }
        return $html;
    }

    //缩略文字
    public static function breviary($str = ''){
        if (mb_strlen($str) > 4){
            $s = mb_substr($str,0,4,'utf-8').'...';
            $html = '<span>'.$s.'&nbsp;<a href="javascript:void(0)" onclick="_spread(this)">展开</a></span><span style="display:none">'.$str.'&nbsp;<a href="javascript:void(0)" onclick="_retract(this)">收起</a></span>';
        }else{
            $html = '<span>'.$str.'</span>';
        }

        return $html;
    }

    //动态添加
    public static function putObjectHtml($op){
        $html ='<div class="col-sm-3" style="padding-left: 0">
                <div class="input-group">
                    <input type="text" placeholder="" value="" data-op="'.$op.'" class="input-sm form-control">
                    <span class="input-group-btn">
                        <a class="btn btn-sm btn-primary" onclick="putObject(this)"> 添加</a>
                    </span>
                </div>
            </div>';
        return $html;
    }

    /**
     * 生成操作html
     * @param $module
     * @param $obj
     * @param $option
     * @param array $filtration
     * @return string
     */
    public static function operation($module, $obj, $option, $filtration=[]){
        $html = '';
        if (count($filtration)){
            $option = array_intersect($option, $filtration);
        }
        if(in_array('modify_state',$option)){
            $html .= '<a onclick="_lock(this,'.$obj->id.',\''.$module.'\')" s="'.$obj->is_lock.'" href="javascript:;" title="'.($obj->is_lock==0 ? '锁定' : '启用').'"><i class="fa '.($obj->is_lock==0 ? 'fa-ban' : 'fa-check' ).'"></i></a>&nbsp;';
        }
        if(in_array('edit',$option)){
            $html .= '<a title="编辑" href="'. url('Manage/'.$module.'/edit',['id'=>$obj->id]) .'"><i class="fa fa-edit"></i></a>&nbsp;';
        }
        if(in_array('del',$option)){
            $html .= '<a title="删除" href="'. url('Manage/'.$module.'/del',['id'=>$obj->id]) .'" class="delObj" ><i class="fa fa-remove"></i></a>';
        }

        return $html;
    }

    /**
     * 生成操作html
     * @param $d
     * @return string
     */
    public static function operation2($d){
        return self::operation($d['module'],$d['obj'],$d['option'],$d['filtration']);
    }

    public static function checkBoxHtml($d){
        $html = '';
        if ($d['status'] == 1){
            $html = '<input type="checkbox" class="i-checks" name="checkData[]" value="'.$d['id'].'">';
        }
        return $html;
    }
    //打开详情
    public static function detail($d){
        if (count($d)){
            $html = '<a href="javascript:void(0)" onclick="detail('.$d['parameter'].')">'.$d['span'].'</a>';
        }else{
            $html = '无';
        }

        return $html;
    }
    //
    public static function detailQuestion($d){
        if (count($d)){
            $html = '<a href="javascript:void(0)" onclick="detailQuestion('.$d['parameter']['customer_id'].','.$d['parameter']['type'].')">'.$d['span'].'</a>';
        }else{
            $html = '无';
        }

        return $html;
    }

    //打开详细信息
    public static function detailedInformation($d){
        $html = '<a href="javascript:void(0)" onclick="detailedInformation('.$d['id'].',\''.$d['module'].'\')">'.$d['span'].'</a>';
        return $html;
    }

    /**
     * a标签新页面打开
     * @param $url
     * @return string
     */
    public static function link($url){
        $html = '<a href="'.$url.'" target="_blank">'.$url.'</a>';
        return $html;
    }

    //时间格式化
    public static function timeFormat($time){
        return $time>0 ? date('Y-m-d H:i:s', $time) : '无';
    }

    public static function jsonDecode($json){
        return implode("<br/>",json_decode($json,true));
    }

    public static function imgHtml($url){
        $html = '';
        if ($url != ''){
            if (! strstr($url,'http')){
                $url = '/'.$url;
            }
            $html = '<img src="'.$url.'" style="width:25%" />';
        }

        return $html;
    }

    //金额单位转换
    public static function moneyFormat($money){
        return ($money / 10000);
    }

    //json转换内容字串
    public static function json2str($json){
        $arr = json_decode($json, true);
        return implode(',',$arr);
    }

    //查看附件
    public static function attachmentHtml($d){
        if (count($d)){
            $html = '<a href="javascript:void(0)" onclick="_attachment('.$d['parameter'].')">'.$d['span'].'</a>';
        }else{
            $html = '无';
        }

        return $html;
    }

    //审核
    public static function examineHtml($d){
        $html = '';

        if(in_array('examine',$d['option']) && $d['status'] == 0){
            $html = '<a title="通过" href="javascript:void(0)" onclick="examine(this,'.$d['id'].',1)">通过</a>
                                        <a title="不通过" href="javascript:void(0)" onclick="examine(this,'.$d['id'].',2)">不通过</a>';
        }

        return $html;
    }
    //发布
    public static function releaseHtml($d){
        $html = '';
        if(in_array('release',$d['option'])){
            $html = '<a title="不发布" href="javascript:void(0)" onclick="release(this,'.$d['id'].',0)">不发布</a>
                                        <a title="发布" href="javascript:void(0)" onclick="release(this,'.$d['id'].',1)">发布</a>';
        }

        return $html;
    }

    /**
     * 获取管理员姓名
     * @param $ids
     * @return string
     */
    public static function getAdminName($ids){
        $html = '';
        if ($ids){
            $rs = Admin::where(['is_del'=>0])->whereIn('id',explode(',',$ids))->select(['real_name'])->get();
            if (count($rs)){
                foreach ($rs as $v){
                    $html = $html ? $html.','.$v->real_name : $v->real_name;
                }
            }
        }
        return $html;
    }

    public static function json2imgHtml($json){
        $html = '';
        $arr = json_decode($json, true);
        if (count($arr)){
            foreach ($arr as $v){
                if ($v != ''){
                    if (! strstr($v,'http')){
                        $v = '/'.$v;
                    }
                    $html .= '<img src="'.$v.'" style="width:25%" />';
                }

            }
        }
        return $html;
    }

    public static function doSign($d){

        if ($d['is_sign'] == 1){
            $html = '已签到';
        }else{
            $html = '<a onclick="doSign(this,'.$d['id'].')" href="javascript:;" >签到</a>';
        }
        return $html;
    }


    public static function getWhether($key){
        return isset(self::$whether[$key]) ? self::$whether[$key] : '';
    }


    public static function searchCondition($d){
        $html = '<a href="'.url(\Request::path()).'?'.$d['conditionKey'].'='.$d['conditionVal'].'">'.$d['name'].'</a>';
        return $html;
    }
    /**
     * 获取职位名称
     * @param $ids
     * @return string
     */
    public static function getPositionName($ids){
        $html = '';
        if ($ids){
            $rs = Position::whereIn('id',explode(',',$ids))->select(['position_name'])->get();
            if (count($rs)){
                foreach ($rs as $v){
                    $html = $html ? $html.','.$v->position_name : $v->position_name;
                }
            }
        }
        return $html;
    }
    /**
     * 获取服务范围名称
     * @param $ids
     * @return string
     */
    public static function getServiceRangeName($ids){
        $html = '';
        if ($ids){
            $rs = ServiceRange::whereIn('id',explode(',',$ids))->select(['range_name'])->get();
            if (count($rs)){
                foreach ($rs as $v){
                    $html = $html ? $html.','.$v->range_name : $v->range_name;
                }
            }
        }
        return $html;
    }
    /**
     * 获取姓名
     * @param $ids
     * @return string
     */
    public static function getAccountByUid($uid){
        $account = '';
        if ($uid){
            $rs = Account::where(['uid'=>$uid])->select(['account'])->first();
            if (count($rs)){
                    $account = $rs->account;
            }
        }
        return $account;
    }

    public static function preHtml($html){
        return '<pre>'.$html.'</pre>';
    }

    public static function _echo($d){
        if (is_object($d)){
            return 'Object';
        }
        if (is_array($d)){
            return 'Array';
        }
        if ($d){
            return trim($d);
        }else{
            return '';
        }
    }
}