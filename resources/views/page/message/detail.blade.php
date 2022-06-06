@extends('layouts.app')

@section('bodyClass', 'pg-message pg-message-detail')

@include('assets.livewire')
@include('assets.select2')
@include('assets.trumbowyg')
@include('assets.lightbox')
@include('assets.datetimepicker')
@include('assets.js.fileHandle')
@include('assets.page.messageJs')
@include('modals.message.preview')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="fas fa-envelope"></i>
        {{ __('words.Message') . ' ' }}{{ $data->isEmpty ? __('words.CreateNew') : ($data->getAttr('isStatusSend', false) ? __('words.Detail') : __('words.Edit')) }}
    @endsection
    @include('common.menu.detailMenu', [
        'page' => 'message'
    ])
@endsection

@section('content')
    @php
        $route = $data->isEmpty ? route('message.store') : route('message.update', $data->id);

        $csvLabel = '';
        $csvUrl = old('unionMemberCsv');
        $logo = '';
        $imageLabel = '';
        $imageUrl = old('thumbnail', $data->getAttr('thumbnail'));
        $imageUrl = empty($imageUrl) ? '' : $imageUrl;

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

        if (old('thumbnail', null) == null) {
            if ($data->isNotEmpty) {
                if ($data->IsThumbnailExist) {
                    $logo = '<i class="fa fa-image"></i> ';
                    $imageLabel = '<a href="' . $imageUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($imageUrl) . '</a>';
                    $imageLabel .= '<span role="btnRemove" class="text-danger ml-2">x</span>';
                } else {
                    $imageLabel = $logo . \Globals::hUpload()::getBaseName($imageUrl);
                }
            }
        } else {
            $logo = '<i class="fa fa-image"></i> ';
            $imageLabel = '<a href="' . $imageUrl . '" data-toggle="lightbox" title="' . __('words.Preview') . '">' . $logo . \Globals::hUpload()::getBaseName($imageUrl) . '</a>';
        }
    @endphp
    <form method="POST" enctype="multipart/form-data" id="form" action="{{ $route }}">
        @csrf

        @if($data->isNotEmpty)
            <input type="text" hidden name="messageId" value="{{ $data->id }}">

            @if(count($data->sendTargetMessageIdList) != 0)
                <input type="text" hidden name="sendTargetMessage" value="{{ implode(',', $data->sendTargetMessageIdList) }}">
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
            <label for="messageName">{{ __('words.ManagementName') }} @if($data->getAttr('isStatusNotSend', true))<req></req>@endif</label>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control @if($errors->has('messageName')) is-invalid @endif" name="messageName" id="messageName"
                        placeholder="{{ __('words.ManagementName') }}" value="{{ old('messageName', $data->getAttr('messageName')) }}"
                        data-original-value="{{ $data->getAttr('messageName') }}"
                        {{ $data->getAttr('isStatusSend', false) ? 'readonly' : '' }}>
                    @include('common.validationError', ['key' => 'messageName'])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @php
                        $sendDate = null;
                        $sendTime = null;
                        if ($data->isNotEmpty) {
                            $sendDate = $data->formatDate('sendDateTime', 'Y/m/d');
                            $sendTime = $data->formatDate('sendDateTime', 'H:i');
                        }
                    @endphp
                    <div class="form-group col-3">
                        <label>{{ __('words.SendDate') }} @if($data->getAttr('isStatusNotSend', true))<req></req>@endif</label>
                        <div class="input-group date" id="sendDate" data-target-input="nearest">
                            <input type="text" id="sendDateInput" class="form-control datetimepicker-input @if($errors->has('sendDateTime')) is-invalid @endif" name="sendDate" placeholder="{{ __('words.DateFormat') }}" value="{{ old( 'sendDate', $sendDate ) }}" data-original-value="{{ $sendDate }}" data-target="#sendDate" autocomplete="off">
                            <div class="input-group-append" data-target="#sendDate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            @if($errors->has('sendDateTime'))
                                <span id="sendDateTime-error" class="error invalid-feedback">{{ $errors->first('sendDateTime') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-3">
                        <label>{{ __('words.TransmissionTime') }} @if($data->getAttr('isStatusNotSend', true))<req></req>@endif</label>
                        <div class="input-group date" id="sendTime" data-target-input="nearest">
                            <input type="text" id="sendTimeInput" class="form-control datetimepicker-input @if($errors->has('sendDateTime')) is-invalid @endif" name="sendTime" value="{{ old( 'sendTime', $sendTime ) }}" data-original-value="{{ $sendTime }}" data-target="#sendTime" autocomplete="off">
                            <div class="input-group-append" data-target="#sendTime" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-clock"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>{{ __('words.SelectTheRecipient') }} @if($data->getAttr('isStatusNotSend', true))<req></req>@endif</label>
            <div class="icheck-primary">
                <input type="radio" id="sendTargetFlg1" value="{{ \Globals::mMessage()::SENDTARGET_UNCONDITIONAL }}"
                    name="sendTargetFlg" {!! old('sendTargetFlg', $data->getAttr('sendTargetFlg', '-1')) == \Globals::mMessage()::SENDTARGET_UNCONDITIONAL ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('sendTargetFlg') }}">
                <label for="sendTargetFlg1">{{ __('words.Unconditional') }}</label>
            </div>
            <div class="icheck-primary">
                <input type="radio" id="sendTargetFlg2" value="{{ \Globals::mMessage()::SENDTARGET_UNIONMEMBER }}"
                    name="sendTargetFlg" {!! old('sendTargetFlg', $data->getAttr('sendTargetFlg', '-1')) == \Globals::mMessage()::SENDTARGET_UNIONMEMBER ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('sendTargetFlg') }}">
                <label for="sendTargetFlg2">{{ __('words.UnionMemberDesignation') }}</label>
            </div>
            <div class="icheck-primary">
                <input type="radio" id="sendTargetFlg3" value="{{ \Globals::mMessage()::SENDTARGET_UB }}"
                    name="sendTargetFlg" {!! old('sendTargetFlg', $data->getAttr('sendTargetFlg', '-1')) == \Globals::mMessage()::SENDTARGET_UB ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('sendTargetFlg') }}">
                <label for="sendTargetFlg3">{{ __('words.UserBusinessDesignation') }}</label>
            </div>
            <div class="icheck-primary{{ $errors->has('sendTargetFlg') ? ' is-invalid' : '' }}">
                <input type="radio" id="sendTargetFlg4" value="{{ \Globals::mMessage()::SENDTARGET_AO }}"
                    name="sendTargetFlg" {!! old('sendTargetFlg', $data->getAttr('sendTargetFlg', '-1')) == \Globals::mMessage()::SENDTARGET_AO ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('sendTargetFlg') }}">
                <label for="sendTargetFlg4">{{ __('words.OfficeDesignation') }}</label>
            </div>
            <div class="icheck-primary{{ $errors->has('sendTargetFlg') ? ' is-invalid' : '' }}">
                <input type="radio" id="sendTargetFlg5" value="{{ \Globals::mMessage()::SENDTARGET_STORE }}"
                    name="sendTargetFlg" {!! old('sendTargetFlg', $data->getAttr('sendTargetFlg', '-1')) == \Globals::mMessage()::SENDTARGET_STORE ? 'checked': '' !!}
                    data-original-value="{{ $data->getAttr('sendTargetFlg') }}">
                <label for="sendTargetFlg5">{{ __('words.SelectAtTheRegisteredStore') }}</label>
            </div>
            @include('common.validationError', ['key' => 'sendTargetFlg'])
        </div>
        <div class="col-md-12 pl-3 target-user-value-box">
            <div class="form-group target-csv">
                <p><b>{{ __('words.CSVFileUploadInstruction') }} @if($data->getAttr('isStatusNotSend', true))<req></req>@endif</b></p>
                @include('common.input.fileCustom', [
                    'id' => \Globals::FILETYPE_CSV,
                    'name' => \Globals::FILETYPE_CSV,
                    'classContainer' => \Globals::FILETYPE_CSV . ($errors->has('unionMemberCsv') ? ' is-invalid' : ''),
                    'accept' => \Globals::implode(\Globals::CSV_ACCEPTEDEXTENSION, ',', '.'),
                    'label' => $csvLabel,
                    'hiddenName' => 'unionMemberCsv',
                    'hiddenValue' => $csvUrl,
                    'disabled' => old('sendTargetFlg', $data->getAttr('sendTargetFlg')) != 1,
                    'originalValue' => '',
                ])
                <span id="csv-error" class="error invalid-feedback">{{ $errors->first('unionMemberCsv') }}</span>
            </div>
            <div class="form-group ub-ao-store-box target-select-option">
                <label>{{ __('messages.custom.specifyAoOrUbOrStore') }} @if($data->getAttr('isStatusNotSend', true))<req></req>@endif</label>
                <div class="row">
                    <div class="col-md-6{{ $errors->has('utilizationBusiness') || $errors->has('affiliationOffice') || $errors->has('storeId') ? ' is-invalid' : '' }}">
                        <select class="ub-list form-control" name="utilizationBusiness[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.BusinessSelection') }}" data-original-value="{{ implode(',', $data->getAttr('sendTargetMessageUbUtilizationBusinessIdList', [])) }}">
                            <option value="" disabled>{{ __('words.BusinessSelection') }}</option>
                            @foreach( $listUb as $key => $val )
                                <option value="{{ $key }}"{!! in_array($key, old('utilizationBusiness', $data->getAttr('sendTargetMessageUbUtilizationBusinessIdList', []))) ?  ' selected': '' !!}>{{ \Globals::__($val) }} ({{ $listUbCount[$key] }})</option>
                            @endforeach
                        </select>
                        <select class="ao-list form-control" name="affiliationOffice[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.AffiliateOffice') }}" data-original-value="{{ implode(',', $data->getAttr('sendTargetMessageAoAffiliationOfficeIdList', [])) }}">
                            <option value="" disabled>{{ __('words.AffiliateOffice') }}</option>
                            @foreach( $listAo as $key => $val )
                                <option value="{{ $key }}" {!! in_array($key, old('affiliationOffice', $data->getAttr('sendTargetMessageAoAffiliationOfficeIdList', []))) ?  'selected': '' !!}>{{ \Globals::__($val) }} ({{ $listAoCount[$key] }})</option>
                            @endforeach
                        </select>
                        <select class="store-list form-control" name="storeId[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.Store') }}" data-original-value="{{ implode(',', $data->getAttr('sendTargetMessageStoreIdList', [])) }}">
                            <option value="" disabled>{{ __('words.Store') }}</option>
                            @foreach( $storeList as $key => $val )
                                <option value="{{ $key }}" {!! in_array($key, old('storeId', $data->getAttr('sendTargetMessageStoreIdList', []))) ?  'selected': '' !!}>{{ __($val) }} ({{ $storeListCount[$key] }})</option>
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
                    @include('common.validationError', [
                        'key' => 'storeId',
                        'class' => 'store-error',
                    ])
                </div>
            </div>
            @if ($data->isNotEmpty)
                @livewire('lw-union-member-code-table', ['params' => [
                    'dataId' => $data->getAttr('id'),
                    'interface' => \Globals::iMessage(),
                    'dTMethodName' => 'sendTargetMessage'
                ]])
            @endif
        </div>
        <div class="form-group">
            <p><b>{{ __('words.BodyContentTransmissionEmoji') }} @if($data->getAttr('isStatusNotSend', true))<req></req>@endif</b></p>
            @if($data->getAttr('isStatusNotSend', true))
                <textarea id="contentsOriginalValue" hidden>{{ $data->getAttr('contents') }}</textarea>
                <textarea id="contents" name="contents" data-original-value="#contentsOriginalValue">{!! old('contents', $data->getAttr('contents')) !!}</textarea>
                {!! $errors->has('contents') ? '<div class="is-invalid"></div>' : '' !!}
                @include('common.validationError', ['key' => 'contents'])
            @else
                <div class="contents">
                    {!! $data->getAttr('contents') !!}
                </div>
            @endif
        </div>
        <div class="form-group">
            <p>
                <b>{{ __('words.SendImage') }}</b>
                @if($data->getAttr('isStatusNotSend', true))
                    <span class="text-xs ml-4">ファイルサイズは2M以下、形式はJPEG or PNG のみとなります。</span>
                @endif
            </p>
            @include('common.input.fileCustom', [
                'id' => 'sendImage',
                'name' => 'sendImage',
                'classContainer' => 'sendImage' . ($errors->has('thumbnail') ? ' is-invalid' : ''),
                'accept' => \Globals::implode(\Globals::IMG_ACCEPTEDEXTENSION, ',', '.'),
                'label' => $imageLabel,
                'hiddenName' => 'thumbnail',
                'hiddenValue' => $imageUrl,
                'disabled' => $data->getAttr('isStatusSend', false),
                'originalValue' => $data->getAttr('thumbnail')
            ])
            <span id="sendImage-error" class="error invalid-feedback">{{ $errors->first('thumbnail') }}</span>
        </div>
        @if($data->getAttr('isStatusNotSend', true))
            <div class="row justify-content-center mb-3">
                <button type="submit" id="draft" class="btn btn-02 col-md-3 col-sm-3 w-100">{{ $data->isNotEmpty ?  __('words.DraftOrUpdate') : __('words.DraftOrSave') }}</button>
            </div>
        @endif
        <div class="row justify-content-center mb-3">
            <button type="submit" id="preview" class="btn btn-02 col-md-3 col-sm-3 w-100">{{ __('words.Preview') }}</button>
        </div>
        @if($data->getAttr('isStatusNotSend', true))
            <div class="row justify-content-center mb-3">
                <button id="submit" type="submit" class="btn btn-success col-md-3 col-sm-3 w-100 text-white">{{ __('words.Send') }}</button>
            </div>
        @endif
        <div class="row justify-content-end mt-2 mb-3">
            <a href="{{ route('message.index') }}" class="btn btn-02">{{ __('words.BackToList') }}</a>
        </div>
    </form>
@endsection

