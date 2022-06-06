<?php

namespace App\Repositories\Coupon;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Coupon\CouponPlanProductRepositoryInterface;
use App\Models\Coupon\CouponPlanProduct;
use App\Repositories\MainEloquentRepository;

class CouponPlanProductEloquentRepository extends MainEloquentRepository implements CouponPlanProductRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var CouponPlanProduct $Model
     */
    public $Model = CouponPlanProduct::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all CouponPlanProduct records
     *
     * @param Null|Int $contentType
     * @param Int $paginatePerPage
     * @return LengthAwarePaginator
     */
    public function acquireAll($paginatePerPage = 10): LengthAwarePaginator
    {
        $rtn = $this->arrayToPagination([]);

        try {
            $query = $this->Model::whereNotDeleted();
            $rtn = $query->sortDesc()->paginate($paginatePerPage);
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }

    /**
     * acquire a CouponPlanProduct record
     *
     * @param Int $id
     * @return CouponPlanProduct
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a CouponPlanProduct record
     *
     * @param Array $attributes
     * @return Bool|CouponPlanProduct
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a CouponPlanProduct record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool|CouponPlanProduct
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a CouponPlanProduct record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * acquire a CouponPlanProduct record with relationship
     *
     * @param Int $id
     * @param string $relation
     *
     * @return CouponPlanProduct
     */
    public function acquireWith($id, $relation)
    {
        return parent::acquireWith($id, $relation);
    }
}
