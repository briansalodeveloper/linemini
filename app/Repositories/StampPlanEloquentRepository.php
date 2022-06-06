<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\StampPlanRepositoryInterface;
use App\Models\StampPlan;

class StampPlanEloquentRepository extends MainEloquentRepository implements StampPlanRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var StampPlan $Model
     */
    public $Model = StampPlan::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all stampPlan records
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
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
        }
        return $rtn;
    }

    /**
     * acquire a stampPlan record
     *
     * @param Int $id
     * @return stampPlan
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a stampPlan record
     *
     * @param Array $attributes
     * @return Bool/stampPlan
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a stampPlan record
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
     * annul a stampPlan record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * duplicate a stampPlan record
     *
     * @param Int $id
     * @return Bool/StampPlan
     */
    public function addDuplicateProject(int $id)
    {

        $rtn = false;
        try {
            $stamp = $this->Model::find($id);
            $rtn = $stamp->replicateData();
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
