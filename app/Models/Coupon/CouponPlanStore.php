<?php

namespace App\Models\Coupon;

use App\Models\MainModel;

class CouponPlanStore extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_CuponPlanStore';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'cuponPlanProductId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'cuponPlanId',
        'storeId',
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
