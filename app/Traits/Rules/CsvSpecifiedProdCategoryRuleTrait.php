<?php

namespace App\Traits\Rules;

use Illuminate\Http\UploadedFile;
use App\Helpers\Excel;
use App\Helpers\Upload;
use App\Models\DepartmentClassification;

trait CsvSpecifiedProdCategoryRuleTrait
{
    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

    /**
     * get data from excel resource
     *
     * @param String|Null $resource
     * @param Bool $isReturnBool - set to true if only wants to return boolean value, and only get the first valid value of the array data
     * @return Array|Bool $rtn
     */
    public static function getValidProductCategoryFromExcelUrl($resource, bool $isReturnBool = false)
    {
        $rtn = [];
        $hasInvalidArray = false;

        if (!empty($resource)) {
            $data = [];

            if (Upload::isValidFileType($resource, \Globals::CSV_ACCEPTEDMIMES)) {
                if ($resource instanceof UploadedFile) {
                    $data = Excel::getColumnValuesByUploadedFile($resource, [0, 1, 2]);
                } elseif (is_string($resource)) {
                    $data = Excel::getColumnValuesByUrl($resource, [0, 1, 2]);
                }

                if (count($data) != 0) {
                    $invalidArray = [];
                    $data = json_encode($data);
                    $data = str_replace('\u0000', '', $data);
                    $data = str_replace('\"', '', $data);
                    $data = str_replace('\n', '', $data);
                    $data = json_decode($data);

                    foreach ($data as $index => $datum) {
                        $error = '';

                        if (isset($datum[0]) && isset($datum[1]) && isset($datum[2])) {
                            $departmentCode = $datum[0];
                            $midClassificationCode = $datum[1];
                            $subClassCode = $datum[2];

                            if (is_numeric(trim((string)$departmentCode)) && is_numeric(trim((string)$midClassificationCode)) && is_numeric(trim((string)$subClassCode))) {
                                $departmentCode = (int) trim((string)$departmentCode);
                                $midClassificationCode = (int) trim((string)$midClassificationCode);
                                $subClassCode = (int) trim((string)$subClassCode);
                                $isCategoryExist = DepartmentClassification::isCategoryCodeExist($departmentCode, $midClassificationCode, $subClassCode);

                                if ($isCategoryExist) {
                                    $rtn[] = $datum;
                                } else {
                                    $error = __('messages.custom.productCodeNotFound');
                                }
                            } elseif ($datum != $data[0] || !is_string($datum[0])) {
                                $error = __('messages.custom.invalidProductCode');
                            }
                        } elseif (!empty($datum) && ($index != 0 || (isset($datum[0]) ? !is_string($datum[0]) : false))) {
                            $error = __('messages.custom.invalidProductCode');
                        }

                        if (!empty($error)) {
                            $hasInvalidArray = true;
                            array_push($datum, $error);
                        }

                        $invalidArray[] = $datum;
                    }
                }
            }
        }

        if ($isReturnBool) {
            $rtn = !$hasInvalidArray && count($rtn) != 0;
        }

        if (!empty($resource)) {
            if ($hasInvalidArray && $resource instanceof UploadedFile) {
                $url = Excel::arrayToCsv($invalidArray, __('words.Error') . $resource->getClientOriginalName());
                session()->flash('invalidCsvUrl', $url);
            }
        }

        return $rtn;
    }
}
