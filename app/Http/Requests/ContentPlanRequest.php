<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ContentPlan;
use App\Rules\CsvMemberCodeRule;

class ContentPlanRequest extends FormRequest
{
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
        if (request()->has(config('searchQuery.param.copy')) && !empty($this->contentPlanId)) {
            $rtn = [];
        } else {
            $isRegister = empty($this->contentPlanId);

            $rtn = [
                'openingLetter' => 'required|string',
                'contentTypeNews' => 'required|integer',
                'displayTargetFlg' => 'required|integer',
                'openingImg' => 'required|string',
            ];

            if (empty(trim($this->startDateTime, ' '))) {
                $rtn['selectPublicationDateTime'] = 'required|integer';
            }

            if (!is_null($this->selectPublicationDateTime) || $this->selectPublicationDateTime == 1) {
                $rtn['startDateTime'] = 'required|date';
                $rtn['endDateTime'] = 'required|date|after:startDate';
            }

            if ($this->displayTargetFlg == ContentPlan::DSPTARGET_UNIONMEMBER && ($isRegister || (!$isRegister && (empty($this->displayTargetContent) || !empty($this->unionMemberCsv))))) {
                $rtn['unionMemberCsv'] = ['required', 'string', new CsvMemberCodeRule()];
            }

            if ($this->displayTargetFlg == ContentPlan::DSPTARGET_UB) {
                $rtn['utilizationBusiness'] = 'required|array';
            }

            if ($this->displayTargetFlg == ContentPlan::DSPTARGET_AO) {
                $rtn['affiliationOffice'] = 'required|array';
            }
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
        $utilizationBusiness = $this->utilizationBusiness;
        $affiliationOffice = $this->affiliationOffice;

        if (empty($utilizationBusiness)) {
            $utilizationBusiness = [];
        }

        if (empty($affiliationOffice)) {
            $affiliationOffice = [];
        }

        $this->merge([
            'startDateTime' => "$this->startDate $this->startTime",
            'endDateTime' => "$this->endDate $this->endTime",
            'utilizationBusiness' => $utilizationBusiness,
            'affiliationOffice' => $affiliationOffice,
        ]);

        request()->merge($this->all());
    }
}
