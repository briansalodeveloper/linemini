<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Helpers\Upload;

class CsvExtensionRule implements Rule
{
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
            $rtn = Upload::isValidFileType($value, \Globals::CSV_ACCEPTEDMIMES);
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
        return __('validation.mimes', [
            'attributes' => __('words.Csv'),
            'values' => implode(',', \Globals::CSV_ACCEPTEDMIMES),
        ]);
    }
}
