<?php

namespace App\Models\Flyer;

use App\Models\MainModel;

class DisplayTargetFlyerAO extends MainModel
{
    /**
    * @var $table
    */
    protected $table = 'T_DisplayTargetFlyerAO';

    /**
    * @var $primaryKey
    */
    protected $primaryKey = 'displayTargetFlyerAOId';

    /**
    * @var $fillable
    */
    protected $fillable = [
        'flyerPlanId',
        'affiliationOfficeId',
        'updateDate',
        'updateUser',
        'delFlg'
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
