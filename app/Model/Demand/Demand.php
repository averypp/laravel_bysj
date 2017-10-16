<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Demand;

use Illuminate\Database\Eloquent\Model;
class Demand extends Model
{
    protected $connection = 'zero_one_platform_demand';

    protected $table = 'demand';

    protected $fillable = [
        'uid',
        'company_id',
        'demand_title',
        'demand_sn',
        'type',
        'service_range_id',
        'finance_flag',
        'evaluate',
        'finance_company',
        'introduct_business',
        'context',
        'payment_detail',
        'status',
        'examine_time',
        'auditor',
        'follow_up',
        'show_priv',
        'end_time',
        'remark',
        'is_del',
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
    // public static function getInfo($id){
    //     return self::where(['id'=>$id,'is_del'=>0])->first();
    // }
}