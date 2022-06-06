<?php

namespace App\Repositories\Coupon;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Coupon\DisplayTargetCouponRepositoryInterface;
use App\Models\Coupon\DisplayTargetCoupon;
use App\Repositories\MainEloquentRepository;

class DisplayTargetCouponEloquentRepository extends MainEloquentRepository implements DisplayTargetCouponRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetCoupon $Model
     */
    public $Model = DisplayTargetCoupon::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all DisplayTargetCoupon records
     *
     * @return Collection
     */
    public function acquireAll()
    {
        return parent::acquireAll();
    }

    /**
     * acquire a DisplayTargetCoupon record
     *
     * @param Int $id
     * @return DisplayTargetCoupon
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a DisplayTargetCoupon record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetCoupon
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetCoupon record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetCoupon
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetCoupon record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetCoupon
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
     * annul a list of DisplayTargetCoupon record base on attributes given
     * call NTC (No Try Catch) method
     *
     * @param Array $attributes
     * @return Bool
     */
    public function annulByAttributes(array $attributes)
    {
        return parent::annulByAttributes($attributes);
    }

    /**
    * acquire all kumicd from DisplayTargetCoupon records via DisplayTargetCuponId
    * call NTC (No Try Catch) method
    *
    * @param null|int $displayTargetCuponId
    * @return array
    */
    public function acquireAllKumicdByCoupon($couponPlanId, int $paginatePerPage = 10)
    {
        $rtn = [];

        try {
            if (!empty($couponPlanId)) {
                $rtn = DisplayTargetCoupon::where('cuponPlanId', $couponPlanId)
                    ->sortDesc()
                    ->whereNotDeleted()
                    ->pluck('kumicd')
                    ->toArray();
            }
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }
}
