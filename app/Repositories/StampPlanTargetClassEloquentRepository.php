<?php

namespace App\Repositories;

use App\Interfaces\StampPlanTargetClassRepositoryInterface;
use App\Models\StampPlanTargetClass;

class StampPlanTargetClassEloquentRepository extends MainEloquentRepository implements StampPlanTargetClassRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var StampPlan $Model
     */
    public $Model = StampPlanTargetClass::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * add a StampPlanTargetClass record
     *
     * @param Array $attributes
     * @return Bool/StampPlanTargetClass
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a StampPlanTargetClass record and if there's no record found  it will add the record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/StampPlanTargetClass
     */
    public function adjustAddTargetClass(int $id, array $attributes)
    {

        $rtn = false;
        try {
            $model = $this->Model::find($id);

            if (!empty($model) && !empty($attributes['departmentCode'])) {
                $rtn = $model->update($attributes);
            } elseif (empty($model) && !empty($attributes['departmentCode'])) {
                $rtn = $this->Model::create($attributes);
            } elseif (!empty($model) && empty($attributes['departmentCode'])) {
                $rtn = $model->update(['delFlg' => $this->Model::STATUS_DELETED]);
            } else {
                $rtn = true;
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
