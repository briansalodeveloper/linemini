<?php

namespace App\Models;

class DepartmentClassification extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'M_DepartmentClassification';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'departmentClassificationId ';

    /*======================================================================
     * STATIC FUNCTIONS
     *======================================================================*/

    /**
     * Check if category exists based on combinations of codes: departmentCode, middleClassificationCode, and subclassCode.
     *
     * @param int $departmentCode
     * @param int $midClassificationCode
     * @param int $subClassCode
     *
     * @return boolean
     */
    public static function isCategoryCodeExist(int $departmentCode, int $midClassificationCode, int $subClassCode)
    {
        return self::where('departmentCode', $departmentCode)
            ->where('middleClassificationCode', $midClassificationCode)
            ->where('subclassCode', $subClassCode)
            ->where('delFlg', 0)->exists();
    }
}
