<?php

namespace App\Models;

use App\Models\Content\DisplayTargetContent;
use App\Models\Coupon\DisplayTargetCoupon;
use App\Models\Flyer\DisplayTargetFlyer;
use App\Models\Flyer\FlyerStoreSelect;
use App\Models\Message\SendTargetMessage;

class UnionLine extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'M_UnionLineId';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'unionLineId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'LineTokenId',
        'cardNumber',
        'pinCode',
        'unionMemberCode',
        'cardAlignment',
        'stopFlg',
        'firstFlg',
        'dailyCheck',
        'incidental',
        'bikou1',
        'bikou2',
        'bikou3',
        'bikou4',
        'bikou5',
        'bikou6',
        'bikou7',
        'bikou8',
        'bikou9',
        'bikou10',
        'updateDate',
        'updateUser',
        'delFlg',
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'delFlgStr',
        'stopFlgStr',
        'isEmpty',
        'isNotEmpty',
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const STOPFLG_NO = 0;
    const STOPFLG_YES = 1;

    const INCIDENTAL_PHONELOST = 1;
    const INCIDENTAL_PHONERECOVERED = 2;
    const INCIDENTAL_PHONEREPLACED = 3;
    const INCIDENTAL_PHONEREPLACE = 4;
    const INCIDENTAL_PHONEREPLACEDBUTHASISSUE = 4;
    const INCIDENTAL_CARDREISSUE = 7;

    const INCIDENTALS = [
        '1' => 'words.LostSmartphone',
        '2' => 'words.AfterRegisteringLostSmartphoneSmartphoneDiscovery',
        '3' => 'words.AfterRegisteringLostSmartphoneReplacementOfSmartphoneIwasAbleToTakeOverLine',
        '4' => [
            'words.AfterRegisteringLostSmartphoneReplacementOfSmartphoneLineCouldNotBeTakenOver',
            'words.SmartphoneReplacement'
        ],
        '7' => 'words.HereCardReissue',
    ];

    /*======================================================================
     * ACCESSORS
     *======================================================================*/

    /**
     * delFlgStr
     *
     * @return String $rtn
     */
    public function getDelFlgStrAttribute(): string
    {
        $rtn = '';

        if ($this->delFlg == self::STATUS_NOTDELETED) {
            $rtn = __('words.InUse');
        } else {
            $rtn = __('words.CancellationOfCooperation');
        }

        return $rtn;
    }

    /**
     * stopFlgStr
     *
     * @return String $rtn
     */
    public function getStopFlgStrAttribute(): string
    {
        $rtn = '';

        if ($this->stopFlg == self::STOPFLG_NO) {
            $rtn = __('words.InUse');
        } elseif ($this->stopFlg == self::STOPFLG_YES) {
            $rtn = __('words.Pause');
        }

        return $rtn;
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * unionMember()
     */
    public function unionMember()
    {
        return $this->hasOne(UnionMember::class, 'unionMemberCode', 'unionMemberCode')->where('delFlg', UnionMember::STATUS_NOTDELETED);
    }

    /**
     * flyerStoreSelect()
     */
    public function flyerStoreSelect()
    {
        return $this->hasOne(FlyerStoreSelect::class, 'unionMemberCode', 'unionMemberCode')
            ->whereRaw('T_FlyerStoreSelect.cardNumber = M_UnionLineId.cardNumber');
    }

    /**
     * displayTargetCoupon()
     */
    public function displayTargetCoupon()
    {
        return $this->hasMany(DisplayTargetCoupon::class, 'unionMemberCode', 'kumicd')->where('delFlg', DisplayTargetCoupon::STATUS_NOTDELETED);
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

    /*======================================================================
     * SCOPES
     *======================================================================*/

    /**
     * whereStop
     */
    public function scopeWhereStop($query)
    {
        $query->where('stopFlg', self::STOPFLG_YES);
        return $query;
    }

    /**
     * whereNotStop
     */
    public function scopeWhereNotStop($query)
    {
        $query->where('stopFlg', self::STOPFLG_NO);
        return $query;
    }
}
