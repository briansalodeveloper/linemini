<?php

namespace App\Repositories\Message;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Message\SendTargetMessageStoreRepositoryInterface;
use App\Models\Message\SendTargetMessageStore;
use App\Repositories\MainEloquentRepository;

class SendTargetMessageStoreEloquentRepository extends MainEloquentRepository implements SendTargetMessageStoreRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var SendTargetMessageStore $Model
     */
    public $Model = SendTargetMessageStore::class;

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
     * @return SendTargetMessageStore
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a SendTargetMessageStore record
     *
     * @param Array $attributes
     * @return Bool/SendTargetMessageStore
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a SendTargetMessageStore record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/SendTargetMessageStore
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a SendTargetMessageStore record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to SendTargetMessageStore
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
     * annul a list of SendTargetMessageStore record base on attributes given
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
