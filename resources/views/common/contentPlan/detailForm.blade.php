@php
    $route = '';
    $routeList = '';

    if ($contentType == Globals::mContentPlan()::CONTENTTYPE_NOTICE) {
        $routeList = route('notice.index');
        $route = $data->isEmpty ? route('notice.store') : route('notice.update', $data->id);
    } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_RECIPE) {
        $routeList = route('recipe.index');
        $route = $data->isEmpty ? route('recipe.store') : route('recipe.update', $data->id);
    } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_PRODUCTINFO) {
        $routeList = route('productInformation.index');
        $route = $data->isEmpty ? route('productInformation.store') : route('productInformation.update', $data->id);
    } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_COLUMN) {
        $routeList = route('column.index');
        $route = $data->isEmpty ? route('column.store') : route('column.update', $data->id);
    }

    $csvLabel = '';
    $csvUrl = old('unionMemberCsv');
    $logo = '';
    $thumbnailLabel = '';
    $thumbnailUrl = old('openingImg', $data->getAttr('openingImg'));
    $thumbnailUrl = empty($thumbnailUrl) ? '' : $thumbnailUrl;

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

    if (old('openingImg', null) == null) {
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
@endphp

<form method="POST" enctype="multipart/form-data" id="form" action="{{ $route }}">
    @csrf

    @if($data->isNotEmpty)
        <input type="text" hidden name="contentPlanId" value="{{ $data->id }}">

        @if(count($data->displayTargetContentIdList) != 0)
            <input type="text" hidden name="displayTargetContent" value="{{ implode(',', $data->displayTargetContentIdList) }}">
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
        <label for="openingLetter">{{ __('words.Title') }} <req></req></label>
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control @if($errors->has('openingLetter')) is-invalid @endif" name="openingLetter" id="openingLetter" placeholder="{{ __('words.Title') }}" value="{{ old('openingLetter', $data->getAttr('openingLetter')) }}" data-original-value="{{ $data->getAttr('openingLetter') }}">
                @include('common.validationError', ['key' => 'openingLetter'])
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>{{ __('words.SelectPublicationDateTime') }} @if($data->isEmpty)<req></req>@endif</label>
        <div class="col-12{{ $errors->has('selectPublicationDateTime') ? ' is-invalid' : '' }}">
            <div class="icheck-primary">
                <input type="radio" id="publishType1"  value="0" name="selectPublicationDateTime" {!! old('selectPublicationDateTime') == '0' ? 'checked': ''!!} data-original-value>
                <label for="publishType1">{{ $data->isEmpty ? __('words.PublishSoon') : __('words.UpdateSoon') }}</label>
            </div>
            <div class="icheck-primary">
                <input type="radio" id="publishType2"  value="1" name="selectPublicationDateTime"  {!! old('selectPublicationDateTime') == '1' ? 'checked': ''!!} data-original-value>
                <label for="publishType2">{{ $data->isEmpty ? __('words.BookAndPublish') : __('words.BookAndRenew') }}</label>
            </div>
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
                <div class="form-group col-3">
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
                <div class="form-group col-3">
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
                <div class="form-group col-3">
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
                <div class="form-group col-3">
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
        <label>{{ __('words.DisplayToTopPage') }} <req></req></label>
        <div class="icheck-primary">
            <input type="radio" id="contentTypeNews1" value="{{ \Globals::mContentPlan()::CONTENTTYPENEWS_NOTTOP }}"
                name="contentTypeNews" {!! old('contentTypeNews', $data->getAttr('contentTypeNews', '-1')) == \Globals::mContentPlan()::CONTENTTYPENEWS_NOTTOP ? 'checked': '' !!}
                data-original-value="{{ $data->getAttr('contentTypeNews') }}">
            <label for="contentTypeNews1">{{ __('words.DontDisplayOnTopPage') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="contentTypeNews2" value="{{ \Globals::mContentPlan()::CONTENTTYPENEWS_NOTIFICATIONAREA }}"
                name="contentTypeNews" {!! old('contentTypeNews', $data->getAttr('contentTypeNews', '-1')) == \Globals::mContentPlan()::CONTENTTYPENEWS_NOTIFICATIONAREA ? 'checked': '' !!}
                data-original-value="{{ $data->getAttr('contentTypeNews') }}">
            <label for="contentTypeNews2">{{ __('words.DisplayOnNotification') }}</label>
        </div>
        <div class="icheck-primary{{ $errors->has('contentTypeNews') ? ' is-invalid' : '' }}">
            <input type="radio" id="contentTypeNews3" value="{{ \Globals::mContentPlan()::CONTENTTYPENEWS_DEALSAREA }}"
                name="contentTypeNews" {!!  old('contentTypeNews', $data->getAttr('contentTypeNews', '-1')) == \Globals::mContentPlan()::CONTENTTYPENEWS_DEALSAREA ? 'checked': '' !!}
                data-original-value="{{ $data->getAttr('contentTypeNews') }}">
            <label for="contentTypeNews3">{{ __('words.DisplayOnDeals') }}</label>
        </div>
        @include('common.validationError', ['key' => 'contentTypeNews'])
    </div>
    <div class="form-group">
        <label>{{ __('words.UnionMemberToDisplay') }} <req></req></label>
        <div class="icheck-primary">
            <input type="radio" id="displayTargetFlg1" value="{{ \Globals::mContentPlan()::DSPTARGET_UNCONDITIONAL }}"
                name="displayTargetFlg" {!! old('displayTargetFlg', $data->getAttr('displayTargetFlg', '-1')) == \Globals::mContentPlan()::DSPTARGET_UNCONDITIONAL ? 'checked': '' !!}
                data-original-value="{{ $data->getAttr('displayTargetFlg') }}">
            <label for="displayTargetFlg1">{{ __('words.Unconditional') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="displayTargetFlg2" value="{{ \Globals::mContentPlan()::DSPTARGET_UNIONMEMBER }}"
                name="displayTargetFlg" {!! old('displayTargetFlg', $data->getAttr('displayTargetFlg', '-1')) == \Globals::mContentPlan()::DSPTARGET_UNIONMEMBER ? 'checked': '' !!}
                data-original-value="{{ $data->getAttr('displayTargetFlg') }}">
            <label for="displayTargetFlg2">{{ __('words.UnionMemberDesignation') }}</label>
        </div>
        <div class="icheck-primary">
            <input type="radio" id="displayTargetFlg3" value="{{ \Globals::mContentPlan()::DSPTARGET_UB }}"
                name="displayTargetFlg" {!! old('displayTargetFlg', $data->getAttr('displayTargetFlg', '-1')) == \Globals::mContentPlan()::DSPTARGET_UB ? 'checked': '' !!}
                data-original-value="{{ $data->getAttr('displayTargetFlg') }}">
            <label for="displayTargetFlg3">{{ __('words.UserBusinessDesignation') }}</label>
        </div>
        <div class="icheck-primary{{ $errors->has('displayTargetFlg') ? ' is-invalid' : '' }}">
            <input type="radio" id="displayTargetFlg4" value="{{ \Globals::mContentPlan()::DSPTARGET_AO }}"
                name="displayTargetFlg" {!! old('displayTargetFlg', $data->getAttr('displayTargetFlg', '-1')) == \Globals::mContentPlan()::DSPTARGET_AO ? 'checked': '' !!}
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
                'disabled' => old('displayTargetFlg', $data->getAttr('displayTargetFlg')) != 1,
                'originalValue' => '',
            ])
            <span id="csv-error" class="error invalid-feedback">{{ $errors->first('unionMemberCsv') }}</span>
        </div>
        <div class="form-group ub-ao-box target-select-option">
            <label>{{ __('messages.custom.specifyAoOrUb') }} <req></req></label>
            <div class="row">
                <div class="col-md-6{{ $errors->has('utilizationBusiness') || $errors->has('affiliationOffice') ? ' is-invalid' : '' }}">
                    <select class="ub-list form-control" name="utilizationBusiness[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.BusinessSelection') }}" data-original-value="{{ implode(',', $data->getAttr('displayTargetContentUbUtilizationBusinessIdList', [])) }}">
                        <option value="" disabled>{{ __('words.BusinessSelection') }}</option>
                        @foreach($listUb as $key => $val)
                            <option value="{{ $key }}"{!! in_array($key, old('utilizationBusiness', $data->getAttr('displayTargetContentUbUtilizationBusinessIdList', []))) ?  ' selected': '' !!}>{{ \Globals::__($val) }}</option>
                        @endforeach
                    </select>
                    <select class="ao-list form-control" name="affiliationOffice[]" multiple="multiple" auto-init-select2 data-placeholder="{{ __('words.AffiliateOffice') }}" data-original-value="{{ implode(',', $data->getAttr('displayTargetContentAoAffiliationOfficeIdList', [])) }}">
                        <option value="" disabled>{{ __('words.AffiliateOffice') }}</option>
                        @foreach($listAo as $key => $val)
                            <option value="{{ $key }}" {!! in_array($key, old('affiliationOffice', $data->getAttr('displayTargetContentAoAffiliationOfficeIdList', []))) ?  'selected': '' !!}>{{ \Globals::__($val) }}</option>
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
                'interface' => \Globals::iContent(),
                'dTMethodName' => 'displayTargetContent'
            ]])
        @endif
    </div>
    <div class="form-group">
        <p><b>{{ __('words.TopImg') }} <req></req></b><span class="text-xs ml-4">ファイルサイズは2M以下、形式はJPEG or PNG のみとなります。</span></p>
        @include('common.input.fileCustom', [
            'id' => 'topImage',
            'name' => 'topImage',
            'classContainer' => 'topImage' . ($errors->has('openingImg') ? ' is-invalid' : ''),
            'accept' => \Globals::implode(\Globals::IMG_ACCEPTEDEXTENSION, ',', '.'),
            'label' => $thumbnailLabel,
            'hiddenName' => 'openingImg',
            'hiddenValue' => $thumbnailUrl,
            'originalValue' => $data->getAttr('openingImg')
        ])
        <span id="topImage-error" class="error invalid-feedback">{{ $errors->first('openingImg') }}</span>
    </div>

    <div class="form-group">
        <p><b>{{ __('words.BodyPostedContent') }}</b></p>
        <textarea id="contentsOriginalValue" hidden>{{ $data->getAttr('contents') }}</textarea>
        <textarea id="contents" name="contents" data-original-value="#contentsOriginalValue">{!! old('contents', $data->getAttr('contents')) !!}</textarea>
    </div>
    <div class="row justify-content-center mb-3">
        <button type="button" id="preview" class="btn btn-02 col-md-3 col-sm-3 w-100">{{ __('words.Preview') }}</button>
    </div>
    <div class="row justify-content-center mb-3">
        <button id="submit" type="submit" class="btn btn-warning col-md-3 col-sm-3 w-100 text-white">{{ __('words.Post') }}</button>
    </div>
    <div class="row justify-content-end mt-2 mb-3">
        <a href="{{ $routeList }}" class="btn btn-02">{{ __('words.BackToList') }}</a>
    </div>
</form>
