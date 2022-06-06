<?php

namespace App\Repositories\Coupon;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Coupon\DisplayTargetCouponUBRepositoryInterface;
use App\Models\Coupon\DisplayTargetCouponUB;
use App\Repositories\MainEloquentRepository;

class DisplayTargetCouponUBEloquentRepository extends MainEloquentRepository implements DisplayTargetCouponUBRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetCouponUB $Model
     */
    public $Model = DisplayTargetCouponUB::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all DisplayTargetCouponUB records
     *
     * @return Collection
     */
    public function acquireAll()
    {
        return parent::acquireAll();
    }

    /**
     * acquire a DisplayTargetCouponUB record
     *
     * @param Int $id
     * @return DisplayTargetCouponUB
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a DisplayTargetCouponUB record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetCouponUB
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetCouponUB record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetCouponUB
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetCouponUB record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetCouponUB
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
     * annul a list of DisplayTargetCouponUB record base on attributes given
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
