<?php

namespace App\Traits\Rules;

use Illuminate\Http\UploadedFile;
use App\Helpers\Excel;
use App\Helpers\Upload;
use App\Models\Product;

trait CsvSpecifiedProductRuleTrait
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
    public static function getValidProductCodeFromExcelUrl($resource, bool $isReturnBool)
    {
        $rtn = [];
        $hasInvalidArray = false;
        if (!empty($resource)) {
            if (Upload::isValidFileType($resource, \Globals::CSV_ACCEPTEDMIMES)) {
                $data = [];

                if ($resource instanceof UploadedFile) {
                    $data = Excel::getColumnValuesByUploadedFile($resource, [0]);
                } elseif (is_string($resource)) {
                    $data = Excel::getColumnValuesByUrl($resource, [0]);
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

                        if ($index != 0) {
                            if (isset($datum[0]) && is_numeric(trim((string)$datum[0]))) {
                                $productCode = trim((string)$datum[0]);
                                $productCode = (int) $productCode;
    
                                if (strlen((string) $productCode) <= Product::CHARCOUNT_PRODUCTCODE) {
                                    $productCode = (string) $productCode;
                                    $productCode = str_pad($productCode, Product::CHARCOUNT_PRODUCTCODE, '0', STR_PAD_LEFT);
                                    $isProductExist = false;
    
                                    if ($isReturnBool) {
                                        $isProductExist = Product::isProductCodeExist($productCode);
                                    } else {
                                        $product = Product::matchingProductCode($productCode);
    
                                        if (!empty($product)) {
                                            $datum = $product->toArray();
                                            $isProductExist = !empty($datum);
                                        }
                                    }
    
                                    if ($isProductExist) {
                                        $rtn[] = $datum;
                                    } else {
                                        $error = __('messages.custom.productCodeNotFound');
                                    }
                                } else {
                                    $error = __('messages.custom.invalidProductCode');
                                }
                            } elseif ($datum != $data[0] || !is_string($datum[0])) {
                                $error = __('messages.custom.invalidProductCode');
                            }
    
                            if (!empty($error)) {
                                $hasInvalidArray = true;
                                array_push($datum, $error);
                            }
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
