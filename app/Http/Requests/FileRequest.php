<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\Upload;
use App\Rules\CsvExtensionRule;
use App\Rules\CsvMemberCodeRule;
use App\Rules\CsvSpecifiedProdCategoryRule;
use App\Rules\CsvSpecifiedProductRule;
use App\Traits\Rules\FileRuleTrait;
use App\Traits\Requests\FileRequestTrait;

class FileRequest extends FormRequest
{
    use FileRuleTrait;
    use FileRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rtn = [];
        $fileType = request()->get('fileType');
        $validateField = request()->get('validateField');

        if ($fileType == \Globals::FILETYPE_IMAGE) {
            if ($this->image) {
                $rtn[$validateField] = $this->imageRule('required');
            }
        } elseif ($fileType == \Globals::FILETYPE_CSV) {
            if ($this->csv) {
                $isValidExtension = Upload::isValidFileType($this->csv, \Globals::CSV_ACCEPTEDMIMES);

                switch ($this->validateField) {
                    case 'specifiedProdCodeCsvTrigger':
                        $rtn[$validateField] = ['required', new CsvExtensionRule()];

                        if ($isValidExtension) {
                            $rtn[$validateField][] = new CsvSpecifiedProductRule();
                        }
                        break;
                    case 'prodCategoryCsvTrigger':
                        $rtn[$validateField] = ['required', new CsvExtensionRule()];

                        if ($isValidExtension) {
                            $rtn[$validateField][] = new CsvSpecifiedProdCategoryRule();
                        }
                        break;
                    default:
                        $rtn[$validateField] = ['required', new CsvExtensionRule()];

                        if ($isValidExtension) {
                            $rtn[$validateField][] = new CsvMemberCodeRule();
                        }
                        break;
                }
            }
        }

        return $rtn;
    }

    /**
     * Modifies laravel failedValidation() to add additional data
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $this->customFailedValidation($validator);
    }
}
