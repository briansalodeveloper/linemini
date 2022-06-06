<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\DisplayTargetStampAORepositoryInterface;
use App\Models\DisplayTargetStampAO;

class DisplayTargetStampAOEloquentRepository extends MainEloquentRepository implements DisplayTargetStampAORepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetStampAO $Model
     */
    public $Model = DisplayTargetStampAO::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * add a DisplayTargetStampAO record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetStampAO
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetStampAO record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetStampAO
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetStampAO record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetStampAO
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
     * annul a list of DisplayTargetStampAO record base on attributes given
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
