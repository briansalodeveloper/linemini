<?php

namespace App\Models\Content;

use App\Models\MainModel;

class DisplayTargetContentAO extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetContentAO';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetContentAOId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'contentPlanId',
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
