<?php

namespace App\Interfaces;

use App\Repositories\UnionLineEloquentRepository;

interface UnionLineRepositoryInterface
{
    /**
     * @return Model
     */
    public function acquireAll();

    /**
     * @param Null/Int $id
     * @return Model
     */
    public function acquire($id);

    /**
     * @param Array $attributes
     * @return Bool/Model
     */
    public function add(array $attributes);

    /**
     * @param Int $id
     * @param Array $attributes
     * @return Bool/Model
     */
    public function adjust(int $id, array $attributes);

    /**
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id);
    
    /**
     * @param Array $filters
     * @return Bool/UnionLine
     */
    public function acquireAllByFilter(array $filters);

    /**
     * @param Array $whereAttributes
     * @param Array $adjustAttributes
     * @return Bool
     */
    public function adjustByAttributes(array $whereAttributes, array $adjustAttributes);
}
