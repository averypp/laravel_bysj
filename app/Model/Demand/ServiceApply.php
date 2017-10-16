<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Demand;

use Illuminate\Database\Eloquent\Model;
class ServiceApply extends Model
{
    protected $connection = 'zero_one_platform_demand';

    protected $table = 'service_apply';

    protected $fillable = [
        'company_id',
        'instruct',
        'honor',
        'honor_json',
        'licenses',
        'licenses_json',
        'logo',
        'publicity',
        'examine_time',
        'auditor',
        'status',
        'service_range_id',
        'remark',
        'is_del',
        'updated_at',
        'created_at',
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