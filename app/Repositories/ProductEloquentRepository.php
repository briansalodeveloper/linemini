<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductEloquentRepository extends MainEloquentRepository implements ProductRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var Product $Model
     */
    public $Model = Product::class;

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all Product records
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
     * acquire a Product record
     *
     * @param Int $id
     * @return Product
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a Product record
     *
     * @param Array $attributes
     * @return Bool|Product
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a Product record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool|Product
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a Product record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }
}
