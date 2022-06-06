
@push('js')
    @php
        $routeDelete = '';
        $routeDraft = '';
        $routeUpload = route('message.upload');
        $routeDuplicate = route('message.store', [config('searchQuery.param.copy') => config('searchQuery.value.copyYes')]);
        $routeSaveAndSend = '';
        $confirmationModal = __('words.FinalConfirmation');
        $deleteConfirmationModal = __('words.DoYouWishToProceedDeletion');
        $duplicateConfirmationModal = __('words.DoYouWantToContinueDuplication');

        if ($data->isNotEmpty) {
            $routeDelete = route('message.destroy', $data->id);

            if ($data->isStatusNotSend) {
                $routeDraft = route('message.update', [
                    'id' => $data->id,
                    'draft' => 1
                ]);
                $routeSaveAndSend = route('message.update', [
                    'id' => $data->id,
                    'send' => 1
                ]);
            }
        } else {
            $routeDraft = route('message.store', [
                'draft' => 1
            ]);
            $routeSaveAndSend = route('message.store', [
                'send' => 1
            ]);
        }
    @endphp
    <script>
        'use strict';

        /*======================================================================
        * CONSTANTS
        *======================================================================*/

        const csvExtensionRegex = /({{ \Globals::implode(\Globals::CSV_ACCEPTEDEXTENSION, '|', '\\.') }})$/i;
        const imgExtensionRegex = /({{ \Globals::implode(\Globals::IMG_ACCEPTEDEXTENSION, '|', '\\.') }})$/i;

        /*======================================================================
        * VARIABLES
        *======================================================================*/

        let _l = {
            upload: function (inputFile, fileId, fileName, type, callback) {
                fileHandle.upload("{{ $routeUpload }}", inputFile, fileId, fileName, type, callback);
            },
            reloadPreview: function () {
                let data = $('#form').serializeArray();
                data = _g.form.toArray(data);
                let contents = data.contents;

                if (typeof data.contents == 'undefined') {
                    contents = $('.contents').html();
                }

                $('#contentPreview [role-name=thumbnail]').each(function () {
                    if ($.trim(data.thumbnail) == '') {
                        $(this).parents('.message').hide();
                    } else {
                        $(this).parents('.message').show();
                        $(this).attr('src', data.thumbnail);
                    }
                });

                $('#contentPreview [role-name=time]').each(function () {
                    $(this).html($.trim(data.sendTime));
                });

                $('#contentPreview [role-name=contents]').each(function () {
                    if ($.trim(contents) == '') {
                        $(this).parents('.message').hide();
                    } else {
                        $(this).parents('.message').show();

                        var text = contents;
                        text = text.replace(/\<\/p\>/g, '[bbbrrr-the-break]</p>');
                        text = $(text).text();
                        text = text.replace(/\[bbbrrr\-the\-break\]/g, '<br/>');
                        $(this).html(text);
                    }
                });
            },
            undoEdit: function () {
                _g.form.undoEdit('form', csvExtensionRegex, imgExtensionRegex);
            }
        };

        /*======================================================================
        * INITIALIZATION
        *======================================================================*/

        $(function () {
            $('#contents').trumbowyg({
                lang: 'ja',
                btnsDef: _g.trumbowyg.default.btnsDef,
                btns: [
                    ['emoji'],
                ],
            });

            $('#sendDate').datetimepicker({
                format: 'L',
                allowInputToggle: true,
            });

            $('#sendTime').datetimepicker({
                format: 'LT',
                allowInputToggle: true,
            });
        });

        /*======================================================================
        * DOM EVENTS
        *======================================================================*/

        $(function () {
            /** FORM **/
            $('form').submit(function() {
                _g.loading.show();
            });

            /** BUTTON DUPLICATE **/
            $('#duplicateBtn').click(function(e) {
                e.preventDefault();
                let callback = function () {
                    $('#form').attr('action', '{!! $routeDuplicate !!}');

                    if ($("#submit").length != 0) {
                        $("#submit").unbind('click').click();
                    } else {
                        $('#form').submit();
                    }
                }
                _g.modal.show('{{ $confirmationModal }}', '{{ $duplicateConfirmationModal }}', callback);
            });

            /** BUTTON DELETE **/
            $('#deleteBtn').click(function(e) {
                e.preventDefault();
                let callback = function () {
                    @if(!empty($routeDelete))
                        $('#form').attr('action', "{{ $routeDelete }}");
                        $("#submit").unbind('click').click();
                    @endif
                }
                _g.modal.show('{{ $confirmationModal }}', '{{ $deleteConfirmationModal}}', callback);
            });

            /** BUTTON CLEAR & UNDO **/
            $('#clearBtn, #undoEdit').click(function() {
                _l.undoEdit();
            });

            /** sendTargetFlg **/
            $('input[name="sendTargetFlg"]').change(function () {
                var val = $('input[name="sendTargetFlg"]:checked').val();

                @if($data->getAttr('isStatusNotSend', true))
                    $('input[name="csv"]').prop('disabled', val != '{{ \Globals::mMessage()::SENDTARGET_UNIONMEMBER }}');
                    $('select[name="utilizationBusiness[]"]').prop('disabled', val != '{{ \Globals::mMessage()::SENDTARGET_UB }}');
                    $('select[name="affiliationOffice[]"]').prop('disabled', val != '{{ \Globals::mMessage()::SENDTARGET_AO }}');
                    $('select[name="storeId"]').prop('disabled', val != '{{ \Globals::mMessage()::SENDTARGET_STORE }}');
                @endif

                var sendTargetFlgAddClass = val == '{{ \Globals::mMessage()::SENDTARGET_STORE }}' ? 'is-store' : (val == '{{ \Globals::mMessage()::SENDTARGET_AO }}' ? 'is-ao' : 'is-ub');
                var sendTargetFlgRemoveClass = sendTargetFlgAddClass == 'is-store' ? 'is-ao is-ub' : (sendTargetFlgAddClass == 'is-ao' ? 'is-ub is-store' : 'is-ao is-store');
                $('.ub-ao-store-box').addClass(sendTargetFlgAddClass).removeClass(sendTargetFlgRemoveClass);

                var targetUserValueBoxAddClass = '';
                var targetUserValueBoxRemoveClass = '';
                switch (val) {
                    case '{{ \Globals::mMessage()::SENDTARGET_UNIONMEMBER }}':
                        targetUserValueBoxAddClass = 'is-csv';
                        targetUserValueBoxRemoveClass = 'is-select-option';
                        break;
                    case '{{ \Globals::mMessage()::SENDTARGET_UB }}':
                        targetUserValueBoxAddClass = 'is-select-option';
                        targetUserValueBoxRemoveClass = 'is-csv';
                        break;
                    case '{{ \Globals::mMessage()::SENDTARGET_AO }}':
                        targetUserValueBoxAddClass = 'is-select-option';
                        targetUserValueBoxRemoveClass = 'is-csv';
                        break;
                    case '{{ \Globals::mMessage()::SENDTARGET_STORE }}':
                        targetUserValueBoxAddClass = 'is-select-option';
                        targetUserValueBoxRemoveClass = 'is-csv';
                        break;
                    default:
                        targetUserValueBoxRemoveClass = 'is-csv is-select-option';
                        break;
                }
                $('.target-user-value-box').addClass(targetUserValueBoxAddClass).removeClass(targetUserValueBoxRemoveClass);
            });

            /** csv **/
            $('input#csv').on('change',function (e) {
                _l.upload(this, e.target.id, 'unionMemberCsv', 'csv');
            });

            /** image **/
            $('input#sendImage').on('change',function (e) {
                _l.upload(this, e.target.id, 'thumbnail', 'image', function () {
                    _l.reloadPreview();
                });
            });

            /** BUTTON DRAFT **/
            $('#draft').click(function(e) {
                e.preventDefault();
                @if(!empty($routeDraft))
                    $('#form').attr('action', '{!! $routeDraft !!}');
                    $("#submit").unbind('click').click();
                @endif
            })

            /** BUTTON PREVIEW **/
            $("#preview").click(function (e) {
                e.preventDefault();
                _l.reloadPreview();
                $('#contentPreview').modal('show');
            });

            /** BUTTON SAVE & SEND **/
            $('#saveAndSendMessage').click(function(e) {
                e.preventDefault()
                @if(!empty($routeSaveAndSend))
                    $(this).parents('.modal').modal('hide');
                    $('#form').attr('action', "{{ $routeSaveAndSend }}");
                    $("#submit").unbind('click').click();
                @endif
            });
        });

        /*======================================================================
        * DOM INITIAL
        *======================================================================*/

        $(function () {
            var sendTargetFlg = $('input[name="sendTargetFlg"]:checked').val();

            if (!sendTargetFlg || typeof sendTargetFlg == 'undefined' || {{ $data->getAttr('isStatusSend', false) ? 1 : 0 }}) {
                $('input[name="csv"]').prop('disabled', true);
                $('select[name="utilizationBusiness[]"]').prop('disabled', true);
                $('select[name="affiliationOffice[]"]').prop('disabled', true);
                $('select[name="storeId"]').prop('disabled', true);
                $('.ub-ao-store-box').addClass('is-ub');
            } else {
                $('input[name="csv"]').prop('disabled', sendTargetFlg != '{{ \Globals::mMessage()::SENDTARGET_UNIONMEMBER }}');
                $('select[name="utilizationBusiness[]"]').prop('disabled', sendTargetFlg != '{{ \Globals::mMessage()::SENDTARGET_UB }}');
                $('select[name="affiliationOffice[]"]').prop('disabled', sendTargetFlg != '{{ \Globals::mMessage()::SENDTARGET_AO }}');
                $('select[name="storeId"]').prop('disabled', sendTargetFlg != '{{ \Globals::mMessage()::SENDTARGET_STORE }}');
            }

            var sendTargetFlgAddClass = sendTargetFlg == '{{ \Globals::mMessage()::SENDTARGET_STORE }}' ? 'is-store' : (sendTargetFlg == '{{ \Globals::mMessage()::SENDTARGET_AO }}' ? 'is-ao' : 'is-ub');
            var sendTargetFlgRemoveClass = sendTargetFlgAddClass == 'is-store' ? 'is-ao is-ub' : (sendTargetFlgAddClass == 'is-ao' ? 'is-ub is-store' : 'is-ao is-store');
            $('.ub-ao-store-box').addClass(sendTargetFlgAddClass).removeClass(sendTargetFlgRemoveClass);
            
            $('input[name="sendTargetFlg"]').change();
        });
    </script>
@endpush
