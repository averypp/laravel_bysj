<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Demand;

use Illuminate\Database\Eloquent\Model;
class DemandOrder extends Model
{
    protected $connection = 'zero_one_platform_demand';

    protected $table = 'order';

    protected $fillable = [
        'demand_id',
        'uid',
        'company_id',
        'service_uid',
        'service_company_id',
        'order_sn',
        'is_appraise',
        'appraise_content',
        'appraise_time',
        'follow_up',
        'remark',
        'is_del',
        'order_status',
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