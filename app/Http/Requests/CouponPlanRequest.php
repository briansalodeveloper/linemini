<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CouponPlan;
use App\Rules\CsvMemberCodeRule;
use App\Rules\CsvSpecifiedProdCategoryRule;
use App\Rules\CsvSpecifiedProductRule;

class CouponPlanRequest extends FormRequest
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
        $isRegister = empty($this->couponPlanId);

        $rtn = [
            'cuponName' => 'required|string',
            'cuponType' => 'required|integer',
            'cuponDisplayFlg' => 'required|integer',
            'useCount' => 'required|integer|min:0|max:999',
            'useTime' => 'required|integer|min:0',
            'priorityDisplayFlg' => 'required|integer',
            'autoEntryFlg' => 'required|integer',
            'useFlg' => 'required|integer',
            'increaseFlg' => 'required|integer',
            'cuponImg' => 'required|string',
            'cuponText' => 'required|string',
            'productFlg' => 'required',
            'storeFlg' => 'required'
        ];

        if (empty(trim($this->startDateTime, ' '))) {
            $rtn['selectPublicationDateTime'] = 'required|integer';
        }

        if (!is_null($this->selectPublicationDateTime)) {
            $rtn['startDateTime'] = 'required|date';
            $rtn['endDateTime'] = 'required|date|after:startDate';
        }

        if ($this->cuponDisplayFlg == CouponPlan::DSPTARGET_UNIONMEMBER && ($isRegister || (!$isRegister && (empty($this->displayTargetCoupon) || !empty($this->unionMemberCsv))))) {
            $rtn['unionMemberCsv'] = ['required', 'string', new CsvMemberCodeRule()];
        }

        if ($this->cuponDisplayFlg == CouponPlan::DSPTARGET_UB) {
            $rtn['utilizationBusiness'] = 'required|array';
        }

        if ($this->cuponDisplayFlg == CouponPlan::DSPTARGET_AO) {
            $rtn['affiliationOffice'] = 'required|array';
        }

        if ($this->increaseFlg == CouponPlan::CODE_POINTSAWARDED) {
            $rtn['grantPoint'] = 'required|integer|min:0';
        }

        if ((int) $this->cuponType !== CouponPlan::CODE_STAMP_ACHIEVEMENT) {
            $rtn['pointGrantFlg'] = 'required|integer';

            if ($this->pointGrantFlg == CouponPlan::CODE_AMOUNT) {
                $rtn['pointGrantPurchasesPrice'] = 'required|integer|min:0';
            } elseif ($this->pointGrantFlg == CouponPlan::CODE_PURCHASENUM) {
                $rtn['pointGrantPurchasesCount'] = 'required|integer|min:0';
            }
        }

        if ($this->productFlg == CouponPlan::CODE_PRODUCTDESIGNATION && !isset($this->cp) && ($isRegister || (!$isRegister && (empty($this->couponPlanProducts) || !empty($this->specifiedProdCodeCsv))))) {
            $rtn['specifiedProdCodeCsv'] = ['required', 'string', new CsvSpecifiedProductRule()];
        }

        if ($this->productFlg == CouponPlan::CODE_CATEGORYDESIGNATION && !isset($this->cp) && ($isRegister || (!$isRegister && (empty($this->couponPlanCategories) || !empty($this->prodCategoryCsv))))) {
            $rtn['prodCategoryCsv'] = ['required', 'string', new CsvSpecifiedProdCategoryRule()];
        }

        if ($this->storeFlg == CouponPlan::CODE_SPECIFIED) {
            $rtn['stores'] = 'required|array';
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
        $stores = $this->stores;
        $useFlg = $this->useCount > 0 ? 1 : 0;
        $grantPointSub =  0;

        if (empty($utilizationBusiness)) {
            $utilizationBusiness = [];
        }

        if (empty($affiliationOffice)) {
            $affiliationOffice = [];
        }

        if (empty($stores)) {
            $stores = [];
        }

        if ($this->increaseFlg == CouponPlan::CODE_POINTSAWARDED) {
            $grantPointSub = $this->grantPoint;
        }

        $rtn = [
            'startDateTime' => "$this->startDate $this->startTime",
            'endDateTime' => "$this->endDate $this->endTime",
            'utilizationBusiness' => $utilizationBusiness,
            'affiliationOffice' => $affiliationOffice,
            'stores' => $stores,
            'useFlg' => "$useFlg",
            'grantPointSub' => "$grantPointSub",
            'grantCuponPlanId' => 0
        ];

        if ((int) $this->cuponType === CouponPlan::CODE_STAMP_ACHIEVEMENT) {
            $rtn['cuponDisplayFlg'] = CouponPlan::DSPTARGET_UNCONDITIONAL;
            $rtn['priorityDisplayFlg'] = CouponPlan::CODE_DONTDISPLAY;
            $rtn['autoEntryFlg'] = CouponPlan::CODE_NOTAUTOENTRY;
            $rtn['storeFlg'] = CouponPlan::CODE_NOTSPECIFIED;
        } else if ((int) $this->cuponType === CouponPlan::CODE_TRIALPRODUCT) {
            $rtn['productFlg'] = CouponPlan::CODE_PRODUCTDESIGNATION;
        } else if ((int) $this->cuponType === CouponPlan::CODE_CATEGORYDESIGNATION) {
            $rtn['productFlg'] = CouponPlan::CODE_CATEGORYDESIGNATION;
        }

        if ($this->pointGrantFlg == CouponPlan::CODE_PURCHASENUM) {
            $rtn['pointGrantPurchasesPrice'] = 0;
        }

        $this->merge($rtn);

        request()->merge($this->all());
    }
}
