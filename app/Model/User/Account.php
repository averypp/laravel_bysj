<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
class Account extends Model
{
    protected $connection = 'zero_one_platform_user';

    protected $table = 'account';

    protected $fillable = [
        'uid',
        'account',
        'pwd',
        'auth_status',
        'company_id',
        'wx_openid',
        'wx_unionid',
        'last_login_ip',
        'last_login_at',
        'is_lock',
        'is_del',
        'remark',
        'reg_time',
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