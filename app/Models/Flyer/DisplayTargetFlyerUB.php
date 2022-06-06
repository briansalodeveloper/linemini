<?php

namespace App\Models\Flyer;

use App\Models\MainModel;

class DisplayTargetFlyerUB extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetFlyerUB';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetFlyerUBId';

    /**
     * @var $fillable
     */
    protected $fillable = [
            'flyerPlanId',
            'utilizationBusinessId',
            'updateDate',
            'updateUser',
            'delFlg'
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
