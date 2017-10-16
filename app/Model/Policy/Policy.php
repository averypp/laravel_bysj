<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Policy;

use Illuminate\Database\Eloquent\Model;
class Policy extends Model
{
    protected $connection = 'zero_one_platform_policy';

    protected $table = 'policy';

    protected $fillable = [
        'policy_name',
        'policy_no',
        'policy_type_ids',
        'policy_name_web',
        'province',
        'city',
        'area',
        'policy_conditions',
        'policy_jiangli',
        'policy_limits',
        'request_datas',
        'deptartment_id',
        'amount',
        'start_day',
        'end_day',
        'web_url',
        'condition_registered_capital',
        'condition_workers_num',
        'condition_technology_workers_num',
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
}