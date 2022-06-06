<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\ParentModel;

class MainModel extends Model
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

    const STATUS_COMINGSOON = 0;
    const STATUS_ENDOFPUBLICATION = 1;
    const STATUS_OPENNOW = 2;

    const IS_COPY = 1;
}
