<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\FlyerPlan;
use App\Rules\CsvMemberCodeRule;

class FlyerPlanRequest extends FormRequest
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
        if (request()->has(config('searchQuery.param.copy')) && !empty($this->flyerPlanId)) {
            $rtn = [];
        } else {
            $isRegister = empty($this->flyerPlanId);

            $rtn = [
                'flyerName' => 'required',
                'displayTargetFlg' => 'required|integer',
                'displayStore' => 'required|array',
                'flyerImg' => 'required|string',
                'flyerUraImg' => 'string',
            ];

            if (empty(trim($this->startDateTime, ' '))) {
                $rtn['selectPublicationDateTime'] = 'required|integer';
            }

            if (!is_null($this->selectPublicationDateTime) || $this->selectPublicationDateTime == 1) {
                $rtn['startDateTime'] = 'required|date';
                $rtn['endDateTime'] = 'required|date|after:startDateTime';
            }

            if ($this->displayTargetFlg == FlyerPlan::DSPTARGET_UNIONMEMBER && ($isRegister || (!$isRegister && (empty($this->displayTargetFlyer) || !empty($this->unionMemberCsv))))) {
                $rtn['unionMemberCsv'] = ['required', 'string', new CsvMemberCodeRule()];
            }

            if ($this->displayTargetFlg == FlyerPlan::DSPTARGET_UB) {
                $rtn['utilizationBusiness'] = 'required|array';
            }

            if ($this->displayTargetFlg == FlyerPlan::DSPTARGET_AO) {
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
        $flyerUraImg = $this->flyerUraImg;

        if (empty($utilizationBusiness)) {
            $utilizationBusiness = [];
        }

        if (empty($affiliationOffice)) {
            $affiliationOffice = [];
        }

        if (is_null($flyerUraImg)) {
            $flyerUraImg = '';
        }

        $this->merge([
            'displayStore' => !is_null($this->displayStore) ? $this->displayStore : [],
            'startDateTime' => "$this->startDate $this->startTime",
            'endDateTime' => "$this->endDate $this->endTime",
            'utilizationBusiness' => $utilizationBusiness,
            'affiliationOffice' => $affiliationOffice,
            'flyerUraImg' => $flyerUraImg,
        ]);

        request()->merge($this->all());
    }
}
