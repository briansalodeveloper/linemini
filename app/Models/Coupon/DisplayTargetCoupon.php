<?php

namespace App\Models\Coupon;

use App\Models\MainModel;
use App\Models\UnionLine;
use App\Models\UnionMember;

class DisplayTargetCoupon extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetCupon';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetCuponId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'cuponPlanId',
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
        'isNotEmpty'
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
