<?php

namespace App\Traits\Rules;

trait FileRuleTrait
{
    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

    /**
     * merge the default global rule and custom rule additional rule to be use in validation
     *
     * @param Array ...$additionalRule
     * @return Array $rtn
     */
    public function imageRule(...$additionalRule)
    {
        $rtn = ['max:2000', 'mimes:' . implode(',', \Globals::IMG_ACCEPTEDEXTENSION)];
        $rtn = array_merge($rtn, $additionalRule);

        return $rtn;
    }
}
