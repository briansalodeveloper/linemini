@php
    $whitespace = ' ';
    $add = [
        'class' => [
            'formGroup' => isset($customClass['formGroup']) ? $whitespace . $customClass['formGroup'] : '',
            'label' => isset($customClass['label']) ? 'class=' . $customClass['label'] : '',
            'input' => isset($customClass['input']) ?  $whitespace . $customClass['input']: '',
            'formCheck' => isset($customClass['formCheck']) ? $whitespace . $customClass['formCheck'] : '',
            'isInvalid' => $errors->has($field) ?  $whitespace . 'is-invalid' : ''
        ]
    ];
    $inputVal = isset($inputVal) && (!empty($inputVal) || (string) $inputVal === (string) 0) ? ("value='$inputVal'") : '';
    $placeholder = (isset($placeholder))  && !empty($placeholder) ? 'placeholder=' . $placeholder : '';
@endphp

@if(isset($isFieldOnly) ? !$isFieldOnly : true)
    <div class="form-group{{ $add['class']['formGroup'] }}">
        <label for="{{ $field }}" {!! $add['class']['label'] !!}>{{ $label }} {!! (isset($required) ? $required : false) ? '<req></req>' : '' !!}</label>
        {!! isset($subLabel) ? '<span>' . $subLabel . '</span>' : '' !!}
@endif
    @if(isset($inputContainer))
        {!! $inputContainer[0] !!}
    @endif
        {{-- INPUT TYPE: TEXT | NUMBER INPUT --}}
        @if($type == 'text' || $type == 'number')
            <input type="{{ $type }}" class="form-control{{ $add['class']['input'] }}{{ $add['class']['isInvalid'] }}" name="{{ $field }}" id="{{ $field }}"
                {!! $placeholder !!} {!! $inputVal !!} data-original-value="{{ $valDefault }}">
        {{-- INPUT TYPE: RADIO BUTTONS --}}
        @elseif($type == 'radio')
            @foreach($list as $key => $item)
                <div class="icheck-primary{{ $add['class']['isInvalid'] }}">
                    <input type="radio" id="{{ $field }}-{{ $loop->index }}" name="{{ $field }}" value="{{ $key }}" data-original-value="{{ $valDefault }}" {!! $valSelected == (string) $key ? 'checked' : '' !!}>
                    <label for="{{ $field }}-{{ $loop->index }}">{{ __($item) }}</label>
                </div>
            @endforeach
        {{-- INPUT TYPE: DATETIMEPICKER INPUT --}}
        @elseif($type == 'datetimepicker')
            <div class="input-group date" id="{{ $name }}" data-target-input="nearest">
                <input type="text" id="{{ $name }}Input" class="form-control {{ $type }}-input{{ $add['class']['isInvalid'] }}" name="{{ $name }}"
                {!! $placeholder !!} {!! $inputVal !!} data-original-value="{{ $valDefault }}" data-target="#{{ $name }}" autocomplete="off">
                <div class="input-group-append" data-target="#{{ $name }}" data-toggle="{{ $type }}">
                    <div class="input-group-text"><i class="{{ $icon }}"></i></div>
                </div>
                @if(isset($dspErrorMsg))
                    @include('common.validationError', ['key' => $field])
                @endif
            </div>
        {{-- INPUT TYPE: FILECUSTOMM INPUT --}}
        @elseif($type == 'fileCustom')
            @include('common.input.fileCustom', [
                'id' => $id,
                'name' => $name,
                'classContainer' => $classContainer,
                'accept' => $extAllowed,
                'label' => $customlabel,
                'hiddenName' => $field,
                'hiddenValue' => $hiddenValue,
                'disabled' => $disabled,
                'originalValue' => $originVal
            ])
            <span id="{{ $id }}-error" class="error invalid-feedback">{{ $errors->first($field) }}</span>
        {{-- INPUT TYPE: SELECTION INPUT --}}
        @elseif($type == 'selection')
            <select name="{{ $field }}" class="form-control{{ $add['class']['input'] }} {{ $add['class']['isInvalid'] }}"{!! isset($customAttr) ? ' ' . $customAttr : '' !!}>
                <option disabled>{{ $defaultItem }}</option>
                @foreach( $list as $key => $item )
                    <option value="{{ $key }}"{!! (is_array($valSelected) ? (in_array($key, $valSelected)) : ($valSelected == $key)) ? 'selected' : '' !!}>{{ \Globals::__($item) }}</option>
                @endforeach
            </select>
        {{-- INPUT TYPE: CHECKBOX INPUT --}}
        @elseif($type == 'checkBox')
            @if(count($list) >= 6)
                <div class="row{{ $add['class']['isInvalid'] }}">
                    @php $listCopy = array_slice($list, 0, 4); @endphp
                    @foreach($listCopy as $key => $item)
                        <div class="col-md-2 icheck-primary{{ $add['class']['isInvalid'] }}{{ $add['class']['formCheck'] }}" data-original-value="{{ $valDefault }}" >
                            <input type="{{ $type }}" id="{{ $field }}{{ $key }}" name="{{ $field }}[]" value="{{ $key }}"
                                {!! in_array((string) $key, $valsSelected) ? 'checked' : '' !!}>
                            <label for="{{ $field }}{{ $key }}">{{ __($item) }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="row{{ $add['class']['isInvalid'] }}">
                    @php $listCopy = array_slice($list, 4); @endphp
                    @foreach($listCopy as $key => $item)
                        <div class="col-md-2 icheck-primary{{ $add['class']['isInvalid'] }}{{ $add['class']['formCheck'] }}" data-original-value="{{ $valDefault }}" >
                            <input type="{{ $type }}" id="{{ $field }}{{ $key }}" name="{{ $field }}[]" value="{{ $key }}"
                                {!! in_array((string) $key, $valsSelected) ? 'checked' : '' !!}>
                            <label for="{{ $field }}{{ $key }}">{{ __($item) }}</label>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row{{ $add['class']['isInvalid'] }}">
                    @foreach($list as $key => $item)
                        <div class="col icheck-primary{{ $add['class']['isInvalid'] }}{{ $add['class']['formCheck'] }}" data-original-value="{{ $valDefault }}" >
                            <input type="{{ $type }}" id="{{ $field }}{{ $key }}" name="{{ $field }}[]" value="{{ $key }}"
                                {!! in_array((string) $key, $valsSelected) ? 'checked' : '' !!}>
                            <label for="{{ $field }}{{ $key }}">{{ __($item) }}</label>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        @if(!in_array($type,['datetimepicker','fileCustom']))
            @php
                if (strpos($field, '[]') !== false) {
                    $field = explode('[]', $field)[0];
                }
            @endphp
            @include('common.validationError', ['key' => $field])
        @endif

    @if(isset($inputContainer))
        {!! $inputContainer[1] !!}
    @endif
@if(isset($isFieldOnly) ? !$isFieldOnly : true)
    </div>
@endif
