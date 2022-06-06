<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\Upload;
use App\Models\Message\SendTargetMessage;
use App\Models\Message\SendTargetMessageAO;
use App\Models\Message\SendTargetMessageStore;
use App\Models\Message\SendTargetMessageUB;

class Message extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'M_Message';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'messageId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'sendTargetFlg',
        'sendFlg',
        'sendDateTime',
        'messageName',
        'thumbnail',
        'thumbnailPreview',
        'contents',
        'storeId',
        'draftFlg',
        'updateDate',
        'updateUser',
        'delFlg',
    ];

    /**
     * @var $casts
     */
    protected $casts = [
        'startDateTime',
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'sendTargetFlgStr',
        'status',
        'statusStr',
        'isStatusSend',
        'isStatusNotSend',
        'isNotDraft',
        'isDraft',
        'isImageExist',
        'isEmpty',
        'isNotEmpty',
        'isTargetAll',
        'isTargetUm',
        'isTargetUb',
        'isTargetAo',
        'isTargetST',
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const STATUS_SCHEDULETOBESENT = 0;
    const STATUS_SENT = 1;

    const DRAFTFLG_NO = 0;
    const DRAFTFLG_YES = 1;

    const SENDSATTUS_NO = 0;
    const SENDSATTUS_YES = 1;

    const SENDTARGET_UNCONDITIONAL = 0;
    const SENDTARGET_UNIONMEMBER = 1;
    const SENDTARGET_UB = 2;
    const SENDTARGET_AO = 3;
    const SENDTARGET_STORE = 4;

    /*======================================================================
     * ACCESSORS
     *======================================================================*/

    /**
     * thumbnail
     *
     * @return String
     */
    public function getThumbnailAttribute($value)
    {
        return urldecode($value);
    }

    /**
     * thumbnailPreview
     *
     * @return String
     */
    public function getThumbnailPreviewAttribute($value)
    {
        return urldecode($value);
    }

    /**
     * sendTargetFlgStr
     *
     * @return String
     */
    public function getSendTargetFlgStrAttribute(): string
    {
        $rtn = '';

        if ($this->sendTargetFlg == self::SENDTARGET_UNCONDITIONAL) {
            $rtn = __('words.Everyone');
        } elseif ($this->sendTargetFlg == self::SENDTARGET_UNIONMEMBER) {
            $rtn = __('words.UnionMemberCode');
        } elseif ($this->sendTargetFlg == self::SENDTARGET_UB) {
            $rtn = __('words.UtilizationBusiness');
        } elseif ($this->sendTargetFlg == self::SENDTARGET_AO) {
            $rtn = __('words.AffiliateOffice');
        } elseif ($this->sendTargetFlg == self::SENDTARGET_STORE) {
            $rtn = __('words.SelectAtTheRegisteredStore');
        }

        return $rtn;
    }

    /**
     * sendDateTime
     *
     * @return String
     */
    public function getSendDateTimeAttribute(): string
    {
        $rtn = '';

        if (!empty($this->attributes['sendDateTime'])) {
            $rtn = new Carbon($this->attributes['sendDateTime']);
            $rtn = $rtn->format('m/d/Y g:i A');
        }

        return $rtn;
    }

    /**
     * status
     *
     * @return String $rtn
     */
    public function getStatusAttribute(): string
    {
        $rtn = '';

        if (!empty($this->sendDateTime)) {
            $currentDate = Carbon::now();
            $sendDateTime = Carbon::parse($this->sendDateTime);

            if ($currentDate->gt($sendDateTime) && $this->sendFlg == self::SENDSATTUS_YES) {
                $rtn = self::STATUS_SENT;
            } else {
                $rtn = self::STATUS_SCHEDULETOBESENT;
            }
        }

        return $rtn;
    }

    /**
     * statusStr
     *
     * @return String $rtn
     */
    public function getStatusStrAttribute(): string
    {
        $rtn = __('words.New');

        if ($this->isDraft) {
            $rtn = __('words.Hide');
        } elseif ($this->isStatusNotSend) {
            $rtn = __('words.ScheduleToBeSent');
        } elseif ($this->isStatusSend) {
            $rtn = __('words.Sent');
        }

        return $rtn;
    }

    /**
     * isStatusSend
     *
     * @return Bool $rtn
     */
    public function getIsStatusSendAttribute(): string
    {
        return $this->sendFlg == self::STATUS_SENT;
    }

    /**
     * isStatusNotSend
     *
     * @return Bool $rtn
     */
    public function getIsStatusNotSendAttribute(): string
    {
        return $this->sendFlg == self::STATUS_SCHEDULETOBESENT;
    }

    /**
     * isNotDraft
     *
     * @return Bool $rtn
     */
    public function getIsNotDraftAttribute(): string
    {
        return $this->draftFlg == self::DRAFTFLG_NO;
    }

    /**
     * isDraft
     *
     * @return Bool $rtn
     */
    public function getIsDraftAttribute(): string
    {
        return $this->draftFlg == self::DRAFTFLG_YES;
    }

    /**
     * isThumbnailExist
     *
     * @return Bool $rtn
     */
    public function getIsThumbnailExistAttribute(): string
    {
        $rtn = false;

        if (!empty($this->thumbnail)) {
            $rtn = Upload::exist($this->thumbnail);
        }

        return $rtn;
    }

    /**
     * isTargetAll
     *
     * @return Bool $rtn
     */
    public function getIsTargetAllAttribute(): string
    {
        return $this->sendTargetFlg == self::SENDTARGET_UNCONDITIONAL;
    }

    /**
     * isTargetUm
     *
     * @return Bool $rtn
     */
    public function getIsTargetUmAttribute(): string
    {
        return $this->sendTargetFlg == self::SENDTARGET_UNIONMEMBER;
    }

    /**
     * isTargetUb
     *
     * @return Bool $rtn
     */
    public function getIsTargetUbAttribute(): string
    {
        return $this->sendTargetFlg == self::SENDTARGET_UB;
    }

    /**
     * isTargetAo
     *
     * @return Bool $rtn
     */
    public function getIsTargetAoAttribute(): string
    {
        return $this->sendTargetFlg == self::SENDTARGET_AO;
    }

    /**
     * isTargetSt
     *
     * @return Bool $rtn
     */
    public function getIsTargetStAttribute(): string
    {
        return $this->sendTargetFlg == self::SENDTARGET_STORE;
    }

    /*======================================================================
     * ACCESSORS (ON CALL) (ON RUNTIME)
     *======================================================================*/

    /**
     * sendTargetMessageIdList
     *
     * @return Array $rtn
     */
    public function getSendTargetMessageIdListAttribute()
    {
        return $this->sendTargetMessage->pluck('kumicd')->toArray();
    }

    /**
     * sendTargetMessageAoAffiliationOfficeIdList
     *
     * @return Array $rtn
     */
    public function getSendTargetMessageAoAffiliationOfficeIdListAttribute()
    {
        return $this->sendTargetMessageAO->pluck('affiliationOfficeId')->toArray();
    }

    /**
     * sendTargetMessageUbUtilizationBusinessIdList
     *
     * @return Array $rtn
     */
    public function getSendTargetMessageUbUtilizationBusinessIdListAttribute()
    {
        return $this->sendTargetMessageUB->pluck('utilizationBusinessId')->toArray();
    }

    /**
     * sendTargetMessageStoreIdList
     *
     * @return Array $rtn
     */
    public function getSendTargetMessageStoreIdListAttribute()
    {
        return $this->sendTargetMessageStore->pluck('storeId')->toArray();
    }

    /*======================================================================
     * MUTATORS
     *======================================================================*/

    public function setSendDateTimeAttribute($value)
    {
        $rtn = '';

        if (!empty($value)) {
            $rtn = Carbon::parse($value)->format('Y-m-d H:i:s');
        }

        $this->attributes['sendDateTime'] = $rtn;
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * This method return mulitple message relation to sendTargetMessage.
     *
     * @return collection
     */
    public function sendTargetMessage()
    {
        return $this->hasMany(SendTargetMessage::class, 'messageId', 'messageId')->where('delFlg', SendTargetMessage::STATUS_NOTDELETED);
    }

    /**
     * This method return mulitple message relation to sendTargetMessageAO.
     *
     * @return collection
     */
    public function sendTargetMessageAO()
    {
        return $this->hasMany(SendTargetMessageAO::class, 'messageId', 'messageId')->where('delFlg', SendTargetMessageAO::STATUS_NOTDELETED);
    }

    /**
     * This method return mulitple message relation to sendTargetMessageUB.
     *
     * @return collection
     */
    public function sendTargetMessageUB()
    {
        return $this->hasMany(SendTargetMessageUB::class, 'messageId', 'messageId')->where('delFlg', SendTargetMessageUB::STATUS_NOTDELETED);
    }

    /**
     * This method return mulitple message relation to sendTargetMessageStore.
     *
     * @return collection
     */
    public function sendTargetMessageStore()
    {
        return $this->hasMany(SendTargetMessageStore::class, 'messageId', 'messageId')->where('delFlg', SendTargetMessageStore::STATUS_NOTDELETED);
    }

    /*======================================================================
     * SCOPES
     *======================================================================*/

    /**
     * whereNotSend()
     */
    public function scopeWhereNotSend($query)
    {
        $query->where('sendFlg', self::SENDSATTUS_NO);
        return $query;
    }

    /**
     * whereNotDraft()
     */
    public function scopeWhereNotDraft($query)
    {
        $query->where('draftFlg', self::DRAFTFLG_NO);
        return $query;
    }
}
