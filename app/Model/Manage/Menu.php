<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 2016-11-29
 * Time: 11:50
 */
namespace App\Model\Manage;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $table = 'menu';
    protected $connection = 'zero_one_platform_manage';
    protected $fillable = [
        'name',
        'module',
        'icon',
        'action',
        'pid',
        'priority',
        'is_del',
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
}