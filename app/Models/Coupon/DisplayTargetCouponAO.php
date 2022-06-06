<?php

namespace App\Models\Coupon;

use App\Models\MainModel;

class DisplayTargetCouponAO extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetCuponAO';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetCuponAOId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'cuponPlanId',
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
        'isNotEmpty'
    ];
}
