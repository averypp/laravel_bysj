<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Demand;

use Illuminate\Database\Eloquent\Model;
class Dynamic extends Model
{
    protected $connection = 'zero_one_platform_demand';

    protected $table = 'demand_dynamic';
    protected $fillable = [
        'uid',
        'did',
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
}