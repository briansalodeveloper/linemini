<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Message;
use App\Rules\CsvMemberCodeRule;

class MessageRequest extends FormRequest
{
    private $excelData = [];

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
        if (request()->has(config('searchQuery.param.copy')) && !empty($this->messageId)) {
            $rtn = [];
        } else {
            $isRegister = empty($this->messageId);

            $rtn = [
                'messageName' => 'required|string',
                'sendDateTime' => 'required|date',
                'sendTargetFlg' => 'required|integer',
                'contents' => 'required|string',
            ];
    
            if ($this->sendTargetFlg == Message::SENDTARGET_UNIONMEMBER && ($isRegister || (!$isRegister && (empty($this->sendTargetMessage) || !empty($this->unionMemberCsv))))) {
                $rtn['unionMemberCsv'] = ['required', 'string', new CsvMemberCodeRule()];
            }
    
            if ($this->sendTargetFlg == Message::SENDTARGET_UB) {
                $rtn['utilizationBusiness'] = 'required|array';
            }
    
            if ($this->sendTargetFlg == Message::SENDTARGET_AO) {
                $rtn['affiliationOffice'] = 'required|array';
            }
    
            if ($this->sendTargetFlg == Message::SENDTARGET_STORE) {
                $rtn['storeId'] = 'required|array';
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
        $storeId = $this->storeId;

        if (empty($utilizationBusiness)) {
            $utilizationBusiness = [];
        }

        if (empty($affiliationOffice)) {
            $affiliationOffice = [];
        }

        if (empty($storeId)) {
            $storeId = [];
        }

        $this->merge([
            'sendDateTime' => "$this->sendDate $this->sendTime",
            'utilizationBusiness' => $utilizationBusiness,
            'affiliationOffice' => $affiliationOffice,
            'storeId' => $storeId,
        ]);

        request()->merge($this->all());
    }
}
