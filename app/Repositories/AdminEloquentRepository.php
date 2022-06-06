<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\AdminRepositoryInterface;
use App\Models\Admin;

class AdminEloquentRepository extends MainEloquentRepository implements AdminRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var Admin $Model
     */
    public $Model = Admin::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all Admin records
     *
     * @param Int $paginatePerPage
     * @return LengthAwarePaginator
     */
    public function acquireAll($paginatePerPage = 10): LengthAwarePaginator
    {
        $rtn = $this->arrayToPagination([]);

        try {
            $rtn = $this->Model::whereNotDeleted()
                ->sortDesc()
                ->paginate($paginatePerPage);
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
     * acquire a Admin record
     *
     * @param Int $id
     * @return Admin
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a Admin record
     *
     * @param Array $attributes
     * @return Bool/Admin
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a Admin record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/Admin
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a Admin record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }
}
