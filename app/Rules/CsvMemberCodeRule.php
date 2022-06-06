<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Helpers\Upload;
use App\Traits\Rules\CsvMemberCodeRuleTrait;

class CsvMemberCodeRule implements Rule
{
    use CsvMemberCodeRuleTrait;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $rtn = true;

        if (!empty($value)) {
            if (Upload::isValidFileType($value, \Globals::CSV_ACCEPTEDMIMES)) {
                $rtn = static::getValidUnionMemberCodeFromExcelUrl($value, true);
            }
        }

        return $rtn;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('messages.custom.invalidCsvData');
    }
}
