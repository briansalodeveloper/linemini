<?php

namespace App\Traits\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait FileRequestTrait
{
    /**
     * Modifies laravel failedValidation() to add additional data
     *
     * @return void
     */
    protected function customFailedValidation(Validator $validator)
    {
        $custom = [];
        $fileType = request()->get('fileType');
        $validateField = request()->get('validateField');

        if ($validator->fails()) {
            if ($fileType == \Globals::FILETYPE_IMAGE) {
                if (!empty($this->image)) {
                    $custom['invalidLabel'] = $this->image->getClientOriginalName();
                }

                if (session()->has('invalidLabel')) {
                    $custom['invalidLabel'] = session()->get('invalidLabel');
                }
            } elseif ($fileType == \Globals::FILETYPE_CSV) {
                if (session()->has('invalidCsvUrl')) {
                    $custom['invalidCsvUrl'] = session()->get('invalidCsvUrl');
                } else {
                    if (!empty($this->csv)) {
                        $custom['invalidLabel'] = $this->csv->getClientOriginalName();
                    }
    
                    if (session()->has('invalidLabel')) {
                        $custom['invalidLabel'] = session()->get('invalidLabel');
                    }
                }
            }
        }

        session()->forget(['invalidCsvUrl', 'invalidLabel']);

        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => 'The given data was invalid.',
            'custom' => $custom
        ], 422));
    }
}
