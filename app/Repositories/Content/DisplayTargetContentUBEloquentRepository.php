<?php

namespace App\Repositories\Content;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Content\DisplayTargetContentUBRepositoryInterface;
use App\Models\Content\DisplayTargetContentUB;
use App\Repositories\MainEloquentRepository;

class DisplayTargetContentUBEloquentRepository extends MainEloquentRepository implements DisplayTargetContentUBRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetContentUB $Model
     */
    public $Model = DisplayTargetContentUB::class;

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
     * @return DisplayTargetContentUB
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a DisplayTargetContentUB record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetContentUB
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetContentUB record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetContentUB
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetContentUB record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetContentUB
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
     * annul a list of DisplayTargetContentUB record base on attributes given
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
