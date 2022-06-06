<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Traits\Rules\CsvSpecifiedProdCategoryRuleTrait;

class CsvSpecifiedProdCategoryRule implements Rule
{
    use CsvSpecifiedProdCategoryRuleTrait;

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
            $rtn = static::getValidProductCategoryFromExcelUrl($value, true);
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
