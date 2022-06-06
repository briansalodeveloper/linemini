<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Rules\FileRuleTrait;
use App\Traits\Requests\FileRequestTrait;

class ImageRequest extends FormRequest
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
        $rtn = [
            'image' => $this->imageRule('required')
        ];

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
