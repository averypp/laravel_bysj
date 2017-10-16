<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Demand;

use Illuminate\Database\Eloquent\Model;
class ServiceRange extends Model
{
    protected $connection = 'zero_one_platform_demand';

    protected $table = 'service_range';

    protected $fillable = [
        'range_name',
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