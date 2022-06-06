@extends('layouts.app')

@section('bodyClass', 'pg-flyer pg-flyer-detail')

@include('assets.livewire')
@include('assets.select2')
@include('assets.lightbox')
@include('assets.datetimepicker')
@include('assets.js.fileHandle')
@include('assets.page.flyerPlanJs')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="far fa-newspaper"></i>
        {{ $data->isEmpty ? __('words.FlyerNewPost') : __('words.FlyerEditPost') }}
    @endsection
    @include('common.menu.detailMenu', [
        'page' => 'flyer'
    ])
@endsection

@push('modals')
    @include('modals.flyerPlan.preview')
@endpush

@php
    $routeList = route('flyer.index');
    $csvLabel = '';
    $csvUrl = old('unionMemberCsv', $data->getAttr('csvInS3'));
    $logo = '';
    $flyerImgLabel = '';
    $flyerImgUrl = old('flyerImg', $data->getAttr('flyerImg'));
    $flyerImgUrl = empty($flyerImgUrl) ? '' : $flyerImgUrl;
    $flyerUraImgLabel = '';
    $flyerUraImgUrl = old('flyerUraImg', $data->getAttr('flyerUraImg'));
    $flyerUraImgUrl = empty($flyerUraImgUrl) ? '' : $flyerUraImgUrl;

    if (old('unionMemberCsv', null) != null) {
        if ($errors->has('unionMemberCsv')) {
            $logo = '<i class="fa fa-file-arrow-down"></i> ';
            $csvLabel = '<a href="' . $csvUrl . '" class="text-danger" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrl) . '</a>';
            $csvLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        } else {
            $logo = '<i class="fa fa-file-csv"></i> ';
            $csvLabel = '<a href="' . $csvUrl . '" download>' . $logo . \Globals::hUpload()::getBaseName($csvUrl) . '</a>';
            $csvLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
        }
    }

    if (old('flyerImg', null) == null) {
        if ($data->isNotEmpty && !empty($flyerImgUrl)) {
            if ($data->isFlyerImgExist) {
                $logo = '<i class="fa fa-image"></i> ';
                $flyerImgLabel = '<a href="' . $flyerImgUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($flyerImgUrl) . '</a>';
                $flyerImgLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
            } else {
                $logo = '<i class="fa fa-circle-xmark text-red" title="' . __('messages.custom.imageNotExist') . '"></i> ';
                $flyerImgLabel = $logo . \Globals::hUpload()::getBaseName($flyerImgUrl);
            }
        }
    } else {
        $logo = '<i class="fa fa-image"></i> ';
        $flyerImgLabel = '<a href="' . $flyerImgUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($flyerImgUrl) . '</a>';
    }

    if (old('flyerUraImg', null) == null) {
        if ($data->isNotEmpty && !empty($flyerUraImgUrl)) {
            if ($data->IsFlyerUraImgExist) {
                $logo = '<i class="fa fa-image"></i> ';
                $flyerUraImgLabel = '<a href="' . $flyerUraImgUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($flyerUraImgUrl) . '</a>';
                $flyerUraImgLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
            } else {
                if (!empty($flyerUraImgUrl)) {
                    $logo = '<i class="fa fa-circle-xmark text-red" title="' . __('messages.custom.imageNotExist') . '"></i> ';
                    $flyerUraImgLabel = $logo . \Globals::hUpload()::getBaseName($flyerUraImgUrl);
                }
            }
        }
    } else {
        $logo = '<i class="fa fa-image"></i> ';
        $flyerUraImgLabel = '<a href="' . $flyerUraImgUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($flyerUraImgUrl) . '</a>';
    }
@endphp

@section('content')
    <form method="POST" enctype="multipart/form-data" id="form" action="{{ $data->isEmpty? route('flyer.store') : route('flyer.update', ['flyerId' => $data->id]) }}">
        @csrf

        @if($data->isNotEmpty)
            <input type="text" hidden name="flyerPlanId" value="{{ $data->id }}">

            @if(count($data->displayTargetFlyerIdList) != 0)
                <input type="text" hidden name="displayTargetFlyer" value="{{ implode(',', $data->displayTargetFlyerIdList) }}">
            @endif
        @endif

        <div class="form-group mt-3">
            <div class="row">
                @if($data->isNotEmpty)
                    <div class="col-12">
                        <label>{{ __('words.Id') }}: {{ $data->getAttr('id') }}</label>
                    </div>
                @endif
                <div class="col-12">
                    <label class="mb-0">{{ __('words.Status') }}: {{ $data->getAttr('statusStr', __('words.New')) }}</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="flyerName">{{ __('words.Title') }} <req></req></label>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control @if($errors->has('flyerName')) is-invalid @endif" name="flyerName" id="flyerName" placeholder="{{ __('words.Title') }}" value="{{ old('flyerName', $data->getAttr('flyerName')) }}" data-original-value="{{ $data->getAttr('flyerName') }}">
                    @include('common.validationError', ['key' => 'flyerName'])
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>{{ __('words.SelectPublicationDateTime') }} @if($data->isEmpty)<req></req>@endif</label>
            <div class="icheck-primary">
                <input type="radio" id="publishType1"  value="0" name="selectPublicationDateTime" {!! old('selectPublicationDateTime') == '0' ? 'checked': ''!!}>
                <label for="publishType1">{{ $data->isEmpty ? __('words.PublishSoon') : __('words.UpdateSoon') }}</label>
            </div>
            <div class="icheck-primary{{ $errors->has('selectPublicationDateTime') ? ' is-invalid' : '' }}">
                <input type="radio" id="publishType2"  value="1" name="selectPublicationDateTime"  {!! old('selectPublicationDateTime') == '1' ? 'checked': ''!!}>
                <label for="publishType2">{{ $data->isEmpty ? __('words.BookAndPublish') : __('words.BookAndRenew') }}</label>
            </div>
            @include('common.validationError', ['key' => 'selectPublicationDateTime'])
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @php
                        $startDate = null;
                        $startTime = null;
                        if ($data->isNotEmpty) {
                            $startDate = $data->formatDate('startDateTime', 'Y/m/d');
                            $startTime = $data->formatDate('startDateTime', 'H:i');
                        }
                    @endphp
                    <div class="form-group col-md-3">
                        <label>{{ __('words.ReleaseDate') }} <req></req></label>
                        <div class="input-group date" id="startDate" data-target-input="nearest">
                            <input type="text" id="startDateInput" class="form-control datetimepicker-input @if($errors->has('startDateTime')) is-invalid @endif" name="startDate" placeholder="{{ __('words.DateFormat') }}" value="{{ old( 'startDate', $startDate ) }}" data-original-value="{{ $startDate }}" data-target="#startDate" autocomplete="off">
                            <div class="input-group-append" data-target="#startDate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            @if($errors->has('startDateTime'))
                                <span id="startDateTime-error" class="error invalid-feedback">{{ $errors->first('startDateTime') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ __('words.PublicationTime') }} <req></req></label>
                        <div class="input-group date" id="startTime" data-target-input="nearest">
                            <input type="text" id="startTimeInput" class="form-control datetimepicker-input @if($errors->has('startDateTime')) is-invalid @endif" name="startTime" value="{{ old( 'startTime', $startTime ) }}" data-original-value="{{ $startTime }}" data-target="#startTime" autocomplete="off">
                            <div class="input-group-append" data-target="#startTime" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row">
                    @php
                        $endDate = null;
                        $endTime = null;

                        if ($data->isNotEmpty) {
                            $endDate = $data->formatDate('endDateTime', 'Y/m/d');
                            $endTime = $data->formatDate('endDateTime', 'H:i');
                        }
                    @endphp
                    <div class="form-group col-md-3">
                        <label>{{ __('words.ReleaseEndDate') }} <req></req></label>
                        <div class="input-group date" id="endDate" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input @if($errors->has('endDateTime')) is-invalid @endif" name="endDate" placeholder="{{ __('words.DateFormat') }}"  value="{{ old( 'endDate', $endDate ) }}" data-original-value="{{ $endDate }}" data-target="#endDate" autocomplete="off">
                            <div class="input-group-append" data-target="#endDate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            @if($errors->has('endDateTime'))
                                <span id="endDateTime-error" class="error invalid-feedback">{{ $errors->first('endDateTime') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ __('words.PublicationEndTime') }} <req></req></label>
                        <div class="input-group date" id="endTime" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input @if($errors->has('endDateTime')) is-invalid  @endif" name="endTime" value="{{ old( 'endTime', $endTime ) }}" data-original-value="{{ $endTime }}" data-target="#endTime" autocomplete="off">
                            <div class="input-group-append" data-target="#endTime" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>{{ __('words.DisplayStore') }} <req></req></label>
            <div class="row">
                @foreach(array_slice(config('const.listStore'), 0, 4, true) as $id => $wordValue)
                    <div class="col-md-2 icheck-primary">
                        <input type="checkbox" id="displayStore{{ $id }}" value="{{ $id }}"
                            name="displayStore[]" {!! in_array($id, old('displayStore', $data->getAttr('flyerDisplayStoreIdList', []))) ? 'checked': '' !!}
                            data-original-value="{{ implode(',', $data->getAttr('flyerDisplayStoreIdList', [])) }}">
                        <label for="displayStore{{ $id }}">{{ __($wordValue) }}</label>
                    </div>
                @endforeach
            </div>
            <div class="row{{ $errors->has('displayStore') ? ' is-invalid' : '' }}">
                @foreach(array_slice(config('const.listStore'), 4, null, true) as $id => $wordValue)
                    <div class="col-md-2 icheck-primary">
                        <input type="checkbox" id="displayStore{{ $id }}" value="{{ $id }}"
                            name="displayStore[]" {!! in_array($id, old('displayStore', $data->getAttr('flyerDisplayStoreIdList', []))) ? 'checked': '' !!}
                            data-original-value="{{ implode(',', $data->getAttr('flyerDisplayStoreIdList', [])) }}">
                        <label for="displayStore{{ $id }}">{{ __($wordValue) }}</label>
                    </div>
                @endforeach
            </div>
            @if($errors->has('displayStore'))
                <span class="error invalid-feedback">{{ $errors->first('displayStore') }}</span>
            @endif
        </div>
        <div class="form-group">
            <label>{{ __('words.UnionMemberToDisplay') }} <req></req></label>
            <div class="icheck-primary">
                <input type="radio" id="displayTargetFlg1" value="{{ \Globals::mFlyerPlan()::DSPTARGET_UNCONDITIONAL }}"
                    name="displayTargetFlg" {!! old('displayTargetFlg', $data->getAttr('displayTargetFlg', '-1')) == \Globals::mFlyerPlan()::DSPTARGET_UNCONDITIONAL ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('displayTargetFlg') }}">
                <label for="displayTargetFlg1">{{ __('words.Unconditional') }}</label>
            </div>
            <div class="icheck-primary">
                <input type="radio" id="displayTargetFlg2" value="{{ \Globals::mFlyerPlan()::DSPTARGET_UNIONMEMBER }}"
                    name="displayTargetFlg" {!! old('displayTargetFlg', $data->getAttr('displayTargetFlg', '-1')) == \Globals::mFlyerPlan()::DSPTARGET_UNIONMEMBER ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('displayTargetFlg') }}">
                <label for="displayTargetFlg2">{{ __('words.UnionMemberDesignation') }}</label>
            </div>
            <div class="icheck-primary">
                <input type="radio" id="displayTargetFlg3" value="{{ \Globals::mFlyerPlan()::DSPTARGET_UB }}"
                    name="displayTargetFlg" {!! old('displayTargetFlg', $data->getAttr('displayTargetFlg', '-1')) == \Globals::mFlyerPlan()::DSPTARGET_UB ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('displayTargetFlg') }}">
                <label for="displayTargetFlg3">{{ __('words.UserBusinessDesignation') }}</label>
            </div>
            <div class="icheck-primary{{ $errors->has('displayTargetFlg') ? ' is-invalid' : '' }}">
                <input type="radio" id="displayTargetFlg4" value="{{ \Globals::mFlyerPlan()::DSPTARGET_AO }}"
                    name="displayTargetFlg" {!! old('displayTargetFlg', $data->getAttr('displayTargetFlg', '-1')) == \Globals::mFlyerPlan()::DSPTARGET_AO ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('displayTargetFlg') }}">
                <label for="displayTargetFlg4">{{ __('words.OfficeDesignation') }}</label>
            </div>
            @include('common.validationError', ['key' => 'displayTargetFlg'])
        </div>
        <div class="col-md-12 pl-3 target-user-value-box">
            <div class="form-group target-csv">
                <p><b>{{ __('words.CSVFileUploadInstruction') }} <req></req></b></p>
                @include('common.input.fileCustom', [
                    'id' => \Globals::FILETYPE_CSV,
                    'name' => \Globals::FILETYPE_CSV,
                    'classContainer' => \Globals::FILETYPE_CSV . ($errors->has('unionMemberCsv') ? ' is-invalid' : ''),
                    'accept' => \Globals::implode(\Globals::CSV_ACCEPTEDEXTENSION, ',', '.'),
                    'label' => $csvLabel,
                    'hiddenName' => 'unionMemberCsv',
                    'hiddenValue' => $csvUrl,
                    'disabled' => old('displayTargetFlg', $data->getAttr('displayTargetFlg')) != \Globals::mFlyerPlan()::DSPTARGET_UNIONMEMBER,
                    'originalValue' => $data->getAttr('csvInS3'),
                ])
                <span id="csv-error" class="error invalid-feedback">{{ $errors->first('unionMemberCsv') }}</span>
            </div>
            <div class="form-group ub-ao-box target-select-option">
                <label>{{ __('messages.custom.specifyAoOrUb') }} <req></req></label>
                <div class="row">
                    <div class="col-md-6{{ $errors->has('utilizationBusiness') || $errors->has('affiliationOffice') ? ' is-invalid' : '' }}">
                        <select class="ub-list form-control" name="utilizationBusiness[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.BusinessSelection') }}" data-original-value="{{ implode(',', $data->getAttr('displayTargetFlyerUbUtilizationBusinessIdList', [])) }}">
                            <option value="" disabled>{{ __('words.BusinessSelection') }}</option>
                            @foreach( $listUb as $key => $val )
                                <option value="{{ $key }}"{!! in_array($key, old('utilizationBusiness', $data->getAttr('displayTargetFlyerUbUtilizationBusinessIdList', []))) ?  ' selected': '' !!}>{{ \Globals::__($val) }}</option>
                            @endforeach
                        </select>
                        <select class="ao-list form-control" name="affiliationOffice[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.AffiliateOffice') }}" data-original-value="{{ implode(',', $data->getAttr('displayTargetFlyerAoAffiliationOfficeIdList', [])) }}">
                            <option value="" disabled>{{ __('words.AffiliateOffice') }}</option>
                            @foreach( $listAo as $key => $val )
                                <option value="{{ $key }}" {!! in_array($key, old('affiliationOffice', $data->getAttr('displayTargetFlyerAoAffiliationOfficeIdList', []))) ?  'selected': '' !!}>{{ \Globals::__($val) }}</option>
                            @endforeach
                        </select>
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
            </div>
            @if ($data->isNotEmpty)
                @livewire('lw-union-member-code-table', ['params' => [
                    'dataId' => $data->getAttr('id'),
                    'interface' => \Globals::iFlyer(),
                    'dTMethodName' => 'displayTargetFlyer'
                ]])
            @endif
        </div>
        <div class="form-group">
            <label>{{ __('words.FlyerImgTable') }} <req></req></label>{{ __('messages.custom.flyerFileTypesLimitation') }}
            @include('common.input.fileCustom', [
                'id' => 'flyerImageFront',
                'name' => 'flyerImageFront',
                'classContainer' => 'flyerImageFront' . ($errors->has('flyerImg') ? ' is-invalid' : ''),
                'accept' => \Globals::implode(\Globals::IMG_ACCEPTEDEXTENSION, ',', '.'),
                'label' => $flyerImgLabel,
                'hiddenName' => 'flyerImg',
                'hiddenValue' => $flyerImgUrl,
                'originalValue' => $data->getAttr('flyerImg')
            ])
            <span id="flyerImageFront-error" class="error invalid-feedback">{{ $errors->first('flyerImg') }}</span>
        </div>
        <div class="form-group">
            <label>{{ __('words.FlyerImgBack') }}</label>{{ __('messages.custom.flyerFileTypesLimitation') }}
            @include('common.input.fileCustom', [
                'id' => 'flyerImageBack',
                'name' => 'flyerImageBack',
                'classContainer' => 'flyerImageBack' . ($errors->has('flyerUraImg') ? ' is-invalid' : ''),
                'accept' => \Globals::implode(\Globals::IMG_ACCEPTEDEXTENSION, ',', '.'),
                'label' => $flyerUraImgLabel,
                'hiddenName' => 'flyerUraImg',
                'hiddenValue' => $flyerUraImgUrl,
                'originalValue' => $data->getAttr('flyerUraImg'),
            ])
            <span id="flyerImageBack-error" class="error invalid-feedback">{{ $errors->first('flyerUraImg') }}</span>
        </div>

        <div class="row justify-content-center mb-3">
            <button type="submit" id="preview" class="btn btn-02 col-md-3 col-sm-3 w-100">{{ __('words.Preview') }}</button>
        </div>
        <div class="row justify-content-center mb-3">
            <button id="submit" type="submit" class="btn btn-warning col-md-3 col-sm-3 w-100 text-white">{{ __('words.Post') }}</button>
        </div>
        <div class="row justify-content-end mt-2 mb-3">
            <a href="{{ $routeList }}" class="btn btn-02">{{ __('words.BackToList') }}</a>
        </div>
    </form>
@endsection
