<?php

namespace App\Interfaces;

interface UnionMemberRepositoryInterface
{
    /**
     * acquire total number of union member base on Ao ID
     *
     * @param Array $ids - Affiliationoffice ID's
     * @return Array $rtn
     */
    public function acquireCountByAoId(array $ids);
}
