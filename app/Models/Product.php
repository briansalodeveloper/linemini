<?php

namespace App\Models;

class Product extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'M_Product';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'productId';

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    /**
     * Product Code length.
     */
    const CHARCOUNT_PRODUCTCODE = 13;

    /*======================================================================
     * STATIC FUNCTIONS
     *======================================================================*/

    /**
     * Check if Product exists based on productCode.
     *
     * @param int $productCode
     *
     * @return boolean
     */
    public static function isProductCodeExist($productCode)
    {
        return self::where('productCode', $productCode)->where('delFlg', 0)->exists();
    }

    /**
     * retrieve matching products with the given productCode.
     *
     * @param int $productCode
     *
     * @return boolean
     */
    public static function matchingProductCode($productCode)
    {
        return self::where('productCode', $productCode)->where('delFlg', 0)->first();
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * Many-to-many relationship between M_Product and M_CouponPlan tables, with T_CuponPlanProduct as pivot table.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function coupons()
    {
        return $this->belongsToMany(CouponPlan::class, 'T_CuponPlanProduct', 'productJancode', 'cuponPlanId', 'productCode', 'couponPlanId')->where('T_CuponPlanProduct.delFlg', CouponPlan::STATUS_NOTDELETED);
    }

    public function stamp()
    {
        return $this->belongsToMany(StampPlan::class, 'T_StampPlanProduct', 'productJancode', 'stampPlanId', 'productCode', 'stampPlanId')->where('T_StampPlanProduct.delFlg', StampPlan::STATUS_NOTDELETED);
    }
}
