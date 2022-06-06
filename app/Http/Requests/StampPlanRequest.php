<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CsvMemberCodeRule;
use App\Rules\CsvSpecifiedProdCategoryRule;
use App\Rules\CsvSpecifiedProductRule;

class StampPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $isRegister = empty($this->contentPlanId);
        $rtn = [
                'stampName' => 'required',
                'stampType' => 'required',
                'stampDisplayFlg' => 'required',
                'useCount' => 'required|digits_between:0,3',
                'stampGrantFlg' => 'required',
                'stampAchievement' => 'required|numeric|min:1|max:10',
                'increaseFlg' => 'required',
                'productFlg' => 'required',
                'stampImage' => 'required',
                'stampText' => 'required',
        ];

        if (empty(trim($this->startDateTime, ' '))) {
            $rtn['selectPublicationDateTime'] = 'required';
        }

        if (!is_null($this->selectPublicationDateTime) || $this->selectPublicationDateTime == 1) {
            $rtn['startDateTime'] = 'required|date';
            $rtn['endDateTime'] = 'required|date|after:startDate';
        }

        if ($this->stampDisplayFlg == '1' && ( empty($this->displayTargetStamp) || !empty($this->unionMemberCode)) ) {
            $rtn['unionMemberCode'] = ['required','string', new CsvMemberCodeRule()];
        }
        
        if ($this->stampDisplayFlg == '2') {
            $rtn['utilizationBusiness'] = 'required';
        }

        if ($this->stampDisplayFlg == '3') {
            $rtn['affiliationOffice'] = 'required';
        }

        if ($this->stampGrantFlg == '1') {
            $rtn['SpecifiedAmount'] = 'required';
        }

        if ($this->stampGrantFlg == '2') {
            $rtn['SpecifiedNumberOfPurchase'] = 'required';
        }

        if ($this->increaseFlg == '1') {
            $rtn['SpecifiedNumberOfPoints'] = 'required';
        }

        if ($this->increaseFlg == '2') {
            $rtn['SpecifiedCouponId'] = 'required';
        }

        if ($this->increaseFlg == '4') {
            $rtn['csvUploadProductRedumption'] = ['required', new CsvSpecifiedProductRule()];
        }

        if ($this->productFlg == '3') {
            $rtn['departmentCode'] = 'required';
        }

        if ($this->productFlg == '2') {
            $rtn['specifiedProdCodeCsv'] = ['required', 'string', new CsvSpecifiedProductRule()];
        }

        return $rtn;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'startDateTime' => "$this->startDate $this->startTime",
            'endDateTime' => "$this->endDate $this->endTime",
        ]);

        request()->merge($this->all());
    }
}
