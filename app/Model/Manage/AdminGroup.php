<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-11-29
 * Time: 11:50
 */
namespace App\Model\Manage;

use Illuminate\Database\Eloquent\Model;

class AdminGroup extends Model
{

    protected $table = 'admin_group';
    protected $connection = 'zero_one_platform_manage';
    protected $fillable = [
        'group_name',
        'pid',
        'parent_ids',
        'type',
        'is_lock',
        'is_del',
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
}