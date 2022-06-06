<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayTargetStampUB extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetStampUB';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetStampUBId';
    
    /**
     * @var $fillable
     */
    protected $fillable = [
        'stampPlanId',
        'utilizationBusinessId',
        'updateDate',
        'updateUser',
        'delFlg',
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'isEmpty',
        'isNotEmpty',
    ];
}
