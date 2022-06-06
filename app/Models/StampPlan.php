<?php

namespace App\Models;

use Carbon\Carbon;

class StampPlan extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'M_StampPlan';

    /**
    * @var $primaryKey
    */
    protected $primaryKey = 'stampPlanId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'stampType',
        'stampDisplayFlg',
        'startDate',
        'startTime',
        'endDate',
        'endTime',
        'useFlg',
        'useCount',
        'stampGrantFlg',
        'stampGrantPurchasesPrice',
        'stampGrantPurchasesCount',
        'stampAchievement',
        'increaseFlg',
        'increasePoint',
        'increaseCupon',
        'productFlg',
        'stampName',
        'stampImg',
        'stampText',
        'updateDate',
        'updateUser',
        'delFlg',
        'storeFlg',
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
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'startDateTime',
        'endDateTime',
        'stampTypeStr',
        'status',
        'statusStr',
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const TYPE_ALWAYS = 01;
    const TYPE_CAMPAIGN = 02;
    const TYPE_PRODUCTUSAGE = 03;
    const TYPE_SELFREQUISITION = 04;

    const DSPTARGET_UNCONDITIONAL = 0;
    const DSPTARGET_UNIONMEMBER = 1;
    const DSPTARGET_UB = 2;
    const DSPTARGET_AO = 3;

    const GRANTFLG_AMOUNTMONEY = 1;
    const GRANTFLG_NUMBERPURCHASE = 2;
    const GRANTFLG_OPENTHEAPP = 3;
    const GRANTFLG_QRCODEREADING = 4;
    const GRANTFLG_AMOUNTFIXED = 5;
    const GRANTFLG_FIXEDNUMBER = 6;
    const GRANTFLG_OPENTHEAPPAUTOENTRY = 7;

    const INCREASEFLG_POINTSAWARDED = 1;
    const INCREASEFLG_COUPONGRANT = 2;
    const INCREASEFLG_APPLYSTORE = 3;
    const INCREASEFLG_REDEEMGOODS = 4;

    const PRODUCTFLG_NODESIGNATION = 1;
    const PRODUCTFLG_PRODUCTDESIGNATION = 2;
    const PRODUCTFLG_CLASSIFICATIONDESIGNATION = 3;

    const STATUS_COMINGSOON = 0;
    const STATUS_ENDOFPUBLICATION = 1;
    const STATUS_OPENNOW = 2;


    /*======================================================================
     * ACCESSORS
     *======================================================================*/


    /**
     * startDateTime
     *
     * @return String $rtn
     */
    public function getStartDateTimeAttribute()
    {
        $rtn = '';
        $date = $this->startDate;
        $time = $this->startTime;

        if (!empty($date)) {
            if (empty($time)) {
                $time = '';
            } else {
                $time = ' ' . $time;
            }

            $dateTime = Carbon::parse($date . $time);
            $rtn = $dateTime->format('Y/m/d H:i');
        }

        return $rtn;
    }
    
    /**
     * endDateTime
     *
     * @return String $rtn
     */
    public function getEndDateTimeAttribute()
    {
        $rtn = '';
        $date = $this->endDate;
        $time = $this->endTime;

        if (!empty($date)) {
            if (empty($time)) {
                $time = '';
            } else {
                $time = ' ' . $time;
            }

            $dateTime = Carbon::parse($date . $time);
            $rtn = $dateTime->format('Y/m/d H:i');
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
     * stampTypeStr
     *
     * @return String $rtn
     */
    public function getStampTypeStrAttribute(): string
    {
        $rtn = '';

        if ($this->stampType == self::TYPE_ALWAYS) {
             $rtn = __('words.AlwaysStamp');
        } elseif ($this->stampType == self::TYPE_CAMPAIGN) {
             $rtn = __('words.CampaignStamp');
        } elseif ($this->stampType == self::TYPE_PRODUCTUSAGE) {
             $rtn = __('words.ProductUsageStamp');
        } elseif ($this->stampType == self::TYPE_SELFREQUISITION) {
             $rtn = __('words.SelfRequisitionStamp');
        }

        return $rtn;
    }

    /*======================================================================
     * ACCESSORS (ON CALL) (ON RUNTIME)
     *======================================================================*/

    /**
     * displayTargetStampIdList
     *
     * @return Array
     */
    public function getDisplayTargetStampIdListAttribute()
    {
        return $this->displayTargetStamp->pluck('kumicd')->toArray();
    }

    /**
     * displayTargetStampAoAffiliationOfficeIdList
     *
     * @return Array
     */
    public function getDisplayTargetStampAoAffiliationOfficeIdListAttribute()
    {
        return $this->displayTargetStampAO->pluck('affiliationOfficeId')->toArray();
    }
    
    /**
     * displayTargetStampUbUtilizationBusinessIdList
     *
     * @return Array
     */
    public function getDisplayTargetStampUbUtilizationBusinessIdListAttribute()
    {
        return $this->displayTargetStampUB->pluck('utilizationBusinessId')->toArray();
    }

    /**
     * stampPlanStoreIdList
     *
     * @return Array
     */
    public function getStampPlanStoreIdListAttribute()
    {
        return $this->stampPlanStore->pluck('storeId')->toArray();
    }

    /**
     * stampPlanProductList
     *
     * @return Array $rtn
     */
    public function getStampPlanProductListAttribute()
    {
        return $this->products->pluck('pivot')->toArray();
    }

    /**
     * stampImageName
     *
     * @return String
     */
    public function getStampImageNameAttribute()
    {
        $getFilename = explode('/', $this->stampImg);
        return $getFilename[4];
    }

    /**
     * stampPlanTargetClassId
     *
     * @return collection
     */
    public function getStampPlanTargetClassIdAttribute()
    {
        return $this->stampPlanTargetClass;
    }

    /**
     * stampPlanTargetClassList
     *
     * @return Array $rtn
     */
    public function getStampPlanTargetClassListAttribute()
    {

        return $this->stampPlanTargetClass->map(function ($item) {
            return [
                'departmentCode' => $item['departmentCode'],
                'majorClassificationCode' => $item['majorClassificationCode'],
                'middleClassificationCode' => $item['middleClassificationCode'],
                'subclassCode' => $item['subclassCode']
            ];
        })->toArray();
    }

    /**
     * replicateData
     *
     * @return StampPlan
     */
    public function replicateData()
    {
        $clone = $this->replicate();
        $clone->push();

        $modelToReplicate = [
            'stampPlanStore' => $this->stampPlanStore,
            'displayTargetStamp' => $this->displayTargetStamp,
            'displayTargetStampAO' => $this->displayTargetStampAO,
            'displayTargetStampUB' => $this->displayTargetStampUB,
            'forStampPlanTargetClass' => $this->forStampPlanTargetClass,
        ];
        //to replicate data in other table
        foreach ($modelToReplicate as $key => $model) {
            if ($key == 'forStampPlanTargetClass') {
                if ($model != null) {
                    $clone->$key()->create($model->toArray());
                }
                 continue;
            }

            if ($model->isNotEmpty()) {
                foreach ($model as $data) {
                    $clone->$key()->create($data->toArray());
                }
            }
        }
        $clone->save();

        return $clone;
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * This method return multiple stampplan relation to DisplayTargetStamp.
     *
     * @return collection
     */
    public function displayTargetStamp()
    {
        return $this->hasMany(DisplayTargetStamp::class, 'stampPlanId', 'stampPlanId')->where('delFlg', DisplayTargetStamp::STATUS_NOTDELETED);
    }

    /**
     * This method return multiple stampplan relation to DisplayTargetStampAO.
     *
     * @return collection
     */
    public function displayTargetStampAO()
    {
        return $this->hasMany(DisplayTargetStampAO::class, 'stampPlanId', 'stampPlanId')->where('delFlg', DisplayTargetStampAO::STATUS_NOTDELETED);
    }

    /**
     * This method return multiple stampplan relation to DisplayTargetStampUB.
     *
     * @return collection
     */
    public function displayTargetStampUB()
    {
        return $this->hasMany(DisplayTargetStampUB::class, 'stampPlanId', 'stampPlanId')->where('delFlg', DisplayTargetStampUB::STATUS_NOTDELETED);
    }

    /**
     * This method return multiple stampplan relation to stampPlanStore.
     *
     * @return collection
     */
    public function stampPlanStore()
    {
        return $this->hasMany(StampPlanStore::class, 'stampPlanId', 'stampPlanId')->where('delFlg', StampPlanStore::STATUS_NOTDELETED);
    }

    /**
     * This method return multiple stampplan relation to StampPlanTargetClass.
     *
     * @return collection
     */
    public function stampPlanTargetClass()
    {
         return $this->hasMany(StampPlanTargetClass::class, 'stampPlanId', 'stampPlanId')->where('delFlg', DisplayTargetStampAO::STATUS_NOTDELETED);
    }

    /**
     * products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'T_StampPlanProduct', 'stampPlanId', 'productJancode', 'stampPlanId', 'productCode')->distinct('productJancode')->where('T_StampPlanProduct.delFlg', Store::STATUS_NOTDELETED)->withPivot('productName', 'productImg', 'productText');
    }
}
