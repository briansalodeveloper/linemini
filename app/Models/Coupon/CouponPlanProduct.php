<?php

namespace App\Models\Coupon;

use App\Helpers\Upload;
use App\Models\MainModel;
use App\Models\Product;

class CouponPlanProduct extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_CuponPlanProduct';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'cuponPlanProductId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'cuponPlanId',
        'productName',
        'productJancode',
        'productImg',
        'productText'
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'isThumbnailExist',
        'isEmpty',
        'isNotEmpty'
    ];

    /**
     * isThumbnailExist
     *
     * @return bool $rtn
     */
    public function getIsThumbnailExistAttribute(): bool
    {
        $rtn = false;

        if (!empty($this->productImg)) {
            $rtn = Upload::exist($this->productImg);
        }

        return $rtn;
    }

    /**
     * product
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'productCode', 'productJancode')->where('delFlg', Product::STATUS_NOTDELETED);
    }
}
