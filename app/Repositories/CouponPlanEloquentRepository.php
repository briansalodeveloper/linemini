<?php

namespace App\Repositories;

use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\CouponPlanRepositoryInterface;
use App\Models\CouponPlan;

class CouponPlanEloquentRepository extends MainEloquentRepository implements CouponPlanRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var CouponPlan $Model
     */
    public $Model = CouponPlan::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all CouponPlan records
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
     * acquire a CouponPlan record
     *
     * @param Int $id
     * @return CouponPlan
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a CouponPlan record
     *
     * @param Array $attributes
     * @return Bool|CouponPlan
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a CouponPlan record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool|CouponPlan
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a CouponPlan record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * acquire a CouponPlan record with relationship
     *
     * @param Int $id
     * @param string $relation
     *
     * @return CouponPlan
     */
    public function acquireWith($id, $relation)
    {
        return parent::acquireWith($id, $relation);
    }

}
