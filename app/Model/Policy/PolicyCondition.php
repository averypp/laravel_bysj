<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-12-28
 * Time: 11:50
 */
namespace App\Model\Policy;

use Illuminate\Database\Eloquent\Model;
class PolicyCondition extends Model
{
    protected $connection = 'zero_one_platform_policy';

    protected $table = 'policy_conditions';

    protected $fillable = [
        'policy_id',
        'registered_capital',
        'workers_num',
        'technology_workers_num',
        'register_month',
        'net_assets',
        'net_profit',
        'r_d_expenses',
        'sales_revenue',
        'invention_patent',
        'utility_model_patent',
        'appearance_patent',
        'new_variety',
        'registered_trademark',
        'software_copyright',
        'integrated_circuit',
        'r_d_equipment_cost',
        'independent_project',
        'entrepreneur_type',
        'social_insurance',
        'labor_contract',
        'equity_investment',
        'credit_loan',
        'technology_insurance',
        'r_d_site_area',
        'branch_board',
        'new_three_plate_agreement',
        'shareholding_system_reform',
        'incubator',
        'guarantee_fee',
        'rent_time',
        'buy_technology_services',
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

    public static function getInfo($policy_id){
        return self::where(['policy_id'=>$policy_id])->first();
    }
}