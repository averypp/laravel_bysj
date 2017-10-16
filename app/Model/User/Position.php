<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
class Position extends Model
{
    protected $connection = 'zero_one_platform_user';

    protected $table = 'position';

    protected $fillable = [
        'position_name',
    ];

    public $timestamps = false;

    protected function getDateFormat()
    {
        return time();
    }

    protected function asDateTime($value)
    {
        return $value;
    }
    public static function getPositionId($position_name){
        $postionInfo = Position::where(['position_name'=>$position_name])->first();
        if($postionInfo){
            return  $postionInfo->id;
        } else {
            $positionData = [
                    'position_name'=>$position_name,
            ];
            $newPostionInfo = Position::create($positionData);
            return  $newPostionInfo->id;
        }
    }
    public static function getPositionNameById($ids){
        $html = '';
        if ($ids){
            $rs = Position::where('id','>',0)->whereIn('id',explode(',',$ids))->select(['position_name'])->get();
            if (count($rs)){
                foreach ($rs as $v){
                    $html = $html ? $html.' | '.$v->position_name : $v->position_name;
                }
            }
        }
        return $html;
    }
}