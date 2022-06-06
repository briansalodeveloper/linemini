<?php

namespace App\Repositories\Flyer;

use App\Interfaces\Flyer\FlyerStoreSelectRepositoryInterface;
use App\Models\Flyer\FlyerStoreSelect;
use App\Repositories\MainEloquentRepository;

class FlyerStoreSelectEloquentRepository extends MainEloquentRepository implements FlyerStoreSelectRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var FlyerStoreSelect $Model
     */
    public $Model = FlyerStoreSelect::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all FlyerStoreSelect storeId only
     *
     * @return Array $rtn
     */
    public function acquireAllStoreDistinctWithUnionLineUser(): array
    {
        $rtn = [];

        try {
            $rtn = $this->Model::select('storeId')
                ->whereHas('unionLine')
                ->whereHas('store')
                ->distinct()->get();
            $rtn = $rtn->pluck('label', 'storeId')->toArray();
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
