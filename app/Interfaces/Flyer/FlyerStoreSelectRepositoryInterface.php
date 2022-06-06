<?php

namespace App\Interfaces\Flyer;

interface FlyerStoreSelectRepositoryInterface
{
    /**
     * acquire all FlyerStoreSelect records
     *
     * @return Array $rtn
     */
    public function acquireAllStoreDistinctWithUnionLineUser(): array;
}
