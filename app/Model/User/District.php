<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
class District extends Model
{
    protected $connection = 'zero_one_platform_user';

    protected $table = 'district';

    protected $fillable = [
        'parent_id',
        'district_name',
        'district_type',
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