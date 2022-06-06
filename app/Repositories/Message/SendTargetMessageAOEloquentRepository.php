<?php

namespace App\Repositories\Message;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Message\SendTargetMessageAORepositoryInterface;
use App\Models\Message\SendTargetMessageAO;
use App\Repositories\MainEloquentRepository;

class SendTargetMessageAOEloquentRepository extends MainEloquentRepository implements SendTargetMessageAORepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var SendTargetMessageAO $Model
     */
    public $Model = SendTargetMessageAO::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all Message records
     *
     * @return Collection
     */
    public function acquireAll(): Collection
    {
        return parent::acquireAll($id);
    }

    /**
     * acquire a Message record
     *
     * @param Int $id
     * @return SendTargetMessageAO
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a SendTargetMessageAO record
     *
     * @param Array $attributes
     * @return Bool/SendTargetMessageAO
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a SendTargetMessageAO record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/SendTargetMessageAO
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a SendTargetMessageAO record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to SendTargetMessageAO
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
     * annul a list of SendTargetMessageAO record base on attributes given
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
