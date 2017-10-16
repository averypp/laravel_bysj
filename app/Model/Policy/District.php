<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Policy;

use Illuminate\Database\Eloquent\Model;
class District extends Model
{
    protected $connection = 'zero_one_platform_policy';

    protected $table = 'district';

    protected $fillable = [
        'district_name',
        'district_type',
        'pid',
        'remark',
    ];

    public $timestamps = true;

    protected function getDateFormat()
    {
        return time();
    }

    protected function asDateTime($value)
    {
        return $value;
    }

    public static function getInfo($id){
        return self::where(['id'=>$id,'is_del'=>0])->first();
    }

    public static function getDistrict($subscript = 0){
        $district = self::where(['is_del'=>0])->get();
        $r_data = [
            'province'=>[],
            'city'=>[],
            'area'=>[],
        ];
        if ($district){
            foreach ($district as $v){
                switch ($v['district_type']){
                    case '1':
                        $tmp = [
                            'id'=>$v->id,
                            'district_name'=>$v->district_name,
                        ];
                        if ($subscript === 1){
                            $r_data['province'][$v->id] = $v->district_name;
                        }else{
                            $r_data['province'][] = $tmp;
                        }
                        break;
                    case '2':
                        !isset($r_data['city'][$v->pid]) ? $r_data['city'][$v->pid]=[] : NULL;
                        $tmp = [
                            'id'=>$v->id,
                            'district_name'=>$v->district_name,
                        ];
                        if ($subscript === 1){
                            $r_data['city'][$v->id] = $v->district_name;
                        }else{
                            $r_data['city'][$v->pid][] = $tmp;
                        }
                        break;
                    case '3':
                        !isset($r_data['area'][$v->pid]) ? $r_data['area'][$v->pid]=[] : NULL;
                        $tmp = [
                            'id'=>$v->id,
                            'district_name'=>$v->district_name,
                        ];
                        if ($subscript === 1){
                            $r_data['area'][$v->id] = $v->district_name;
                        }else{
                            $r_data['area'][$v->pid][] = $tmp;
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        return $r_data;
    }
}