<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-11-29
 * Time: 11:50
 */
namespace App\Model\Manage;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    //  $users = DB::connection('mysql2')->select(...);
    //  $someModel = new SomeModel;
    //  $something = $someModel->find(1);
    protected $connection = 'zero_one_platform_manage';
    protected $table = 'admin';

    protected $fillable = [
        'account',
        'pwdmd5',
        'real_name',
        'mobile',
        'group_id',
        'position',
        'is_lock',
        'remark'
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