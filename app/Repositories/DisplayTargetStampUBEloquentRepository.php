<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\DisplayTargetStampUBRepositoryInterface;
use App\Models\DisplayTargetStampUB;

class DisplayTargetStampUBEloquentRepository extends MainEloquentRepository implements DisplayTargetStampUBRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetStampUB $Model
     */
    public $Model = DisplayTargetStampUB::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * add a DisplayTargetStampUB record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetStampUB
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetStampUB record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetStampUB
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetStampUB record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetStampUB
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
     * annul a list of DisplayTargetStampUB record base on attributes given
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
