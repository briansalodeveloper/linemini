<?php

namespace App\Repositories;

use App\Interfaces\StampPlanStoreRepositoryInterface;
use App\Models\StampPlanStore;

class StampPlanStoreEloquentRepository extends MainEloquentRepository implements StampPlanStoreRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var FlyerPlan $Model
     */
    public $Model = StampPlanStore::class;
}
