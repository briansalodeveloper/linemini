<?php

namespace App\Models\Content;

use App\Models\MainModel;

class DisplayTargetContentUB extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetContentUB';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetContentUBId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'contentPlanId',
        'utilizationBusinessId',
        'updateDate',
        'updateUser',
        'delFlg',
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'isEmpty',
        'isNotEmpty',
    ];
}
