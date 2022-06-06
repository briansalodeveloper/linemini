<?php

namespace App\Interfaces;

interface DisplayTargetStampUBRepositoryInterface
{
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
}
