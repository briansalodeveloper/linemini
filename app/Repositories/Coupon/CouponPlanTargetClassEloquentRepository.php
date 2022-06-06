<?php

namespace App\Repositories\Coupon;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Coupon\CouponPlanTargetClassRepositoryInterface;
use App\Models\Coupon\CouponPlanTargetClass;
use App\Repositories\MainEloquentRepository;

class CouponPlanTargetClassEloquentRepository extends MainEloquentRepository implements CouponPlanTargetClassRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var CouponPlanTargetClass $Model
     */
    public $Model = CouponPlanTargetClass::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all CouponPlanTargetClass records
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
     * acquire a CouponPlanTargetClass record
     *
     * @param Int $id
     * @return CouponPlanTargetClass
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a CouponPlanTargetClass record
     *
     * @param Array $attributes
     * @return Bool|CouponPlanTargetClass
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a CouponPlanTargetClass record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool|CouponPlanTargetClass
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a CouponPlanTargetClass record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * acquire a CouponPlanTargetClass record with relationship
     *
     * @param Int $id
     * @param string $relation
     *
     * @return CouponPlanTargetClass
     */
    public function acquireWith($id, $relation)
    {
        return parent::acquireWith($id, $relation);
    }
}
