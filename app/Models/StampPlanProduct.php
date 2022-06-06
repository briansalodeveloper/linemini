<?php

namespace App\Models;

use App\Helpers\Upload;

class StampPlanProduct extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_StampPlanProduct';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'stampPlanProductId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'stampPlanProductId',
        'productName',
        'productJancode',
        'productImg',
        'productText'
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'isThumbnailExist'
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
