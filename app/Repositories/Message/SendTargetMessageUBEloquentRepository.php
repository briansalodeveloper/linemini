<?php

namespace App\Repositories\Message;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Message\SendTargetMessageUBRepositoryInterface;
use App\Models\Message\SendTargetMessageUB;
use App\Repositories\MainEloquentRepository;

class SendTargetMessageUBEloquentRepository extends MainEloquentRepository implements SendTargetMessageUBRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var SendTargetMessageUB $Model
     */
    public $Model = SendTargetMessageUB::class;

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
     * @return SendTargetMessageUB
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a SendTargetMessageUB record
     *
     * @param Array $attributes
     * @return Bool/SendTargetMessageUB
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a SendTargetMessageUB record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/SendTargetMessageUB
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a SendTargetMessageUB record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to SendTargetMessageUB
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
     * annul a list of SendTargetMessageUB record base on attributes given
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
