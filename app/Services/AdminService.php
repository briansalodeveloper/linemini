<?php

namespace App\Services;

use App\Models\Admin;
use App\Interfaces\AdminRepositoryInterface;

class AdminService extends MainService
{
    /*======================================================================
     * CONSTRUCTOR
     *======================================================================*/

    /**
     * @param AdminRepositoryInterface $repository
     */
    public function __construct(AdminRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /*======================================================================
     * PUBLIC METHODS
     *======================================================================*/

    /**
     * fetch all records
     *
     * @param Int $type
     * @return Array $rtn
     */
    public function all()
    {
        $rtn = [
            'data' => $this->repository->acquireAll()
        ];

        return $rtn;
    }

    /**
     * fetch a record
     *
     * @param Int|Null $id
     * @return Array $rtn
     */
    public function get(int $id = null): array
    {
        $rtn = [
            'data' => $this->repository->acquire($id),
            'listRole' => config('const.listRole'),
        ];

        return $rtn;
    }

    /**
     * store a record
     *
     * @return Bool|Admin $rtn
     */
    public function store()
    {
        $data = [
            'name' => request()->get('name'),
            'username' => request()->get('username'),
            'email' => request()->get('email'),
            'password' => bcrypt(request()->get('password')),
            'role' => request()->get('role'),
        ];


        return $this->repository->add($data);
    }

    /**
     * update a record
     *
     * @param Int $id
     * @return Bool|Admin $rtn
     */
    public function update(int $id)
    {
        $data = [
            'name' => request()->get('name'),
            'username' => request()->get('username'),
            'email' => request()->get('email'),
            'role' => request()->get('role'),
        ];

        if (request()->get('updatePassword')) {
            $data['password'] = bcrypt(request()->get('password'));
        }

        return $this->repository->adjust($id, $data);
    }

    /**
     * delete a record
     *
     * @param Int $id
     * @return Bool
     */
    public function destroy(int $id)
    {
        return $this->repository->annul($id);
    }
}
