<?php

namespace App\Repositories\Flyer;

use App\Interfaces\Flyer\DisplayTargetFlyerUBRepositoryInterface;
use App\Models\Flyer\DisplayTargetFlyerUB;
use App\Repositories\MainEloquentRepository;

class DisplayTargetFlyerUBEloquentRepository extends MainEloquentRepository implements DisplayTargetFlyerUBRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetFlyerUB $Model
     */
    public $Model = DisplayTargetFlyerUB::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * add a DisplayTargetFlyerUB record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetFlyerUB
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetFlyerUB  record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetFlyerUB
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetFlyerUB record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetFlyerUB
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
     * annul a list of DisplayTargetFlyerUB record base on attributes given
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
