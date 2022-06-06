<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\StampPlanProductRepositoryInterface;
use App\Models\StampPlanProduct;

class StampPlanProductEloquentRepository extends MainEloquentRepository implements StampPlanProductRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var StampPlanProduct $Model
     */
    public $Model = StampPlanProduct::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all StampPlanProduct records
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
     * acquire a StampPlanProduct record
     *
     * @param Int $id
     * @return StampPlanProduct
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a StampPlanProduct record
     *
     * @param Array $attributes
     * @return Bool|StampPlanProduct
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a StampPlanProduct record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool|StampPlanProduct
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a StampPlanProduct record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * acquire a StampPlanProduct record with relationship
     *
     * @param Int $id
     * @param string $relation
     *
     * @return StampPlanProduct
     */
    public function acquireWith($id, $relation)
    {
        return parent::acquireWith($id, $relation);
    }
}
