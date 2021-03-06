<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Demand;

use Illuminate\Database\Eloquent\Model;
class DemandComment extends Model
{
    protected $connection = 'zero_one_platform_demand';

    protected $table = 'demand_comment';

    protected $fillable = [
        'demand_id',
        'uid',
        'upid',
        'content',
        'type',
        'is_del',
        'is_lock',
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