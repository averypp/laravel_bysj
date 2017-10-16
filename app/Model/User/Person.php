<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-11-29
 * Time: 11:50
 */
namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    protected $connection = 'zero_one_platform_user';

    protected $table = 'person';

    protected $fillable = [
        'uid',
        'real_name',
        'identification',
        'idcard_front_url',
        'idcard_back_url',
        'sex',
        'industry_id',
        'work_startdate',
        'work_year',
        'examine_time',
        'updated_at',
        'created_at',
        'status',
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