<?php

namespace App\Models;

use App\Models\Content\DisplayTargetContent;
use App\Models\Coupon\DisplayTargetCoupon;
use App\Models\Flyer\DisplayTargetFlyer;
use App\Models\Flyer\FlyerStoreSelect;
use App\Models\Message\SendTargetMessage;

class UnionMember extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'M_UnionMemberId';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'unionMemberId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'unionMemberCode',
        'affiliationOffice',
        'joinDate',
        'withdrawalApplicationDate',
        'withdrawalDate',
        'pointBalance',
        'utilizationBusiness1',
        'utilizationBusiness2',
        'utilizationBusiness3',
        'utilizationBusiness4',
        'utilizationBusiness5',
        'utilizationBusiness6',
        'utilizationBusiness7',
        'utilizationBusiness8',
        'utilizationBusiness9',
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

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * unionLine()
     */
    public function unionLine()
    {
        return $this->hasOne(UnionLine::class, 'unionMemberCode', 'unionMemberCode')->where('delFlg', UnionLine::STATUS_NOTDELETED);
    }

    /**
     * flyerStoreSelect()
     */
    public function flyerStoreSelect()
    {
        return $this->hasOne(FlyerStoreSelect::class, 'unionMemberCode', 'unionMemberCode')
            ->whereRaw('T_FlyerStoreSelect.cardNumber = M_UnionMemberId.cardNumber');
    }

    /**
     * displayTargetCoupon()
     */
    public function displayTargetCoupon()
    {
        return $this->hasMany(displayTargetCoupon::class, 'unionMemberCode', 'kumicd')->where('delFlg', displayTargetCoupon::STATUS_NOTDELETED);
    }

    /**
     * displayTargetContent()
     */
    public function displayTargetContent()
    {
        return $this->hasMany(DisplayTargetContent::class, 'unionMemberCode', 'kumicd')->where('delFlg', DisplayTargetContent::STATUS_NOTDELETED);
    }

    /**
     * displayTargetFlyer()
     */
    public function displayTargetFlyer()
    {
        return $this->hasMany(DisplayTargetFlyer::class, 'unionMemberCode', 'kumicd')->where('delFlg', DisplayTargetFlyer::STATUS_NOTDELETED);
    }

    /**
     * sendTargetMessage()
     */
    public function sendTargetMessage()
    {
        return $this->hasMany(SendTargetMessage::class, 'unionMemberCode', 'kumicd')->where('delFlg', SendTargetMessage::STATUS_NOTDELETED);
    }
}
