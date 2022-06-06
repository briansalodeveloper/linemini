<?php

namespace App\Models\Coupon;

use App\Models\DepartmentClassification;
use App\Models\MainModel;

class CouponPlanTargetClass extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_CuponPlanTargetClass';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'cuponPlanTargetClassId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'cuponPlanId',
        'productName',
        'departmentCode',
        'majorClassificationCode',
        'middleClassificationCode',
        'subclassCode',
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
     * CONSTANTS
     *======================================================================*/

    const CODE_ALL = 0;

    /*======================================================================
     * ACCESSORS (ON CALL) (ON RUNTIME)
     *======================================================================*/

    /**
     * departmentClassificationCodeName
     *
     * @return String $rtn
     */
    public function getDepartmentClassificationCodeNameAttribute()
    {
        $rtn = '';
        $deptClass = $this->departmentClassificationDepartmentClassificationCode;

        if (!empty($deptClass)) {
            if ($deptClass->departmentCode == self::CODE_ALL) {
                $rtn = __('words.All');
            } else {
                $rtn = '(' . $deptClass->departmentCode . ') ' . $deptClass->departmentClassificationName;
            }
        }

        return $rtn;
    }

    /**
     * middleClassificationCodeName
     *
     * @return String $rtn
     */
    public function getMiddleClassificationCodeNameAttribute()
    {
        $rtn = '';
        $deptClass = $this->departmentClassificationMiddleClassificationCode;

        if (!empty($deptClass)) {
            if ($deptClass->middleClassificationCode == self::CODE_ALL) {
                $rtn = __('words.All');
            } else {
                $rtn = '(' . $deptClass->middleClassificationCode . ') ' . $deptClass->departmentClassificationName;
            }
        }

        return $rtn;
    }

    /**
     * subClassificationCodeName
     *
     * @return String $rtn
     */
    public function getSubClassificationCodeNameAttribute()
    {
        $rtn = '';
        $deptClass = $this->departmentClassificationSubClassificationCode;

        if (!empty($deptClass)) {
            if ($deptClass->subclassCode == self::CODE_ALL) {
                $rtn = __('words.All');
            } else {
                $rtn = '(' . $deptClass->subclassCode . ') ' . $deptClass->departmentClassificationName;
            }
        }

        return $rtn;
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * This method return a DepartmentClassification base on departmentCode
     * departmentClassificationDepartmentClassificationCode
     *
     * @return DepartmentClassification
     */
    public function departmentClassificationDepartmentClassificationCode()
    {
        return $this->hasOne(DepartmentClassification::class, 'departmentCode', 'departmentCode')
            ->where('middleClassificationCode', self::CODE_ALL)
            ->where('subclassCode', self::CODE_ALL)
            ->where('delFlg', DepartmentClassification::STATUS_NOTDELETED);
    }

    /**
     * This method return a DepartmentClassification base on middleClassificationCode
     * departmentClassificationMiddleClassificationCode
     *
     * @return DepartmentClassification
     */
    public function departmentClassificationMiddleClassificationCode()
    {
        return $this->hasOne(DepartmentClassification::class, 'middleClassificationCode', 'middleClassificationCode')
            ->where('departmentCode', '!=', self::CODE_ALL)
            ->whereNotNull('departmentCode')
            ->where('subclassCode', self::CODE_ALL)
            ->where('delFlg', DepartmentClassification::STATUS_NOTDELETED);
    }

    /**
     * This method return a DepartmentClassification base on subclassCode
     * departmentClassificationSubClassificationCode
     *
     * @return DepartmentClassification
     */
    public function departmentClassificationSubClassificationCode()
    {
        return $this->hasOne(DepartmentClassification::class, 'subclassCode', 'subclassCode')
            ->where('departmentCode', '!=', self::CODE_ALL)
            ->whereNotNull('departmentCode')
            ->where('middleClassificationCode', self::CODE_ALL)
            ->whereNotNull('middleClassificationCode')
            ->where('delFlg', DepartmentClassification::STATUS_NOTDELETED);
    }
}
