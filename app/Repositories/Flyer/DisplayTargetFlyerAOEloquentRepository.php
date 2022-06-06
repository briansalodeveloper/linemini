<?php

namespace App\Repositories\Flyer;

use App\Interfaces\Flyer\DisplayTargetFlyerAORepositoryInterface;
use App\Models\Flyer\DisplayTargetFlyerAO;
use App\Repositories\MainEloquentRepository;

class DisplayTargetFlyerAOEloquentRepository extends MainEloquentRepository implements DisplayTargetFlyerAORepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetFlyerAO $Model
     */
    public $Model = DisplayTargetFlyerAO::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * add a DisplayTargetFlyerAO record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetFlyerAO
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetFlyerAO record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetFlyerAO
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetFlyerAO record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetFlyerAO
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
     * annul a list of DisplayTargetFlyerAO record base on attributes given
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
