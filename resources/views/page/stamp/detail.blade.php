@extends('layouts.app')

{{-- @section('bodyClass', 'pg-contents pg-contents-detail pg-notice pg-notice-detail') --}}
@section('bodyClass', 'pg-stamp pg-stamp-detail')

@include('assets.trumbowyg')
@include('assets.lightbox')
@include('assets.datetimepicker')
@include('assets.page.stampJs')
@include('assets.js.fileHandle')
@include('assets.select2')
@include('assets.livewire')

@section('contentHeader')
    @section('contentHeaderTitle')
    <i class="fas fa-stamp"></i>&nbsp{{__('words.StampNewPost') }}
    @endsection
    <div class="edit-menu-container btn-group-vertical">
        @if($data)
            <button class="btn btn-02 mb-2" id="duplicateProject">{{ __('words.DuplicateThisProject') }}</button>
            <button class="btn btn-02 mb-2" id="deleteProject">{{ __('words.DeleteThisProject') }}</button>
            <button class="btn btn-02 mb-2" id="undoBtn">{{ __('words.UndoEditing') }}</button>
        @else
            <button class="btn btn-02" id="clearBtn">{{ __('words.ClearInputItems') }}</button>
        @endif
    </div>
@endsection

@push('modals')
        @if($data)
            @include('modals.stampPlan.duplicateProject')
            @include('modals.stampPlan.deleteProject')
        @endif
        @include('modals.stampPlan.preview')
@endpush
@section('content')

@php
    if(!empty($data)) {
         $targetClassId = isset($data->getAttr('stampPlanTargetClassId')[0]['stampPlanTargetClassId'])? $data->getAttr('stampPlanTargetClassId')[0]['stampPlanTargetClassId']: '';
            $departmentCode = isset($data->getAttr('StampPlanTargetClassList')[0]['departmentCode'])? $data->getAttr('StampPlanTargetClassList')[0]['departmentCode']: '';
        }else{
            $targetClassId = '';
            $departmentCode = '';
    }

     $csvUrlSpecifiedProdCode = old('specifiedProdCodeCsv');
        if (isset($csvUrlSpecifiedProdCode)) {
            if ($errors->has('specifiedProdCodeCsv')) {
                $logo = '<i class="fa fa-file-arrow-down"></i> ';
                $csvLabelSpecifiedProdCode = '<a href="' . $csvUrlSpecifiedProdCode . '" class="text-danger" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrlSpecifiedProdCode) . '</a>';
                $csvLabelSpecifiedProdCode .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
            } else {
                $logo = '<i class="fa fa-file-csv"></i> ';
                $csvLabelSpecifiedProdCode = '<a href="' . $csvUrlSpecifiedProdCode . '" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrlSpecifiedProdCode) . '</a>';
                $csvLabelSpecifiedProdCode .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
            }
        }

    $thumbnailUrl =  old('stampImage',$data?$data->stampImg:'') ?? '';
        if ($thumbnailUrl) {
            $logo = '<i class="fa fa-image"></i> ';
            $thumbnailLabel = '<a href="' . $thumbnailUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($thumbnailUrl) . '</a>';
            $thumbnailLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        }

    $csvUrl = old('unionMemberCode', '');
     if (old('unionMemberCode', null) != null) {
        if ($errors->has('unionMemberCode')) {
            $logo = '<i class="fa fa-file-arrow-down"></i> ';
            $csvLabel = '<a href="' . $csvUrl . '" class="text-danger" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrl) . '</a>';
            $csvLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        } else {
            $logo = '<i class="fa fa-file-csv"></i> ';
            $csvLabel = '<a href="' . $csvUrl . '" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrl) . '</a>';
            $csvLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        }
     }
@endphp

<form method="POST" enctype="multipart/form-data" id="form" action="{{$data?route('stamp.update', $data->id):route('stamp.store')}}">
    @csrf
{{-- Title--}}    
    <div class="form-group">
        <label for="stampName">{{ __('words.Title') }}</label>
        <div class="row">
            <div class="col-md-5">
                <input type="text" class="form-control @if($errors->has('stampName')) is-invalid @endif" name="stampName" id="stampName" 
                    placeholder="{{ __('words.Title') }}" value="{{ old('stampName', $data?$data->stampName:'')}}" origvalue="{{$data?$data->stampName:''}}" 
                >
                @include('common.validationError', ['key' => 'stampName'])
            </div>
        </div>
    </div>

{{-- Select the publication date and time --}}
    <div class="form-group">
        <label>{{ __('words.SelectPublicationDateTime') }}</label>
        <div class="col-12{{ $errors->has('selectPublicationDateTime') ? ' is-invalid' : '' }}">
            <div class="icheck-primary">
                <input type="radio" id="publishType1"  value="0" name="selectPublicationDateTime" {{ old('selectPublicationDateTime') == '0' ? 'checked': ''}}
                    origvalue="null"
                >
                <label for="publishType1">{{ __('words.PublishSoon') }}</label>
            </div>
            <div class="icheck-primary">
                <input type="radio" id="publishType2"  value="1" name="selectPublicationDateTime"  {{ old('selectPublicationDateTime') == '1' ? 'checked': ''}}>
                <label for="publishType2">{{  __('words.BookAndPublish') }}</label>
            </div>
        </div>
        @include('common.validationError', ['key' => 'selectPublicationDateTime'])
    </div>

    <div class="row">
        {{-- Release Date --}}
        <div class="col-12">
            <div class="row">
                <div class="form-group col-2">
                    <label>{{ __('words.ReleaseDate') }}</label>
                    <div class="input-group date" id="startDate" data-target-input="nearest">
                        <input type="text" id="startDateInput" class="form-control datetimepicker-input @if($errors->has('startDateTime')) is-invalid @endif" 
                            name="startDate" placeholder="{{ __('words.DateFormat') }}" value="{{ old( 'startDate',  $data?$data->startDate:'' ) }}" data-target="#startDate"
                            origvalue="{{$data?$data->formatDate('startDate', 'Y/m/d'):''}}"
                        >
                        <div class="input-group-append" data-target="#startDate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @include('common.validationError', ['key' => 'startDateTime'])
                    </div>
                </div>
                <div class="form-group col-2">
                    <label>{{ __('words.PublicationTime') }}</label>
                    <div class="input-group date" id="startTime" data-target-input="nearest">
                        <input type="text" id="startTimeInput" class="form-control datetimepicker-input @if($errors->has('startDateTime')) is-invalid @endif"
                            name="startTime" value="{{ old( 'startTime',  $data?$data->startTime:'' ) }}" data-target="#startTime"
                            origvalue="{{$data?$data->startTime:''}}"
                        >      
                        <div class="input-group-append" data-target="#startTime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Date --}}
        <div class="col-12">
            <div class="row">
                <div class="form-group col-2">
                    <label>{{ __('words.ReleaseEndDate') }}</label>
                    <div class="input-group date" id="endDate" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input @if($errors->has('endDateTime')) is-invalid @endif" 
                            name="endDate" id="endDateInput" placeholder="{{ __('words.DateFormat') }}"  value="{{ old( 'endDate',$data?$data->endDate:'' ) }}" data-target="#endDate"
                            origvalue="{{$data?$data->formatDate('endDate', 'Y/m/d'):''}}"
                        >        
                        <div class="input-group-append" data-target="#endDate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        @include('common.validationError', ['key' => 'endDateTime'])
                    </div>
                </div>
                <div class="form-group col-2">
                    <label>{{ __('words.PublicationEndTime') }}</label>
                    <div class="input-group date" id="endTime" data-target-input="nearest">
                        <input type="text" name="endTime" id="endTimeInput" class="form-control datetimepicker-input @if($errors->has('endDateTime')) is-invalid  @endif"
                            value="{{ old( 'endTime', $data?$data->endTime:'' ) }}" data-target="#endTime" origvalue="{{$data?$data->endTime:''}}"
                        >
                        <div class="input-group-append" data-target="#endTime" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- Stamp Type --}}
    <div class="form-group">
        <label>{{ __('words.stampType') }}</label>
        <div class="icheck-primary">        
            <input type="radio" name="stampType" id="AlwaysStamp" 
                value="{{\Globals::mStampPlan()::TYPE_ALWAYS}}"  {{ (old('stampType', $data?$data->stampType:'-1') == \Globals::mStampPlan()::TYPE_ALWAYS )? 'checked': '' }}  origvalue="{{$data?$data->stampType:''}}"
            >
            <label for="AlwaysStamp">{{ __('words.AlwaysStamp') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" name="stampType" id="CampaignStamp" 
                value="{{\Globals::mStampPlan()::TYPE_CAMPAIGN}}" {{ (old('stampType', $data?$data->stampType:'') == \Globals::mStampPlan()::TYPE_CAMPAIGN)? 'checked': '' }}>
            <label for="CampaignStamp">{{ __('words.CampaignStamp') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" name="stampType" id="ProductUsageStamp" 
                value="{{\Globals::mStampPlan()::TYPE_PRODUCTUSAGE}}" {{ (old('stampType', $data?$data->stampType:'') == \Globals::mStampPlan()::TYPE_PRODUCTUSAGE)? 'checked': '' }}>
            <label for="ProductUsageStamp">{{ __('words.ProductUsageStamp') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" name="stampType" id="SelfRequisitionStamp" 
                value="{{\Globals::mStampPlan()::TYPE_SELFREQUISITION}}" {{ (old('stampType', $data?$data->stampType:'') == \Globals::mStampPlan()::TYPE_SELFREQUISITION)? 'checked': '' }}>
            <label for="SelfRequisitionStamp">{{ __('words.SelfRequisitionStamp') }}</label>
        </div>
        @if($errors->has('stampType'))
            <span class="text-danger">{{ $errors->first('stampType') }}</span>
        @endif
    </div>

{{-- Union member to display --}}
    <div class="form-group">
        <label>{{ __('words.UnionMemberToDisplay') }}</label>
        <div class="icheck-primary">
            <input type="radio" name="stampDisplayFlg" id="stampDisplayFlg1" 
                value="{{\Globals::mStampPlan()::DSPTARGET_UNCONDITIONAL}}"  {!! (old('stampDisplayFlg', $data?$data->stampDisplayFlg:'-1') == \Globals::mStampPlan()::DSPTARGET_UNCONDITIONAL)? 'checked': '' !!}  origvalue="{{$data?$data->stampDisplayFlg:''}}">
            <label for="stampDisplayFlg1">{{ __('words.Unconditional') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" name="stampDisplayFlg" id="stampDisplayFlg2" 
                value="{{\Globals::mStampPlan()::DSPTARGET_UNIONMEMBER}}" {{ (old('stampDisplayFlg', $data?$data->stampDisplayFlg:'') == \Globals::mStampPlan()::DSPTARGET_UNIONMEMBER)? 'checked': '' }}>
            <label for="stampDisplayFlg2">{{ __('words.UnionMemberDesignation') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" name="stampDisplayFlg" id="stampDisplayFlg3" 
                value="{{\Globals::mStampPlan()::DSPTARGET_UB}}" {{ (old('stampDisplayFlg', $data?$data->stampDisplayFlg:'') == \Globals::mStampPlan()::DSPTARGET_UB)? 'checked': '' }}>
            <label for="stampDisplayFlg3">{{ __('words.UserBusinessDesignation') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" name="stampDisplayFlg" id="stampDisplayFlg4" 
                value="{{\Globals::mStampPlan()::DSPTARGET_AO}}" {{ (old('stampDisplayFlg', $data?$data->stampDisplayFlg:'') == \Globals::mStampPlan()::DSPTARGET_AO)? 'checked': '' }}>
            <label for="stampDisplayFlg4">{{ __('words.OfficeDesignation') }}</label>
        </div>
        @if($errors->has('stampDisplayFlg'))
            <span class="text-danger">{{ $errors->first('stampDisplayFlg') }}</span>
        @endif
    </div>

    <div class="col-md-12 pl-5">
        {{-- when the union membercode selected --}}
        <div class="form-group d-none" id="unionMemberCsvDiv">
            <p><b>{{ __('words.CSVFileUploadInstruction') }}</b></p>
            <div class="col-md-12 pl-3 custom-input-file unionMemberCodeTrigger" style="position: relative;">
                <input type="hidden" name="displayTargetStamp" value="{{ $data?implode(',', $data->displayTargetStampIdList):''}}" >
                <input type="hidden" name="unionMemberCode" id="unionMemberCode" value="{{ old('unionMemberCode') }}">
                <button>{{ __('words.SelectFiles') }}</button>

                <span class="label">
                    {!!isset($csvLabel)?$csvLabel:__('words.NotSelected')!!}
                </span>
                <input type="file" name="unionMemberCodeTrigger"  id="unionMemberCodeTrigger" accept=".csv"
                    style="position: absolute;
                    left:10px;
                    top:0;
                    width:135px;
                    opacity:0;
                ">
            </div>
            <span id="unionMemberCodeTrigger-error" class="error invalid-feedback"></span>
        <div>

            @if($errors->has('unionMemberCode'))
                <span class="text-danger ml-2">{{ $errors->first('unionMemberCode') }}</span>
            @endif 
            </div>

            @if ($data)
                @livewire('lw-union-member-code-table', ['params' => [
                    'dataId' => $data->getAttr('id'),
                    'interface' => \Globals::iStamp(),
                    'dTMethodName' => 'displayTargetStamp'
                ]])
            @endif
        </div>
        {{-- when the utlizationBusiness selected --}}
        <div class="form-group ub-ao-box d-none" id="utilizationBusinessDiv">
            <label>{{ __('messages.custom.specifyAoOrUb') }}</label>
            <div class="row">
                <div class="col-md-4">  
                    <select class="ub-list form-control"  type="select" name="utilizationBusiness[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.BusinessSelection') }}" 
                        origvalue="{{ $data?implode(',', $data->getAttr('displayTargetStampUbUtilizationBusinessIdList')):'' }}"
                    >
                        @foreach($ubList as $key => $val ) 
                            <option value="{{ $key }}" {{ in_array($key, old("utilizationBusiness",$data?$data->getAttr('displayTargetStampUbUtilizationBusinessIdList'):[])) ? "selected":""    }}>
                                {{ \Globals::__($val) }}
                            </option>
                            
                        @endforeach
                    </select>
                    @if($errors->has('utilizationBusiness'))
                        <span class="text-danger">{{ $errors->first('utilizationBusiness') }}</span>
                    @endif
                </div>
            </div>
        </div>
    {{-- when the affiliationOffice selected --}}
        <div class="form-group ub-ao-box d-none" id="affiliationOfficeDiv">   
            <label>{{ __('messages.custom.specifyAoOrUb') }}</label>
            <div class="row">
                <div class="col-md-4">  
                    <select class="ao-list form-control" type="select" name="affiliationOffice[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.AffiliateOffice') }}"
                        origvalue="{{ $data?implode(',', $data->getAttr('displayTargetStampAoAffiliationOfficeIdList')):'' }}"
                    >
                        @foreach( $aoList as $key => $val )    
                            <option value="{{ $key }}" {{ in_array($key, old("affiliationOffice",$data?$data->getAttr('displayTargetStampAoAffiliationOfficeIdList', []):[])) ? "selected":"" }} >
                                {{ \Globals::__($val) }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('affiliationOffice'))
                        <span class="text-danger">{{ $errors->first('affiliationOffice') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

{{-- Number of times available --}}
    <div class="form-group">
        <label for="useCount">{{ __('words.NumberOfTimesAvailable') }}</label>
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control @if($errors->has('useCount')) is-invalid @endif" 
                    name="useCount" id="useCount" value="{{ old('useCount', $data?$data->useCount:'')}}" origvalue="{{$data?$data->useCount:''}}" 
                >
                @include('common.validationError', ['key' => 'useCount'])
            </div>
        </div>
    </div>

{{-- Stamping Condition --}}
    <div class="form-group">
        <label>{{ __('words.StampingConditions') }}</label>&nbsp {{ __('words.SpecifyCondtition') }}
        <div class="icheck-primary">
            <input type="radio" id="Amount" value="{{\Globals::mStampPlan()::GRANTFLG_AMOUNTMONEY}}"
                name="stampGrantFlg" {{ (old('stampGrantFlg', $data?$data->stampGrantFlg:'') == \Globals::mStampPlan()::GRANTFLG_AMOUNTMONEY)? 'checked': '' }} origvalue="{{$data?$data->stampGrantFlg:''}}">
            <label for="Amount">{{ __('words.Amount') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="NumberOfPurchases" value="{{\Globals::mStampPlan()::GRANTFLG_NUMBERPURCHASE}}"
                name="stampGrantFlg" {{ (old('stampGrantFlg',  $data?$data->stampGrantFlg:'') == \Globals::mStampPlan()::GRANTFLG_NUMBERPURCHASE)? 'checked': '' }}>
            <label for="NumberOfPurchases">{{ __('words.NumberOfPurchases') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="OpenTheApp" value="{{\Globals::mStampPlan()::GRANTFLG_OPENTHEAPP}}"
                name="stampGrantFlg" {{ (old('stampGrantFlg',  $data?$data->stampGrantFlg:'') == \Globals::mStampPlan()::GRANTFLG_OPENTHEAPP)? 'checked': '' }}>
            <label for="OpenTheApp">{{ __('words.OpenTheApp') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="QRCodeReading" value="{{\Globals::mStampPlan()::GRANTFLG_QRCODEREADING}}"
                name="stampGrantFlg" {{ (old('stampGrantFlg',  $data?$data->stampGrantFlg:'') == \Globals::mStampPlan()::GRANTFLG_QRCODEREADING)? 'checked': '' }}>
            <label for="QRCodeReading">{{ __('words.QRCodeReading') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="1StampRegardlessOfTheAmount" value="{{\Globals::mStampPlan()::GRANTFLG_AMOUNTFIXED}}"
                name="stampGrantFlg" {{ (old('stampGrantFlg',  $data?$data->stampGrantFlg:'') == \Globals::mStampPlan()::GRANTFLG_AMOUNTFIXED)? 'checked': '' }}>
            <label for="1StampRegardlessOfTheAmount">{{ __('words.1StampRegardlessOfTheAmount') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="1StampRegardlessOfTheNumberOfPurchases" value="{{\Globals::mStampPlan()::GRANTFLG_FIXEDNUMBER}}"
                name="stampGrantFlg" {{ (old('stampGrantFlg',  $data?$data->stampGrantFlg:'') == \Globals::mStampPlan()::GRANTFLG_FIXEDNUMBER)? 'checked': '' }}>
            <label for="1StampRegardlessOfTheNumberOfPurchases">{{ __('words.1StampRegardlessOfTheNumberOfPurchases') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="OpenTheAppAutoEntryWhenOpened" value="{{\Globals::mStampPlan()::GRANTFLG_OPENTHEAPPAUTOENTRY}}"
                name="stampGrantFlg" {{ (old('stampGrantFlg',  $data?$data->stampGrantFlg:'') == \Globals::mStampPlan()::GRANTFLG_OPENTHEAPPAUTOENTRY)? 'checked': '' }}>
            <label for="OpenTheAppAutoEntryWhenOpened">{{ __('words.OpenTheAppAutoEntryWhenOpened') }}</label>
        </div>
        @if($errors->has('stampGrantFlg'))
            <span class="text-danger">{{ $errors->first('stampGrantFlg') }}</span>
        @endif 
    </div>

    <div class="col-md-12 pl-5">
        {{-- when the amount is selected --}}
        <div class="form-group d-none" id="SpecifiedAmountDiv">
            <label for="SpecifiedAmount">{{ __('words.SpecifiedAmount') }}</label>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control @if($errors->has('SpecifiedAmount')) is-invalid @endif" 
                        name="SpecifiedAmount" id="SpecifiedAmount" value="{{ old('SpecifiedAmount', $data?$data->stampGrantPurchasesPrice:'')}}"
                        origvalue="{{$data?$data->stampGrantPurchasesPrice:''}}"
                    >
                    @if($errors->has('SpecifiedAmount'))
                        <span class="text-danger">{{ $errors->first('SpecifiedAmount') }}</span>
                    @endif 
                </div>
            </div>
        </div>
        {{-- when the number of purchase is selected --}}
        <div class="form-group d-none" id="SpecifiedNumberOfPurchaseDiv">
            <label for="SpecifiedNumberOfPurchase">{{ __('words.SpecifiedNumberOfPurchase') }}</label>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control @if($errors->has('SpecifiedNumberOfPurchase')) is-invalid @endif" 
                        name="SpecifiedNumberOfPurchase" id="SpecifiedNumberOfPurchase" value="{{ old('SpecifiedNumberOfPurchase', $data?$data->stampGrantPurchasesCount:'')}}"
                        origvalue="{{$data?$data->stampGrantPurchasesCount:''}}"
                    >
                    @if($errors->has('SpecifiedNumberOfPurchase'))
                        <span class="text-danger">{{ $errors->first('SpecifiedNumberOfPurchase') }}</span>
                    @endif 
                </div>
            </div>
        </div>
    </div>

{{-- Specify the number of stamps required --}}
    <div class="form-group">
        <label for="stampAchievement">{{ __('words.NumberOfStampsRequired') }}</label>
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control @if($errors->has('stampAchievement')) is-invalid @endif" 
                    name="stampAchievement" id="stampAchievement" value="{{ old('stampAchievement', $data?$data->stampAchievement:'')}}" origvalue="{{$data?$data->stampAchievement:''}}"
                >
                @include('common.validationError', ['key' => 'stampAchievement'])
            </div>
        </div>
    </div>

{{-- Type of benefits --}}
    <div class="form-group">
        <label>{{ __('words.TypeOfBenefits') }}</label>
        <div class="icheck-primary">
            <input type="radio" id="Point" value="{{\Globals::mStampPlan()::INCREASEFLG_POINTSAWARDED}}"
                name="increaseFlg" {{ (old('increaseFlg', $data?$data->increaseFlg:'') == \Globals::mStampPlan()::INCREASEFLG_POINTSAWARDED)? 'checked': '' }} origvalue="{{$data?$data->increaseFlg:''}}">
            <label for="Point">{{ __('words.Point') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="Coupon" value="{{\Globals::mStampPlan()::INCREASEFLG_COUPONGRANT}}"
                name="increaseFlg" {{ (old('increaseFlg', $data?$data->increaseFlg:'') == \Globals::mStampPlan()::INCREASEFLG_COUPONGRANT)? 'checked': '' }}>
            <label for="Coupon">{{ __('words.Coupon') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="ApplyAtTheStore" value="{{\Globals::mStampPlan()::INCREASEFLG_APPLYSTORE}}"
                name="increaseFlg" {{ (old('increaseFlg', $data?$data->increaseFlg:'') == \Globals::mStampPlan()::INCREASEFLG_APPLYSTORE)? 'checked': '' }}>
            <label for="ApplyAtTheStore">{{ __('words.ApplyAtTheStore') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="ProductRedemption" value="{{\Globals::mStampPlan()::INCREASEFLG_REDEEMGOODS}}"
                name="increaseFlg" {{ (old('increaseFlg', $data?$data->increaseFlg:'') == \Globals::mStampPlan()::INCREASEFLG_REDEEMGOODS)? 'checked': '' }}>
            <label for="ProductRedemption">{{ __('words.ProductRedemption') }}</label>
        </div>
        @if($errors->has('increaseFlg'))
            <span class="text-danger">{{ $errors->first('increaseFlg') }}</span>
        @endif 
    </div>

    <div class="col-md-12 pl-5">
        {{--when the point is selected--}}
        <div class="form-group d-none" id="SpecifiedNumberOfPointsDiv">
            <label for="SpecifiedNumberOfPoints">{{ __('words.SpecifiedNumberOfPoints') }}</label>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control @if($errors->has('SpecifiedNumberOfPoints')) is-invalid @endif" 
                        name="SpecifiedNumberOfPoints" id="SpecifiedNumberOfPoints" value="{{ old('SpecifiedNumberOfPoints', $data?$data->increasePoint:'')}}"
                        origvalue="{{$data?$data->increasePoint:''}}"
                    >
                    @if($errors->has('SpecifiedNumberOfPoints'))
                        <span class="text-danger">{{ $errors->first('SpecifiedNumberOfPoints') }}</span>
                    @endif 
                </div>
            </div>
        </div>
        {{--when the coupon is selected--}}
        <div class="form-group d-none" id="SpecifiedCouponIdDiv">
            <label for="SpecifiedCouponId">{{ __('words.SpecifiedCouponId') }}</label>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control @if($errors->has('SpecifiedCouponId')) is-invalid @endif" 
                        name="SpecifiedCouponId" id="SpecifiedCouponId" value="{{ old('SpecifiedCouponId', $data?$data->increaseCupon:'')}}"
                        origvalue="{{$data?$data->increaseCupon:''}}"
                    >
                    @if($errors->has('SpecifiedCouponId'))
                        <span class="text-danger">{{ $errors->first('SpecifiedCouponId') }}</span>
                    @endif    
                </div>
            </div>
        </div>
        {{--when the product redemption is selected--}}
        {{-- <div class="form-group d-none" id="ProductRedumptionDiv">
            <p><b>{{ __('words.UploadCsvFileJanCode') }}</b></p>            
            <div class="col-md-12 pl-3" style="position: relative;">
                <input type="hidden" name="linknameProductRedumption" id="linknameProductRedumption" value="{{ old('linknameProductRedumption') }}">
                <input type="hidden" name="filenameProductRedumption" id="filenameProductRedumption" value="{{ old('filenameProductRedumption') }}">
                
                <button>{{ __('words.SelectFiles') }}</button>
                <span class="label ml-2">
                    {{ (old('filenameProductRedumption') == '')? __('words.NotSelected'): old('filenameProductRedumption') }}
                </span>
                <input type="file" name="csvUploadProductRedumption" id="csvUploadProductRedumption" accept=".csv"
                    style="position: absolute;
                    left:10px;
                    top:0;
                    width:135px;
                    opacity:0;
                "> 
            </div>
            @if($errors->has('csvUploadProductRedumption'))
                <span class="text-danger ml-2">{{ $errors->first('csvUploadProductRedumption') }}</span>
            @endif  
        </div> --}}
    </div>

{{-- store   --}}
    <div class="form-group">
        <label>{{ __('words.SpecifyingStore') }}</label> &nbsp{{ __('words.CheckToSpecifyStore') }} <br>
        @foreach($storeList as $key => $val)
            <input type="checkbox" id="store1" name="store[]" value="{{$key}}"  
                {{ ( in_array($key, old('store', $data?$data->getAttr('stampPlanStoreIdList'):[])))? 'checked': '' }}
                origvalue="{{ $data?implode(',', $data->getAttr('stampPlanStoreIdList')):'' }}"
            >
            <label for="store1">{{ __($val) }}&nbsp</label>
            @if($key == 3)
                <br>
            @endif    
        @endforeach
        @if($errors->has('store'))
            <span class="text-danger ml-2"><br>{{ $errors->first('store') }}</span>
        @endif      
    </div>

{{-- Target for granting benefits --}}
    <div class="form-group">
        <label>{{ __('words.ProductForGrantingBenefits') }}</label>&nbsp {{ __('words.SpecifyTheConditionForGrantingBenefits') }}
        <div class="icheck-primary">
            <input type="radio" id="Unspecified" value="{{\Globals::mStampPlan()::PRODUCTFLG_NODESIGNATION}}"
                name="productFlg"  {{ (old('productFlg', $data?$data->productFlg:'') == \Globals::mStampPlan()::PRODUCTFLG_NODESIGNATION)? 'checked': '' }} origvalue="{{$data?$data->productFlg:''}}">
            <label for="Unspecified">{{ __('words.Unspecified') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="ProductDesignation" value="{{\Globals::mStampPlan()::PRODUCTFLG_PRODUCTDESIGNATION}}"
                name="productFlg" {{ (old('productFlg', $data?$data->productFlg:'') == \Globals::mStampPlan()::PRODUCTFLG_PRODUCTDESIGNATION)? 'checked': '' }}>
            <label for="ProductDesignation">{{ __('words.ProductDesignation') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="ClassificationDesignation" value="{{\Globals::mStampPlan()::PRODUCTFLG_CLASSIFICATIONDESIGNATION}}"
                name="productFlg" {{ (old('productFlg', $data?$data->productFlg:'') == \Globals::mStampPlan()::PRODUCTFLG_CLASSIFICATIONDESIGNATION)? 'checked': '' }}>
            <label for="ClassificationDesignation">{{ __('words.ClassificationDesignation') }}</label>
        </div>
        @if($errors->has('productFlg'))
            <span class="text-danger ml-2">{{ $errors->first('productFlg') }}</span>
        @endif  
    </div>

    <div class="col-md-12 pl-5">
        {{--when the product designation is selected--}}
        <div class="form-group d-none" id="ProductDesignationDiv">
            <p><b>{{ __('words.UploadCsvFileJanCode') }}</b></p>            
            <div class="col-md-12 pl-3 custom-input-file specifiedProdCodeCsvTrigger" style="position: relative;">
                <input type="hidden" name="specifiedProdCodeCsv" id="specifiedProdCodeCsv" value="{{ old('specifiedProdCodeCsv') }}">
                <input type="file" name="specifiedProdCodeCsvTrigger" id="specifiedProdCodeCsvTrigger" accept=".csv"
                    style="position: absolute;
                    left:10px;
                    top:0;
                    width:135px;
                    opacity:0;
                "> 
                <button>{{ __('words.SelectFiles') }}</button>
                <span class="label">
                    {!!isset($csvLabelSpecifiedProdCode)?$csvLabelSpecifiedProdCode:__('words.NotSelected')!!}
                </span>
            </div>
            <span id="specifiedProdCodeCsvTrigger-error" class="error invalid-feedback"></span>
            @if($errors->has('specifiedProdCodeCsv'))
                <span id="" class="text-danger ml-2">{{ $errors->first('specifiedProdCodeCsv') }}</span>
            @endif  
        </div>

        {{-- when the department designation is selected --}}
        <div class="form-group d-none" id="DepartmentDiv">
            <label for="DepartmentCode">{{ __('words.DepartmentCode') }}</label>
            <div class="row">
                <div class="col-md-3">
                    <input type="hidden" name="stampPlanTargetClassId" value="{{$targetClassId}}">
                    <input type="text" class="form-control @if($errors->has('openingLetter')) is-invalid @endif" 
                        name="departmentCode" id="departmentCode" value="{{ old('departmentCode', $departmentCode)}}"
                        origvalue="{{$departmentCode}}"
                    >
                    @if($errors->has('departmentCode'))
                        <span class="text-danger">{{ $errors->first('departmentCode') }}</span>
                    @endif 
                </div>
            </div>
        </div>
    </div>



{{-- Image of stamp --}}
    <div class="form-group" id="">
        <p><b>{{ __('words.ImageOfStampBeforeEntry') }}</b><span class="text-xs ml-4">ファイルサイズは2M以下、形式はJPEG or PNG のみとなります。</span></p>
        <div class="col-md-12 pl-3 custom-input-file stampImageTrigger" style="position: relative;">

            <input type="hidden" name="stampImage" id="stampImage" value="{{ old('stampImage',$data?$data->stampImg:'') }}" 
                origname="{{$data?$data->getAttr('stampImageName'):''}}" origvalue="{{$data?$data->stampImg:''}}"
            >
            <button type="button">{{ __('words.SelectFiles') }}</button>
            <span class="label ml-2">
                {!!isset($thumbnailLabel)?$thumbnailLabel:__('words.NotSelected')!!}
            </span>
            <input type="file" name="stampImageTrigger" id="stampImageTrigger"  accept="file_extension .jpg, .png"
                style="position: absolute;
                left:10px;
                top:0;
                width:135px;
                opacity:0;
            "> 
        </div>
        @if($errors->has('stampImage'))
            <span class="text-danger ml-2">{{ $errors->first('stampImage') }}</span>
        @endif 
    </div>

{{-- stamp content --}}
    <div class="form-group">
        <p><b>{{ __('words.StampContents') }}</b></p>
        <textarea id="contentsOriginalValue" hidden></textarea>
        <textarea id="stampText" name="stampText" origvalue="{{$data?$data->stampText:''}}">{!! old('stampText', $data?$data->stampText:'') !!}</textarea>
        @if($errors->has('stampText'))
            <span class="text-danger ml-2">{{ $errors->first('stampText') }}</span>
        @endif 
    </div>

{{--Preview--}}
    <div class="row justify-content-center mb-3">
        <button type="submit" id="preview" class="btn btn-02 col-md-3 col-sm-3 w-100">{{ __('words.Preview') }}</button>
    </div>

{{--Submit--}}
    <div class="row justify-content-center mb-3">
        <button id="submit" type="submit" class="btn btn-warning col-md-3 col-sm-3 w-100 text-white">{{ __('words.Post') }}</button>
    </div>

</form>
@endsection