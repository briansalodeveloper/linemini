<?php

namespace App\Models;

class Store extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'M_Store';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'departmentClassificationId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'storeId',
        'storeName',
        'updateDate',
        'updateUser',
        'delFlg'
    ];

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    public function coupons()
    {
        return $this->belongsToMany(CouponPlan::class, 'T_CuponPlanStore', 'storeId', 'couponPlanId', 'storeId', 'couponPlanId')->where('T_CuponPlanStore.delFlg', CouponPlan::STATUS_NOTDELETED);
    }
}
