<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StampPlanTargetClass extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_StampPlanTargetClass';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'stampPlanTargetClassId';
    
    /**
     * @var $fillable
     */
    protected $fillable = [
        'stampPlanId',
        'departmentCode',
        'updateDate',
        'updateUser',
        'delFlg',
    ];
    
}
