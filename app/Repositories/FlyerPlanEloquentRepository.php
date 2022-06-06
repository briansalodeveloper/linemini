<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\FlyerPlanRepositoryInterface;
use App\Models\FlyerPlan;

class FlyerPlanEloquentRepository extends MainEloquentRepository implements FlyerPlanRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var FlyerPlan $Model
     */
    public $Model = FlyerPlan::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all FlyerPlan records
     *
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
     * acquire a FlyerPlan record
     *
     * @param Int $id
     * @return FlyerPlan
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a FlyerPlan record
     *
     * @param Array $attributes
     * @return Bool/FlyerPlan
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a FlyerPlan record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/FlyerPlan
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a FlyerPlan record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }
}
