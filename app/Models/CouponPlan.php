<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\Upload;
use App\Traits\CustomDateTrait;
use App\Models\Coupon\DisplayTargetCoupon;
use App\Models\Coupon\DisplayTargetCouponAO;
use App\Models\Coupon\DisplayTargetCouponUB;
use App\Models\Coupon\CouponPlanProduct;
use App\Models\Coupon\CouponPlanTargetClass;

class CouponPlan extends MainModel
{
    use CustomDateTrait;

    /**
     * @var string $table
     */
    protected $table = 'M_CuponPlan';

    /**
     * @var string $primaryKey
     */
    protected $primaryKey = 'cuponPlanId';

    /**
     * @var $fillables
     */
    protected $fillable = [
        'cuponName',
        'cuponType',
        'priorityDisplayFlg',
        'cuponDisplayFlg',
        'startDate',
        'startTime',
        'endDate',
        'endTime',
        'useFlg',
        'useCount',
        'useTime',
        'pointGrantFlg',
        'pointGrantPurchasesPrice',
        'pointGrantPurchasesCount',
        'increaseFlg',
        'grantPoint',
        'grantPointSub',
        'grantCuponPlanId',
        'productFlg',
        'cuponImg',
        'cuponText',
        'updateDate',
        'updateUser',
        'delFlg',
        'storeFlg',
        'autoEntryFlg'
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'couponTypeStr',
        'status',
        'statusStr',
        'isThumbnailExist',
        'isEmpty',
        'isNotEmpty'
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    /** TYPES CODES */
    const CODE_PRODUCTPURCHASE = 1;
    const CODE_TRIALPRODUCT = 2;
    const CODE_CATEGORY = 3;
    const CODE_VISITGUIDANCE = 4;
    const CODE_VISITGUIDESUPPORT = 5;
    const CODE_SHOWGRATITUDE = 6;
    const CODE_VIP = 90;
    const CODE_STAMP_ACHIEVEMENT = 7;

    /** DISPLAY TARGET CODES */
    const DSPTARGET_UNCONDITIONAL = 0;
    const DSPTARGET_UNIONMEMBER = 1;
    const DSPTARGET_UB = 2;
    const DSPTARGET_AO = 3;
    const DSPTARGET_BONUS = 4;
    /** HIGH LEVEL DISPLAY OPTION CODES */
    const CODE_DONTDISPLAY = 0;
    const CODE_DISPLAY = 1;
    /** AUTO ENTRY CODES */
    const CODE_NOTAUTOENTRY = 0;
    const CODE_AUTOENTRY = 1;
    /** POINTGRANT FLAG CODES */
    const CODE_OPENAPP = 3;
    const CODE_AMOUNT = 1;
    const CODE_PURCHASENUM = 2;
    const CODE_NOENTRYOPENAPP = 3;
    /** USE FLAG CODES */
    const CODE_UNLIMITEDUSEFLG = 0;
    const CODE_LIMITEDUSEFLG = 1;
    /** INCREASE FLAG CODES */
    const CODE_POINTSAWARDED = 1;
    const CODE_GIVECOUPONS = 2;
    const CODE_APPLYATSTORE = 3;
    const CODE_PRODUCTREDEMPTION = 4;
    /** TARGET PRODUCT CODES */
    const CODE_UNSPECIFIED = 1;
    const CODE_PRODUCTDESIGNATION = 2;
    const CODE_CATEGORYDESIGNATION = 3;

    const DATEONLY_DELIMETERNONEDEFVAL = '00000000';
    const TIMEONLY_DELIMETERNONEDEFVAL = '000000';
    const DATEONLY_YMDFORMAT = 'Ymd';
    const TIMEONLY_HISFORMAT = 'His';
    const DATETIME_FORMAT = 'm/d/Y g:i A';
    const HIDE_DISPLAY = 0;
    const CODE_NOTSPECIFIED = 0;
    const CODE_SPECIFIED = 1;

    const COUPONTYPE_LIST = [
        self::CODE_PRODUCTPURCHASE => 'words.ProductPurchaseCoupon',
        self::CODE_TRIALPRODUCT => 'words.TrialProductCoupon',
        self::CODE_CATEGORY => 'words.CategoryCoupon',
        self::CODE_VISITGUIDANCE => 'words.VisitGuidanceCoupon',
        self::CODE_VISITGUIDESUPPORT => 'words.HereSupportVisitGuidanceCoupon',
        self::CODE_SHOWGRATITUDE => 'words.VisitSupportGratitudeCoupon',
        self::CODE_VIP => 'words.HighValuePurchaserCoupon',
        self::CODE_STAMP_ACHIEVEMENT => 'words.StampAchievementPrivilegeCoupon'
    ];
    const DSPTARGET_OPTIONS = [
        self::DSPTARGET_UNCONDITIONAL  => 'words.Unconditional',
        self::DSPTARGET_UNIONMEMBER  => 'words.UnionMemberDesignation',
        self::DSPTARGET_UB  => 'words.UserBusinessDesignation',
        self::DSPTARGET_AO  => 'words.OfficeDesignation'
    ];
    const HIGHLVLDISPLAY_OPTIONS = [
        self::CODE_DISPLAY  => 'words.DisplayAtTop',
        self::CODE_DONTDISPLAY  => 'words.DontDisplayOnTop'
    ];
    const REGULARLVLDISPLAY_OPTIONS = [
        self::CODE_DONTDISPLAY  => 'words.DontDisplay',
        self::CODE_DISPLAY  => 'words.Indicate'
    ];
    const AUTOENTRY_OPTIONS = [
        self::CODE_AUTOENTRY  => 'words.MakeAutoEntry',
        self::CODE_NOTAUTOENTRY  => 'words.DontMakeAutoEntry',
    ];
    const POINTGRANTFLG_OPTIONS = [
        self::CODE_AMOUNT  => 'words.AmntOfMoney',
        self::CODE_PURCHASENUM  => 'words.NumberOfPurchases',
        self::CODE_OPENAPP  => 'words.OpenAppBenefit',
    ];
    const PUBLICATION_OPTIONS = [
        ['words.PublishSoon', 'words.BookAndPublish'],
        ['words.UpdateSoon', 'words.BookAndRenew']
    ];
    const USEFLG_OPTIONS = [
        self::CODE_LIMITEDUSEFLG  => 'words.OncePerday',
        self::CODE_UNLIMITEDUSEFLG  => 'words.OnceOnParticularPeriod',
    ];
    const INCREASEFLG_OPTIONS = [
        // self::CODE_GIVECOUPONS  => 'words.GiveACoupon',
        self::CODE_POINTSAWARDED  => 'words.Point',
        self::CODE_APPLYATSTORE  => 'words.ApplyAtStore',
        self::CODE_PRODUCTREDEMPTION  => 'words.ProductRedemption'
    ];
    const TARGETPROD_OPTIONS = [
        self:: CODE_UNSPECIFIED => 'words.UnSpecified',
        self:: CODE_PRODUCTDESIGNATION => 'words.ProductDesignation',
        self:: CODE_CATEGORYDESIGNATION => 'words.SelectionDesignation'
    ];
    const STOREFLG_OPTIONS = [
        self:: CODE_NOTSPECIFIED => 'words.NotSpecified',
        self:: CODE_SPECIFIED => 'words.Specify'
    ];
    /*======================================================================
     * MUTATORS
     *======================================================================*/

    /**
     * format startDate attr value
     *
     * @param String $value
     *
     * @return void
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['startDate'] = self::formatStrDateTime($value, self::DATEONLY_YMDFORMAT, self::DATEONLY_DELIMETERNONEDEFVAL);
    }

    /**
     * format startTime attr value
     *
     * @param string $value
     *
     * @return void
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['startTime'] = self::formatStrDateTime($value, self::TIMEONLY_HISFORMAT, self::TIMEONLY_DELIMETERNONEDEFVAL);
    }

    /**
     * format endDate attr value
     *
     * @param string $value
     *
     * @return void
     */
    public function setEndDateAttribute($value)
    {
        $this->attributes['endDate'] = self::formatStrDateTime($value, self::DATEONLY_YMDFORMAT, self::DATEONLY_DELIMETERNONEDEFVAL);
    }

    /**
     * format endTime attr value
     *
     * @param string $value
     *
     * @return void
     */
    public function setEndTimeAttribute($value)
    {
        $this->attributes['endTime'] = self::formatStrDateTime($value, self::TIMEONLY_HISFORMAT, self::TIMEONLY_DELIMETERNONEDEFVAL);
    }

    /*======================================================================
     * ACCESSORS
     *======================================================================*/

    /**
     * cuponImg
     *
     * @return String
     */
    public function getCuponImgAttribute($value)
    {
        return urldecode($value);
    }

    /**
     * couponTypeStr
     *
     * @return String
     */
    public function getCouponTypeStrAttribute(): string
    {
        $rtn = '';

        if (isset(self::COUPONTYPE_LIST[$this->cuponType])) {
            $rtn = __(self::COUPONTYPE_LIST[$this->cuponType]) ?? '';
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
     * isThumbnailExist
     *
     * @return bool $rtn
     */
    public function getIsThumbnailExistAttribute(): bool
    {
        $rtn = false;

        if (!empty($this->cuponImg)) {
            $rtn = Upload::exist($this->cuponImg);
        }

        return $rtn;
    }

    /**
     * startDateTime
     *
     * @return String
     */
    public function getStartDateTimeAttribute(): string
    {
        return self::formatStrDateAndTime($this->attributes['startDate'], $this->attributes['startTime'], self::DATETIME_FORMAT, '');
    }

    /**
     * endDateTime
     *
     * @return String
     */
    public function getEndDateTimeAttribute(): string
    {
        return self::formatStrDateAndTime($this->attributes['endDate'], $this->attributes['endTime'], self::DATETIME_FORMAT, '');
    }

    /*======================================================================
     * ACCESSORS (ON CALL) (ON RUNTIME)
     *======================================================================*/

    /**
     * displayTargetCouponIdList
     *
     * @return Array $rtn
     */
    public function getDisplayTargetCouponIdListAttribute()
    {
        return $this->displayTargetCoupon->pluck('kumicd')->toArray();
    }

    /**
     * displayTargetCouponAoAffiliationOfficeIdList
     *
     * @return Array $rtn
     */
    public function getDisplayTargetCouponAoAffiliationOfficeIdListAttribute()
    {
        return $this->displayTargetCouponAO->pluck('affiliationOfficeId')->toArray();
    }

    /**
     * displayTargetCouponUbUtilizationBusinessIdList
     *
     * @return Array $rtn
     */
    public function getDisplayTargetCouponUbUtilizationBusinessIdListAttribute()
    {
        return $this->displayTargetCouponUB->pluck('utilizationBusinessId')->toArray();
    }

    /**
     * couponPlanStoreIdList
     *
     * @return Array $rtn
     */
    public function getCouponPlanStoreIdListAttribute()
    {
        return $this->stores->pluck('storeId')->toArray();
    }

    /**
     * couponPlanProductList
     *
     * @return Array $rtn
     */
    public function getCouponPlanProductListAttribute()
    {
        return $this->products->pluck('pivot')->toArray();
    }

    /**
     * couponPlanTargetClassList
     *
     * @return Array $rtn
     */
    public function getCouponPlanTargetClassListAttribute()
    {
        return $this->couponPlanTargetClass->map(function ($item) {
            return [
                'departmentCode' => $item['departmentCode'],
                'majorClassificationCode' => $item['majorClassificationCode'],
                'middleClassificationCode' => $item['middleClassificationCode'],
                'subclassCode' => $item['subclassCode']
            ];
        })->toArray();
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * This method return multiple coupon relation to displayTargetCoupon.
     *
     * @return collection
     */
    public function displayTargetCoupon()
    {
        return $this->hasMany(DisplayTargetCoupon::class, 'cuponPlanId', 'cuponPlanId')->where('delFlg', DisplayTargetCoupon::STATUS_NOTDELETED);
    }

    /**
     * This method return multiple contentplan relation to displayTargetCouponAO.
     *
     * @return collection
     */
    public function displayTargetCouponAO()
    {
        return $this->hasMany(DisplayTargetCouponAO::class, 'cuponPlanId', 'cuponPlanId')->where('delFlg', DisplayTargetCouponAO::STATUS_NOTDELETED);
    }

    /**
     * This method return multiple contentplan relation to CouponPlanTargetClass.
     *
     * @return collection
     */
    public function couponPlanTargetClass()
    {
        return $this->hasMany(CouponPlanTargetClass::class, 'cuponPlanId', 'cuponPlanId')->where('delFlg', DisplayTargetCouponAO::STATUS_NOTDELETED);
    }

    /**
     * This method return multiple contentplan relation to displayTargetCouponUB.
     *
     * @return collection
     */
    public function displayTargetCouponUB()
    {
        return $this->hasMany(DisplayTargetCouponUB::class, 'cuponPlanId', 'cuponPlanId')->where('delFlg', DisplayTargetCouponUB::STATUS_NOTDELETED);
    }

    /**
     * couponProducts
     */
    public function couponProducts()
    {
        return $this->hasMany(CouponPlanProduct::class, 'cuponPlanId', 'cuponPlanId')->distinct('productJancode')->where('delFlg', CouponPlanProduct::STATUS_NOTDELETED);
    }

    /**
     * stores
     */
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'T_CuponPlanStore', 'cuponPlanId', 'storeId', 'cuponPlanId', 'storeId')->where('T_CuponPlanStore.delFlg', Store::STATUS_NOTDELETED);
    }

    /**
     * products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'T_CuponPlanProduct', 'cuponPlanId', 'productJancode', 'cuponPlanId', 'productCode')->distinct('productJancode')->where('T_CuponPlanProduct.delFlg', Store::STATUS_NOTDELETED)->withPivot('productName', 'productImg', 'productText');
    }
}
