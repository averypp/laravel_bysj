<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
class Invite2company extends Model
{
    protected $connection = 'zero_one_platform_user';

    protected $table = 'invite2company';

    protected $fillable = [
        'inviter_uid',
        'invitee_uid',
        'invite_state',
        'remark',
        'company_id',
        'create_at',
        'update_at',
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