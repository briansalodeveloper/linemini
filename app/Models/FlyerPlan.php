<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\Upload;
use App\Models\Flyer\DisplayTargetFlyer;
use App\Models\Flyer\DisplayTargetFlyerAO;
use App\Models\Flyer\DisplayTargetFlyerUB;
use App\Models\Flyer\FlyerDisplayStore;

class FlyerPlan extends MainModel
{
    /**
    * @var $table
    */
    protected $table = 'M_FlyerPlan';

    /**
    * @var $primaryKey
    */
    protected $primaryKey = 'flyerPlanId';

    /**
    * @var $fillable
    */
    protected $fillable = [
        'flyerPlanId',
        'displayTargetFlg',
        'startDateTime',
        'endDateTime',
        'flyerName',
        'flyerImg',
        'flyerUraImg',
        'updateDate',
        'updateUser',
        'delFlg'
    ];

    /**
    * @var $cast
    */
    protected $casts = [
        'startDateTime',
        'endDateTime',
    ];

    /**
    * @var $appends
    */
    protected $appends = [
        'status',
        'statusStr',
        'isFlyerImgExist',
        'isFlyerUraImgExist'
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const STATUS_COMINGSOON = 0;
    const STATUS_ENDOFPUBLICATION = 1;
    const STATUS_OPENNOW = 2;

    const DSPTARGET_UNCONDITIONAL = 0;
    const DSPTARGET_UNIONMEMBER = 1;
    const DSPTARGET_UB = 2;
    const DSPTARGET_AO = 3;

    const STORE_SHIN_SHIMOZEKI = 0;
    const STORE_UBE = 1;
    const STORE_KUCHIOGORI = 2;
    const STORE_IZUMI = 3;
    const STORE_THANKYOU = 4;
    const STORE_TOKUYAMA = 5;
    const STORE_SHIMADA = 6;

    const CSV_ACCEPTEDEXTENSION = ['csv'];

    /*======================================================================
     * ACCESSORS
     *======================================================================*/

    /**
     * flyerImg
     *
     * @return String
     */
    public function getFlyerImgAttribute($value)
    {
        return urldecode($value);
    }

    /**
     * flyerUraImg
     *
     * @return String
     */
    public function getFlyerUraImgAttribute($value)
    {
        return urldecode($value);
    }

    /**
     * This method returns a new startDateTime format.
     *
     * @return String|DateTime $rtn
     */
    public function getStartDateTimeAttribute()
    {
        $rtn = '';

        if (!empty($this->attributes['startDateTime'])) {
            $rtn = new Carbon($this->attributes['startDateTime']);
            $rtn = $rtn->format('m/d/Y h:i A');
        }

        return $rtn;
    }

    /**
     * This method returns a new endDateTime format.
     *
     * @return String|Boolean $rtn
     */
    public function getEndDateTimeAttribute(): string
    {
        $rtn = '';

        if (!empty($this->attributes['endDateTime'])) {
            $rtn = new Carbon($this->attributes['endDateTime']);
            $rtn = $rtn->format('m/d/Y h:i A');
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
        $currentDate = Carbon::now();
        $startDateTime = new Carbon($this->startDateTime);
        $endDateTime = new Carbon($this->endDateTime);

        if ($currentDate->gt($endDateTime)) {
            $rtn = self::STATUS_ENDOFPUBLICATION;
        } elseif ($currentDate->lt($startDateTime)) {
            $rtn = self::STATUS_COMINGSOON;
        } elseif (!empty($startDateTime) && !empty($endDateTime)) {
            $rtn = self::STATUS_OPENNOW;
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

        if ($this->status == self::STATUS_COMINGSOON) {
            $rtn = __('words.ComingSoon');
        } elseif ($this->status == self::STATUS_ENDOFPUBLICATION) {
            $rtn = __('words.EndOfPublication');
        } elseif ($this->status == self::STATUS_OPENNOW) {
            $rtn = __('words.OpenNow');
        }

        return $rtn;
    }

    /**
     * This method check if file of attribute flyerImg exist.
     *
     * @return Bool $rtn
     */
    public function getIsFlyerImgExistAttribute()
    {
        $rtn = false;

        if (!empty($this->flyerImg)) {
            $rtn = Upload::exist($this->flyerImg);
        }

        return $rtn;
    }

    /**
     * This method check if file of atrribute flyerUraImg exist.
     *
     * @return Bool $rtn
     */
    public function getIsFlyerUraImgExistAttribute()
    {
        $rtn = false;

        if (!empty($this->flyerUraImg)) {
            $rtn = Upload::exist($this->flyerUraImg);
        }

        return $rtn;
    }

    /*======================================================================
     * ACCESSORS (ON CALL) (ON RUNTIME)
     *======================================================================*/

    /**
     * displayStoresStr
     *
     * @return String $rtn
     */
    public function getDisplayStoresStrAttribute(): string
    {
        $rtn = '';

        $ids = $this->flyerDisplayStoreIdList;

        foreach ($ids as $ind => $id) {
            if (!empty(config('const.listStore')[$id])) {
                if ($ind != 0) {
                    $rtn .= ',';
                }

                $rtn .= __(config('const.listStore')[$id]);
            } else {
                \L0g::error('Invalid store id #' . $id . ' in T_FlyerDisplayStore');
                \SlackLog::error('Invalid store id #' . $id . ' in T_FlyerDisplayStore');
            }
        }

        return $rtn;
    }

    /**
     * flyerDisplayStoreIdList
     *
     * @return Array $rtn
     */
    public function getFlyerDisplayStoreIdListAttribute()
    {
        return $this->flyerDisplayStore->pluck('storeId')->toArray();
    }

    /**
     * displayTargetFlyerIdList
     *
     * @return Array $rtn
     */
    public function getDisplayTargetFlyerIdListAttribute()
    {
        return $this->displayTargetFlyer->pluck('kumicd')->toArray();
    }

    /**
     * displayTargetFlyerAoAffiliationOfficeIdList
     *
     * @return Array $rtn
     */
    public function getDisplayTargetFlyerAoAffiliationOfficeIdListAttribute()
    {
        return $this->displayTargetFlyerAO->pluck('affiliationOfficeId')->toArray();
    }

    /**
     * displayTargetFlyerUbUtilizationBusinessIdList
     *
     * @return Array $rtn
     */
    public function getDisplayTargetFlyerUbUtilizationBusinessIdListAttribute()
    {
        return $this->displayTargetFlyerUB->pluck('utilizationBusinessId')->toArray();
    }

    /*======================================================================
     * MUTATORS
     *======================================================================*/

    /**
     * This method mutate startDateTime to new date format.
     *
     * @return void
     */
    public function setStartDateTimeAttribute($value)
    {
        $this->attributes['startDateTime'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    /**
     * This method mutate endDateTime to new date format.
     *
     * @return void
     */
    public function setEndDateTimeAttribute($value)
    {
        $this->attributes['endDateTime'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * This method return mulitple flyer relation to flyerDisplayStore.
     *
     * @return collection
     */
    public function flyerDisplayStore()
    {
        return $this->hasMany(FlyerDisplayStore::class, 'flyerPlanId')->where('delFlg', FlyerDisplayStore::STATUS_NOTDELETED);
    }

    /**
     * This method return mulitple flyer relation to displayTargetFlyer.
     *
     * @return collection
     */
    public function displayTargetFlyer()
    {
        return $this->hasMany(DisplayTargetFlyer::class, 'flyerPlanId')->where('delFlg', DisplayTargetFlyer::STATUS_NOTDELETED);
    }

    /**
     * This method return mulitple flyer relation to displayTargetFlyerUB.
     *
     * @return collection
     */
    public function displayTargetFlyerUB()
    {
        return $this->hasMany(DisplayTargetFlyerUB::class, 'flyerPlanId')->where('delFlg', DisplayTargetFlyerUB::STATUS_NOTDELETED);
    }

    /**
     * This method return mulitple flyer relation to displayTargetFlyerAO.
     *
     * @return collection
     */
    public function displayTargetFlyerAO()
    {
        return $this->hasMany(DisplayTargetFlyerAO::class, 'flyerPlanId')->where('delFlg', DisplayTargetFlyerAO::STATUS_NOTDELETED);
    }
}
