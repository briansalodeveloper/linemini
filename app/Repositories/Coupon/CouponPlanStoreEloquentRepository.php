<?php

namespace App\Repositories\Coupon;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Coupon\CouponPlanStoreRepositoryInterface;
use App\Models\Coupon\CouponPlanStore;
use App\Repositories\MainEloquentRepository;

class CouponPlanStoreEloquentRepository extends MainEloquentRepository implements CouponPlanStoreRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var CouponPlanStore $Model
     */
    public $Model = CouponPlanStore::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all CouponPlanStore records
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
     * acquire a CouponPlanStore record
     *
     * @param Int $id
     * @return CouponPlanStore
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a CouponPlanStore record
     *
     * @param Array $attributes
     * @return Bool|CouponPlanStore
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a CouponPlanStore record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool|CouponPlanStore
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a CouponPlanStore record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * acquire a CouponPlanStore record with relationship
     *
     * @param Int $id
     * @param string $relation
     *
     * @return CouponPlanStore
     */
    public function acquireWith($id, $relation)
    {
        return parent::acquireWith($id, $relation);
    }

}
