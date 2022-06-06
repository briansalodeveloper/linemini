<?php

namespace App\Repositories\Flyer;

use App\Interfaces\Flyer\FlyerDisplayStoreRepositoryInterface;
use App\Models\Flyer\FlyerDisplayStore;
use App\Repositories\MainEloquentRepository;

class FlyerDisplayStoreEloquentRepository extends MainEloquentRepository implements FlyerDisplayStoreRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var FlyerDisplayStore $Model
     */
    public $Model = FlyerDisplayStore::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * add a FlyerDisplayStore record
     *
     * @param Array $attributes
     * @return Bool/FlyerDisplayStore
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a FlyerDisplayStore  record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/FlyerDisplayStore
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a FlyerDisplayStore record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to FlyerDisplayStore
     * call NTC (No Try Catch) method
     *
     * @param Array $attributesArray
     * @return Bool/Model
     */
    public function addBulk(array $attributesArray)
    {
        return parent::addBulk($attributesArray);
    }

    /**
     * annul a list of FlyerDisplayStore record base on attributes given
     * call NTC (No Try Catch) method
     *
     * @param Array $attributes
     * @return Bool
     */
    public function annulByAttributes(array $attributes)
    {
        return parent::annulByAttributes($attributes);
    }
}
