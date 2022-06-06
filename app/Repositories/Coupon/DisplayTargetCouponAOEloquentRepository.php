<?php

namespace App\Repositories\Coupon;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Coupon\DisplayTargetCouponAORepositoryInterface;
use App\Models\Coupon\DisplayTargetCouponAO;
use App\Repositories\MainEloquentRepository;

class DisplayTargetCouponAOEloquentRepository extends MainEloquentRepository implements DisplayTargetCouponAORepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetCouponAO $Model
     */
    public $Model = DisplayTargetCouponAO::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all DisplayTargetCouponAO records
     *
     * @return Collection
     */
    public function acquireAll()
    {
        return parent::acquireAll();
    }

    /**
     * acquire a DisplayTargetCouponAO record
     *
     * @param Int $id
     * @return DisplayTargetCouponAO
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a DisplayTargetCouponAO record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetCouponAO
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetCouponAO record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetCouponAO
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetCouponAO record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetCouponAO
     * call NTC (No Try Catch) method
     *
     * @param Array $attributesArray
     * @return Bool/Model
     */
    public function addBulk(array $attributesArray)
    {
        return parent::addBulk($attributesArray);
    }

    /**
     * annul a list of DisplayTargetCouponAO record base on attributes given
     * call NTC (No Try Catch) method
     *
     * @param Array $attributes
     * @return Bool
     */
    public function annulByAttributes(array $attributes)
    {
        return parent::annulByAttributes($attributes);
    }
}
