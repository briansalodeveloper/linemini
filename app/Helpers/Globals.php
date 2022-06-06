<?php

namespace App\Helpers;

use App\Helpers\Upload;
use App\Interfaces\ContentPlanRepositoryInterface;
use App\Interfaces\CouponPlanRepositoryInterface;
use App\Interfaces\FlyerPlanRepositoryInterface;
use App\Interfaces\StampPlanRepositoryInterface;
use App\Interfaces\MessageRepositoryInterface;
use App\Models\ContentPlan;
use App\Models\StampPlan;
use App\Models\CouponPlan;
use App\Models\Message;
use App\Models\FlyerPlan;
use App\Models\UnionLine;

class Globals
{
    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const FILETYPE_IMAGE = 'image';
    const FILETYPE_CSV = 'csv';

    const CSV_ACCEPTEDEXTENSION = ['csv'];
    const CSV_ACCEPTEDMIMES = ['csv', 'xlsx'];
    const IMG_ACCEPTEDEXTENSION = ['gif', 'jpg', 'jpeg', 'png'];
    const CONVERSION_BYTETOKILOBYTE = 1024;

    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

    /**
     * Globals::__()
     * return a concatenated by lang/locale by space (" ")
     *
     * @param Strin/Int/Object/Array $trans
     * @return String $rtn
     */
    public static function __($trans)
    {
        $rtn = '';

        if (!is_array($trans)) {
            $trans = [$trans];
        }

        foreach ($trans as $ind => $tran) {
            if ($ind != 0) {
                $rtn .= ' ';
            }

            $rtn .= __($tran);
        }

        return $rtn;
    }

    /**
     * Globals::implode()
     * return a concatenated array with a set of character string combination
     *
     * @param Array $array
     * @param String $delimeter
     * @param String/Null $pre - prefix to be added every loop
     * @return String $rtn
     */
    public static function implode($array, $delimeter, $pre = null)
    {
        $rtn = '';

        foreach ($array as $ind => $ar) {
            if ($ind != 0) {
                $rtn .= $delimeter;
            }

            if (!empty($pre)) {
                $rtn .= $pre;
            }

            $rtn .= $ar;
        }

        return $rtn;
    }

    /**
     * Globals::paginateLinks($paginatedList, $blade)
     *
     * @param LengthAware $paginatedList
     * @param String|Null $blade
     */
    public static function paginateLinks($paginatedList, $blade = null)
    {
        $requestData = request()->all();

        if (!empty($blade)) {
            $rtn = $paginatedList->appends(collect($requestData)->reject(function ($item, $key) {
                return strpos($key, '//') !== false;
            })->map(function ($item, $key) {
                return empty($item) ? '' : $item;
            })->toArray())->links($blade);
        } else {
            $rtn = $paginatedList->appends(collect($requestData)->reject(function ($item, $key) {
                return strpos($key, '//') !== false;
            })->map(function ($item, $key) {
                return empty($item) ? '' : $item;
            })->toArray())->links();
        }

        return $rtn;
    }

    /**
     * Globals::mContentPlan()
     * return a model class (ContentPlan)
     *
     * @return ContentPlan
     */
    public static function mContentPlan()
    {
        return ContentPlan::class;
    }

    /**
     * Globals::mStampPlan()
     * return a model class (StampPlan)
     *
     * @return StampPlan
     */
    public static function mStampPlan()
    {
        return StampPlan::class;
    }

    /**
     * Globals::mCouponPlan()
     * return a model class (CouponPlan)
     *
     * @return CouponPlan
     */
    public static function mCouponPlan()
    {
        return CouponPlan::class;
    }

    /**
     * Globals::mFlyerPlan()
     * return a helper class (FlyerPlan)
     *
     * @return Upload
     */
    public static function mFlyerPlan()
    {
        return FlyerPlan::class;
    }

    /**
     * Globals::mMessage()
     * return a model class (Message)
     *
     * @return Message
     */
    public static function mMessage()
    {
        return Message::class;
    }

    /**
     * Globals::mUnionLine()
     * return a model class (UnionLine)
     *
     * @return UnionLine
     */
    public static function mUnionLine()
    {
        return UnionLine::class;
    }

    /**
     * Globals::hUpload()
     * return a helper class (Upload)
     *
     * @return Upload
     */
    public static function hUpload()
    {
        return Upload::class;
    }

    /**
     * Globals::iCoupon()
     * return a interface class (CouponPlan)
     *
     * @return CouponPlanRepositoryInterface
     */
    public static function iCoupon()
    {
        return CouponPlanRepositoryInterface::class;
    }

    /**
     * Globals::iContent()
     * return a interface class (ContentPlan)
     *
     * @return ContentPlanRepositoryInterface
     */
    public static function iContent()
    {
        return ContentPlanRepositoryInterface::class;
    }

    /**
     * Globals::iFlyer()
     * return a interface class (FlyerPlan)
     *
     * @return FlyerPlanRepositoryInterface
     */
    public static function iFlyer()
    {
        return FlyerPlanRepositoryInterface::class;
    }

    /**
     * Globals::iMessage()
     * return a interface class (Messages)
     *
     * @return MessageRepositoryInterface
     */
    public static function iMessage()
    {
        return MessageRepositoryInterface::class;
    }

    /**
     * Globals::iStamp()
     * return a interface class (Stamp)
     *
     * @return StampRepositoryInterface
     */
    public static function iStamp()
    {
        return StampPlanRepositoryInterface::class;
    }
}
