<?php

namespace App\Models\Flyer;

use App\Models\MainModel;

class FlyerDisplayStore extends MainModel
{
    /**
    * @var $table
    */
    protected $table = 'T_FlyerDisplayStore';

    /**
    * @var $primaryKey
    */
    protected $primaryKey = 'flyerDisplayStoreId';

    /**
    * @var $fillable
    */
    protected $fillable = [
        'flyerPlanId',
        'storeId',
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
