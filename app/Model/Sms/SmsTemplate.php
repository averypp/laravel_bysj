<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Sms;

use Illuminate\Database\Eloquent\Model;
class SmsTemplate extends Model
{
    protected $connection = 'zero_one_platform_sms';

    protected $table = 'sms_template';

    protected $fillable = [
        'template_id',
        'template_content',
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