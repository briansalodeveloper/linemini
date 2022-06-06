<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\StoreRepositoryInterface;
use App\Models\Store;

class StoreEloquentRepository extends MainEloquentRepository implements StoreRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var Store $Model
     */
    public $Model = Store::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all Store records
     *
     * @return Builder
     */
    public function acquireAll()
    {
        try {
            $query = $this->Model::whereNotDeleted();
            $rtn = $query->get()->pluck('storeName', 'storeId');
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }

    /**
     * acquire a Store record
     *
     * @param Int $id
     * @return Store
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a Store record
     *
     * @param Array $attributes
     * @return Bool|Store
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a Store record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool|Store
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a Store record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }
}
