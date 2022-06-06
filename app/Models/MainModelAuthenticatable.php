<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\Models\ParentModel;

class MainModelAuthenticatable extends Authenticatable
{
    use ParentModel;

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'isEmpty',
        'isNotEmpty',
    ];

    /**
     * @var $timestamps
     */
    public $timestamps = false;

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const CREATED_AT = null;
    const UPDATED_AT = 'updateDate';

    const STATUS_NOTDELETED = 0;
    const STATUS_DELETED = 1;
}
