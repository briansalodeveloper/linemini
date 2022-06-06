<?php

namespace App\Interfaces;

interface StampPlanTargetClassRepositoryInterface
{
    /**
     * @param Array $attributes
     * @return Bool/Model
     */
    public function add(array $attributes);

    /**
     * @param Id $id
     * @param Array $attributes
     * @return Bool/Model
     */
    public function adjustAddTargetClass(int $id, array $attributes);
}
