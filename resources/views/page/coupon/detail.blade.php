@extends('layouts.app')

@section('bodyClass', 'pg-coupon pg-coupon-detail')

@include('assets.livewire')
@include('assets.select2')
@include('assets.trumbowyg')
@include('assets.lightbox')
@include('assets.datetimepicker')
@include('assets.js.fileHandle')
@include('assets.page.couponPlanJs')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="fas fa-ticket-simple"></i>
        {{ $data->isEmpty ? __('words.NewCouponRegistration') : __('words.CouponEditing') }}
    @endsection
    @include('common.menu.detailMenu', [
        'page' => 'coupon'
    ])
@endsection

@push('modals')
    @include('modals.coupon.preview')
@endpush

@php
    $route = $data->isEmpty ? route('coupon.store') : route('coupon.update', $data->id);
    $radioBtnGroup = [
        // TODO: investigate the column the value for this one belongs
        [
            'type' => 'radio',
            'field' => 'priorityDisplayFlg',
            'label' => __('words.TopDisplayOnCouponListScreen'),
            'subLabel' => __('messages.custom.inTheCouponListCheckIfWantToDisplayHigher'),
            'list' => $listHighLevelDisplayOptions,
            'valSelected' => old('priorityDisplayFlg', $data->getAttr('priorityDisplayFlg')),
            'valDefault' => $data->getAttr('priorityDisplayFlg'),
            'required' => true
        ],
        // TODO: still asking for more details
        // [
        //     'type' => 'radio',
        //     'field' => 'todo',
        //     'label' => __('words.CouponListRegLvlInputLbl'),
        //     'list' => $listRegLevelDisplayOptions,
        //     'valSelected' => old('todo', $data->getAttr('todo')),
        //     'valDefault' => $data->getAttr('todo'),
        //     'customClass' => [
        //         'formGroup' => 'on-hold'
        //     ]
        // ],
        [
            'type' => 'radio',
            'field' => 'autoEntryFlg',
            'label' => __('words.AutoEntryWhenLoggedIn'),
            'subLabel' => __('messages.custom.autoEntryWhenLoggedIn'),
            'list' => $listAutoEntryOptions,
            'valSelected' => old('autoEntryFlg', $data->getAttr('autoEntryFlg')),
            'valDefault' => $data->getAttr('autoEntryFlg'),
            'required' => true
        ],
        [
            'type' => 'radio',
            'field' => 'pointGrantFlg',
            'label' => __('words.ConditionForGrantingBenefits'),
            'subLabel' => __('messages.custom.pleaseSpecifyForGrantingBenfits'),
            'list' => $listPoinGrantFlgOptions,
            'valSelected' => old('pointGrantFlg', $data->getAttr('pointGrantFlg')),
            'valDefault' => $data->getAttr('pointGrantFlg'),
            'required' => true
        ]
    ];

    $csvUrlUnionMember = old('unionMemberCsv');
    $csvUrlSpecifiedProdCode = old('specifiedProdCodeCsv');
    $csvUrlSpecifiedCategory = old('prodCategoryCsv');
    $csvLabelUnionMember = '';
    $csvLabelSpecifiedProdCode = '';
    $csvLabelSpecifiedCategory = '';
    $logo = '';
    $thumbnailLabel = '';
    $thumbnailUrl = old('cuponImg', $data->getAttr('cuponImg')) ?? '';

    if (isset($csvUrlUnionMember)) {
        if ($errors->has('unionMemberCsv')) {
            $logo = '<i class="fa fa-file-arrow-down"></i> ';
            $csvLabelUnionMember = '<a href="' . $csvUrlUnionMember . '" class="text-danger" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrlUnionMember) . '</a>';
            $csvLabelUnionMember .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        } else {
            $logo = '<i class="fa fa-file-csv"></i> ';
            $csvLabelUnionMember = '<a href="' . $csvUrlUnionMember . '" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrlUnionMember) . '</a>';
            $csvLabelUnionMember .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        }
    }

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

    if (isset($csvUrlSpecifiedCategory)) {
        if ($errors->has('prodCategoryCsv')) {
            $logo = '<i class="fa fa-file-arrow-down"></i> ';
            $csvLabelSpecifiedCategory = '<a href="' . $csvUrlSpecifiedCategory . '" class="text-danger" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrlSpecifiedCategory) . '</a>';
            $csvLabelSpecifiedCategory .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        } else {
            $logo = '<i class="fa fa-file-csv"></i> ';
            $csvLabelSpecifiedCategory = '<a href="' . $csvUrlSpecifiedCategory . '" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrlSpecifiedCategory) . '</a>';
            $csvLabelSpecifiedCategory .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        }
    }

    if (old('cuponImg', null) == null) {
        if ($data->isNotEmpty && !empty($thumbnailUrl)) {
            if ($data->IsThumbnailExist) {
                $logo = '<i class="fa fa-image"></i> ';
                $thumbnailLabel = '<a href="' . $thumbnailUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($thumbnailUrl) . '</a>';
                $thumbnailLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
            } else {
                $logo = '<i class="fa fa-circle-xmark text-red" title="' . __('messages.custom.imageNotExist') . '"></i> ';
                $thumbnailLabel = $logo . \Globals::hUpload()::getBaseName($thumbnailUrl);
            }
        }
    } else {
        $logo = '<i class="fa fa-image"></i> ';
        $thumbnailLabel = '<a href="' . $thumbnailUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($thumbnailUrl) . '</a>';
    }

    $startDate = null;
    $startTime = null;
    $endDate = null;
    $endTime = null;

    if ($data->isNotEmpty) {
        $startDate = $data->formatDate('startDate', 'Y/m/d');
        $startTime = $data->formatDate('startTime', 'H:i');
    }

    if ($data->isNotEmpty) {
        $endDate = $data->formatDate('endDate', 'Y/m/d');
        $endTime = $data->formatDate('endTime', 'H:i');
    }
@endphp

@section('content')
    <form method="POST" enctype="multipart/form-data" id="form" action="{{ $route }}">
        @csrf
        @if($data->isNotEmpty)
            <input type="text" hidden name="couponPlanId" value="{{ $data->id }}">

            @if(count($data->displayTargetCouponIdList) != 0)
                <input type="text" hidden name="displayTargetCoupon" value="{{ implode(',', $data->displayTargetCouponIdList) }}">
            @endif

            @if(count($data->couponPlanProductList) != 0)
                <input type="text" hidden name="couponPlanProducts" value="1">
            @endif

            @if(count($data->couponPlanTargetClassList) != 0)
                <input type="text" hidden name="couponPlanCategories" value="1">
            @endif
        @endif
        <input type="hidden" id="fileType" name="fileType">
        <div class="form-group mt-3">
            <div class="row">
                @if($data->isNotEmpty)
                    <div class="col-12">
                        <label>{{ __('words.Id') }}: {{ $data->getAttr('id') }}</label>
                    </div>
                @endif
                <div class="col-12">
                    <label class="mb-0">{{ __('words.Status') }}: {{ $data->getAttr('statusStr', __('words.New')) }}</label>
                    {{-- TODO: check what is the used of this --}}
                    {{-- @if($data->isNotEmpty)
                        <button class="btn btn-02 ml-2 def-size">{{ __('words.Edit') }}</button>
                    @endif --}}
                </div>
            </div>
        </div>
        @include('common.input.inputGroup', [
            'type' => 'text',
            'field' => 'cuponName',
            'label' => __('words.Title'),
            'placeholder' => __('words.Title'),
            'inputVal' => old('cuponName', $data->getAttr('cuponName')),
            'valDefault' => $data->getAttr('cuponName'),
            'inputContainer' => ['<div class="row"><div class="col-md-6">' , '</div></div>'],
            'required' => true
        ])
        @include('common.input.inputGroup', [
            'type' => 'radio',
            'field' => 'selectPublicationDateTime',
            'label' => __('words.SelectPublicationDateTime'),
            'list' => $listPublicationOptions,
            'valSelected' => old('selectPublicationDateTime', $data->getAttr('selectPublicationDateTime')),
            'valDefault' => $data->getAttr('selectPublicationDateTime'),
            'required' => true,
            'inputContainer' => ['<div class="col-md-6">' , '</div>'],
        ])
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <span class="col-3">
                        @include('common.input.inputGroup', [
                            'type' => 'datetimepicker',
                            'field' => 'startDateTime',
                            'name' => 'startDate',
                            'label' => __('words.ReleaseDate'),
                            'placeholder' => __('words.DateFormat'),
                            'inputVal' => old('startDate', $startDate),
                            'valDefault' => $startDate,
                            'icon' => 'fa fa-calendar',
                            'dspErrorMsg' => true,
                            'required' => true
                        ])
                    </span>
                    <span class="col-3">
                        @include('common.input.inputGroup', [
                            'type' => 'datetimepicker',
                            'field' => 'startDateTime',
                            'name' => 'startTime',
                            'label' => __('words.PublicationTime'),
                            'inputVal' => old('startTime', $startTime),
                            'valDefault' => $startTime,
                            'icon' => 'fa fa-clock',
                            'required' => true
                        ])
                    </span>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    <span class="col-3">
                        @include('common.input.inputGroup', [
                            'type' => 'datetimepicker',
                            'field' => 'endDateTime',
                            'name' => 'endDate',
                            'label' => __('words.ReleaseEndDate'),
                            'placeholder' => __('words.DateFormat'),
                            'inputVal' => old('endDate', $endDate),
                            'valDefault' => $endDate,
                            'icon' => 'fa fa-calendar',
                            'dspErrorMsg' => true,
                            'required' => true
                        ])
                    </span>
                    <span class="col-3">
                        @include('common.input.inputGroup', [
                            'type' => 'datetimepicker',
                            'field' => 'endDateTime',
                            'name' => 'endTime',
                            'label' => __('words.PublicationEndTime'),
                            'inputVal' => old('endTime', $endTime),
                            'valDefault' => $endTime,
                            'icon' => 'fa fa-clock',
                            'required' => true
                        ])
                    </span>
                </div>
            </div>
        </div>
        @include('common.input.inputGroup', [
            'type' => 'radio',
            'field' => 'cuponType',
            'label' => __('words.CouponType'),
            'list' => $listCouponType,
            'valSelected' => old('cuponType', $data->getAttr('cuponType')),
            'valDefault' => $data->getAttr('cuponType'),
            'required' => true
        ])
        @include('common.input.inputGroup', [
            'type' => 'radio',
            'field' => 'cuponDisplayFlg',
            'label' => __('words.UnionMemberToDisplay'),
            'list' => $listDisplayTargetOptions,
            'valSelected' => old('cuponDisplayFlg', $data->getAttr('cuponDisplayFlg')),
            'valDefault' => $data->getAttr('cuponDisplayFlg'),
            'required' => true
        ])
        <div class="col-md-12 pl-3 target-user-value-box">
            @include('common.input.inputGroup', [
                'type' => 'fileCustom',
                'label' =>  __('words.CSVFileUploadInstruction'),
                'id' => 'unionMemberCsvTrigger',
                'name' => 'unionMemberCsvTrigger',
                'classContainer' => 'unionMemberCsvTrigger' . ($errors->has('unionMemberCsv') ? ' is-invalid' : ''),
                'extAllowed' => \Globals::implode($listCsvAcceptedExtension, ',', '.'),
                'customlabel' => $csvLabelUnionMember,
                'field' => 'unionMemberCsv',
                'hiddenValue' => $csvUrlUnionMember,
                'disabled' => false,
                'originVal' => '',
                'customClass' => [
                    'formGroup' => 'target-csv',
                ],
                'required' => true
            ])
            <div class="form-group ub-ao-box target-select-option">
                <label>{{ __('messages.custom.specifyAoOrUb') }} <req></req></label>
                <div class="col-md-6{{ $errors->has('utilizationBusiness') || $errors->has('affiliationOffice') ? ' is-invalid' : '' }}">
                    @include('common.input.inputGroup', [
                        'type' => 'selection',
                        'field' => 'utilizationBusiness[]',
                        'label' => __('messages.custom.specifyAoOrUbCode'),
                        'defaultItem' => __('words.BusinessSelection'),
                        'list' => $listUb,
                        'valSelected' => old('utilizationBusiness', $data->getAttr('displayTargetCouponUbUtilizationBusinessIdList', [])),
                        'customClass' =>  [ 'input' => 'ub-list form-control' ],
                        'customAttr' => 'multiple="multiple" auto-init-select2 data-placeholder="' . __('words.BusinessSelection') . '" data-original-value="' . implode(',', $data->getAttr('displayTargetCouponUbUtilizationBusinessIdList', [])) . '"',
                        'isFieldOnly' => true
                    ])
                    @include('common.input.inputGroup', [
                        'type' => 'selection',
                        'field' => 'affiliationOffice[]',
                        'label' => __('messages.custom.specifyAoOrUbCode'),
                        'defaultItem' => __('words.AffiliateOffice'),
                        'list' => $listAo,
                        'valSelected' => old('affiliationOffice', $data->getAttr('displayTargetCouponAoAffiliationOfficeIdList', [])),
                        'customClass' =>  [ 'input' => 'ao-list form-control' ],
                        'customAttr' => 'multiple="multiple" auto-init-select2 data-placeholder="' . __('words.AffiliateOffice') . '" data-original-value="' . implode(',', $data->getAttr('displayTargetCouponAoAffiliationOfficeIdList', [])) . '"',
                        'isFieldOnly' => true
                    ])
                </div>
                @include('common.validationError', [
                    'key' => 'utilizationBusiness',
                    'class' => 'ub-error',
                ])
                @include('common.validationError', [
                    'key' => 'affiliationOffice',
                    'class' => 'ao-error',
                ])
            </div>
            @if ($data->isNotEmpty)
                @livewire('lw-union-member-code-table', ['params' => ['dataId' => $data->getAttr('id'),'interface' => \Globals::iCoupon(),'dTMethodName' => 'displayTargetCoupon']])
            @endif
        </div>
        @include('common.input.inputGroup', [
            'type' => 'number',
            'field' => 'useCount',
            'label' => __('words.AvailableTimes'),
            'subLabel' => __('messages.custom.specifyNumberOfTimeCouponCanBeUsed'),
            'placeholder' => 0,
            'inputVal' => old('useCount', $data->getAttr('useCount')),
            'valDefault' => $data->getAttr('useCount'),
            'customClass' =>  [ 'input' => 'col-md-3' ],
            'required' => true
        ])
        @include('common.input.inputGroup', [
            'type' => 'number',
            'field' => 'useTime',
            'label' => __('words.SettingCouponUsageTimeLimit'),
            'subLabel' => __('messages.custom.ifYouWantToMakeATimeLimitUseTheCouponPleaseEnterTheTime'),
            'placeholder' => 0,
            'inputVal' => old('useTime', $data->getAttr('useTime')),
            'valDefault' => $data->getAttr('useTime'),
            'customClass' =>  [ 'input' => 'col-md-3' ],
            'required' => true
        ])
        @foreach($radioBtnGroup as $field)
            @include('common.input.inputGroup', $field)
        @endforeach
        @include('common.input.inputGroup', [
            'type' => 'number',
            'field' => 'pointGrantPurchasesPrice',
            'label' => __('words.EnterAmntIfGrantCondIsAmnt'),
            'placeholder' => 0,
            'inputVal' => old('pointGrantPurchasesPrice', $data->getAttr('pointGrantPurchasesPrice')),
            'valDefault' => $data->getAttr('pointGrantPurchasesPrice'),
            'customClass' =>  [ 'input' => 'col-md-3' ],
            'required' => true
        ])
        @include('common.input.inputGroup', [
            'type' => 'number',
            'field' => 'pointGrantPurchasesCount',
            'label' => __('words.EnterPointsIfGrantCondIsPoints'),
            'placeholder' => 0,
            'inputVal' => old('pointGrantPurchasesCount', $data->getAttr('pointGrantPurchasesCount')),
            'valDefault' => $data->getAttr('pointGrantPurchasesCount'),
            'customClass' =>  [ 'input' => ' col-md-3' ],
            'required' => true
        ])
        @include('common.input.inputGroup', [
            'type' => 'radio',
            'field' => 'productFlg',
            'label' => __('words.ProductsEligibleBenefits'),
            'subLabel' => __('messages.custom.pleaseSpecifyForGrantingBenfits'),
            'list' => $listTargetProdOptions,
            'valSelected' => old('productFlg', $data->getAttr('productFlg')),
            'valDefault' => $data->getAttr('productFlg'),
            'required' => $data->isEmpty
        ])
        <div class="col-md-12 pl-3 target-coupon-value-box">
            @include('common.input.inputGroup', [
                'type' => 'fileCustom',
                'label' =>  __('words.specifiedProdCodeCsv'),
                'id' => 'specifiedProdCodeCsvTrigger',
                'name' => 'specifiedProdCodeCsvTrigger',
                'classContainer' => 'specifiedProdCodeCsvTrigger file-csv' . ($errors->has('specifiedProdCodeCsv') ? ' is-invalid' : ''),
                'extAllowed' => \Globals::implode($listCsvAcceptedExtension, ',', '.'),
                'customlabel' => $csvLabelSpecifiedProdCode,
                'field' => 'specifiedProdCodeCsv',
                'hiddenValue' => $csvUrlSpecifiedProdCode,
                'disabled' => false,
                'originVal' => '',
                'customClass' => [
                    'formGroup' => 'target-csv-prodcode',
                ],
                'required' => true
            ])
            @include('common.input.inputGroup', [
                'type' => 'fileCustom',
                'label' =>  __('words.SpecifiedCategoryCsv'),
                'id' => 'prodCategoryCsvTrigger',
                'name' => 'prodCategoryCsvTrigger',
                'classContainer' => 'prodCategoryCsvTrigger file-csv' . ($errors->has('prodCategoryCsv') ? ' is-invalid' : ''),
                'extAllowed' => \Globals::implode($listCsvAcceptedExtension, ',', '.'),
                'customlabel' => $csvLabelSpecifiedCategory,
                'field' => 'prodCategoryCsv',
                'hiddenValue' => $csvUrlSpecifiedCategory,
                'disabled' => false,
                'originVal' => '',
                'customClass' => [
                    'formGroup' => 'target-csv-prodcategory',
                ],
                'required' => true
            ])
            @if ($data->isNotEmpty)
                <div class="coupon-prodTarget-data">
                    @livewire('coupon.lw-product-table', ['params' => $data->getAttr('id')])
                    @livewire('coupon.lw-category-designation-table', ['params' => $data->getAttr('id')])
                </div>
            @endif
        </div>

        @include('common.input.inputGroup', [
            'type' => 'radio',
            'field' => 'storeFlg',
            'label' => __('words.SpecifyStoreForGrantBenefitCond'),
            'list' => $listStoreFlgOptions,
            'valSelected' => old('storeFlg', $data->getAttr('storeFlg')),
            'valDefault' => $data->getAttr('storeFlg'),
            'required' => true
        ])

        @include('common.input.inputGroup', [
            'type' => 'checkBox',
            'field' => 'stores',
            'label' => __(''),
            'subLabel' => __('messages.custom.specifyStoreConditionForGrantingBenefits'),
            'list' => $listStore,
            'valsSelected' => old('stores', $data->getAttr('couponPlanStoreIdList', [])),
            'valDefault' => $data->getAttr('stores'),
            'required' => true
        ])

        @include('common.input.inputGroup', [
            'type' => 'radio',
            'field' => 'increaseFlg',
            'label' => __('words.TypesOfBenefits'),
            'subLabel' => __('messages.custom.specifyBenefitsToBeGivenWhenConditionsAreMet'),
            'list' => $listIncreaseFlagOptions,
            'valSelected' => old('increaseFlg', $data->getAttr('increaseFlg')),
            'valDefault' => $data->getAttr('increaseFlg'),
            'required' => true
        ])
        @include('common.input.inputGroup', [
            'type' => 'number',
            'field' => 'grantPoint',
            'label' => __('words.EnterPointsIfBenefitCondIsPoints'),
            'placeholder' => 0,
            'inputVal' => old('grantPoint', $data->getAttr('grantPoint')),
            'valDefault' => $data->getAttr('grantPoint'),
            'customClass' =>  [ 'input' => 'col-md-3' ],
            'required' => true
        ])
        <div class="col-md-12 pl-3">
            @include('common.input.inputGroup', [
                'type' => 'fileCustom',
                'label' =>  __('words.CouponImg'),
                'id' => 'couponImage',
                'name' => 'couponImage',
                'classContainer' => 'couponImage' . ($errors->has('cuponImg') ? ' is-invalid' : ''),
                'extAllowed' => \Globals::implode($listImageAcceptedExtension, ',', '.'),
                'customlabel' => $thumbnailLabel,
                'field' => 'cuponImg',
                'hiddenValue' => $thumbnailUrl,
                'disabled' => 0,
                'originVal' => $data->getAttr('cuponImg'),
                'required' => true
            ])
        </div>
        <div class="form-group">
            <p><b>{{ __('words.BodyPostedContent') }} <req></req></b></p>
            <textarea id="contentsOriginalValue" hidden>{{ $data->getAttr('cuponText') }}</textarea>
            <textarea id="cuponText" name="cuponText" data-original-value="#contentsOriginalValue">{!! old('cuponText', $data->getAttr('cuponText')) !!}</textarea>
            {!! $errors->has('cuponText') ? '<div class="is-invalid"></div>' : '' !!}
            @include('common.validationError', ['key' => 'cuponText'])
        </div>
        <div class="row justify-content-center mb-3">
            <button type="button" id="preview" class="btn btn-02 col-md-3 col-sm-3 w-100">{{ __('words.Preview') }}</button>
        </div>
        <div class="row justify-content-center mb-3">
            <button id="submit" type="submit" class="btn btn-warning col-md-3 col-sm-3 w-100 text-white">{{ __('words.Post') }}</button>
        </div>
        <div class="row justify-content-end mt-2 mb-3">
            <a href="{{ route('coupon.index') }}" class="btn btn-02">{{ __('words.BackToList') }}</a>
        </div>
    </form>
@endsection
