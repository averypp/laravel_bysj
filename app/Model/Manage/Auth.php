<?php
/**
 * Created by PhpStorm.
 * User: shamo
 * Date: 12/29/2016
 * Time: 5:36 PM
 */
namespace App\Model\Manage;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table = 'auth';
    protected $connection = 'zero_one_platform_manage';
    protected $fillable = [
        'group_id',
        'module',
        'index',
        'add',
        'edit',
        'del',
        'modify_state',
        'import',
        'export',
        'examine',
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