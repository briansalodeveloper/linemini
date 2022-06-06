<?php

namespace App\Models\Flyer;

use App\Models\UnionLine;
use App\Models\UnionMember;
use App\Models\MainModel;

class DisplayTargetFlyer extends MainModel
{
    /**
    * @var $table
    */
    protected $table = 'T_DisplayTargetFlyer';

    /**
    * @var $primaryKey
    */
    protected $primaryKey = 'displayTargetFlyerId';

    /**
    * @var $fillable
    */
    protected $fillable = [
        'flyerPlanId',
        'kumicd',
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

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * unionMember()
     */
    public function unionMember()
    {
        return $this->belongsTo(UnionMember::class, 'kumicd', 'unionMemberCode')->where('delFlg', UnionMember::STATUS_NOTDELETED);
    }

    /**
     * UnionLine()
     */
    public function UnionLine()
    {
        return $this->belongsTo(UnionLine::class, 'kumicd', 'unionMemberCode')->where('delFlg', UnionLine::STATUS_NOTDELETED);
    }
}
