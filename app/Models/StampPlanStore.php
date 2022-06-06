<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StampPlanStore extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_StampPlanStore';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'T_StampPlanStoreId';
    
    /**
     * @var $fillable
     */
    protected $fillable = [
        'stampPlanId',
        'storeId',
        'updateDate',
        'updateUser',
        'delFlg',
    ];
}
