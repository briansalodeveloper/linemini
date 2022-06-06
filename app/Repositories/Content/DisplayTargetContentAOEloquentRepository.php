<?php

namespace App\Repositories\Content;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Content\DisplayTargetContentAO;
use App\Interfaces\Content\DisplayTargetContentAORepositoryInterface;
use App\Repositories\MainEloquentRepository;

class DisplayTargetContentAOEloquentRepository extends MainEloquentRepository implements DisplayTargetContentAORepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetContentAO $Model
     */
    public $Model = DisplayTargetContentAO::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all ContentPlan records
     *
     * @return Collection
     */
    public function acquireAll(): Collection
    {
        return parent::acquireAll($id);
    }

    /**
     * acquire a ContentPlan record
     *
     * @param Int $id
     * @return DisplayTargetContentAO
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a DisplayTargetContentAO record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetContentAO
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetContentAO record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetContentAO
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetContentAO record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetContentAO
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
     * annul a list of DisplayTargetContentAO record base on attributes given
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
