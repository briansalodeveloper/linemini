<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayTargetStampAO extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetStampAO';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetStampAOId';
    
    /**
     * @var $fillable
     */
    protected $fillable = [
        'stampPlanId',
        'affiliationOfficeId',
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
