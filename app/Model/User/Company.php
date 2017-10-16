<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
class Company extends Model
{
    protected $connection = 'zero_one_platform_user';

    protected $table = 'company';

    protected $fillable = [
        'company_name',
        'credit_code',
        'legal_person',
        'bank',
        'bank_account',
        'license_url',
        'Date_of_establishment',
        'registered_capital',
        'registered_district_id',
        'registered_address',
        'office_address',
        'industry_id',
        'examine_time',
        'status',
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