@push('js')
    @php
        $routeTrumbowyg = '';
        $routeUpload = '';
        $routeDuplicate = '';
        $routeDelete = '';
        $confirmationModal = __('words.FinalConfirmation');
        $deleteConfirmationModal = __('words.DoYouWishToProceedDeletion');
        $duplicateConfirmationModal = __('words.DoYouWantToContinueDuplication');

        if ($contentType == Globals::mContentPlan()::CONTENTTYPE_NOTICE) {
            $routeTrumbowyg = route("notice.uploadTrumbowygImage");
            $routeUpload = route('notice.upload');
            $routeDuplicate = route('notice.store', [config('searchQuery.param.copy') => config('searchQuery.value.copyYes')]);

            if ($data->isNotEmpty) {
                $routeDelete = route('notice.destroy', $data->id);
            }
        } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_RECIPE) {
            $routeTrumbowyg = route("recipe.uploadTrumbowygImage");
            $routeUpload = route('recipe.upload');
            $routeDuplicate = route('recipe.store', [config('searchQuery.param.copy') => config('searchQuery.value.copyYes')]);

            if ($data->isNotEmpty) {
                $routeDelete = route('recipe.destroy', $data->id);
            }
        } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_PRODUCTINFO) {
            $routeTrumbowyg = route("productInformation.uploadTrumbowygImage");
            $routeUpload = route('productInformation.upload');
            $routeDuplicate = route('productInformation.store', [config('searchQuery.param.copy') => config('searchQuery.value.copyYes')]);

            if ($data->isNotEmpty) {
                $routeDelete = route('productInformation.destroy', $data->id);
            }
        } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_COLUMN) {
            $routeTrumbowyg = route("column.uploadTrumbowygImage");
            $routeUpload = route('column.upload');
            $routeDuplicate = route('column.store', [config('searchQuery.param.copy') => config('searchQuery.value.copyYes')]);

            if ($data->isNotEmpty) {
                $routeDelete = route('column.destroy', $data->id);
            }
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
                let startDateTime = '';
                data = _g.form.toArray(data);

                if (typeof data.startDate != 'undefined' && $.trim(data.startDate) != '') {
                    startDateTime = new Date(data.startDate);
                    startDateTime = startDateTime.getFullYear() + '年' + (startDateTime.getMonth() + 1) + '月' + startDateTime.getDate() + '日配信';
                }

                if ($.trim(data.openingImg) == '') {
                    $('#contentPreview [role-name=opening-image]').hide();
                } else {
                    $('#contentPreview [role-name=opening-image]').show();
                    $('#contentPreview [role-name=opening-image]').each(function () {
                        $(this).attr('src', data.openingImg);
                    });
                }

                $('#contentPreview [role-name=opening-letter]').each(function () {
                    $(this).html(data.openingLetter);
                });

                $('#contentPreview [role-name=start-datetime]').each(function () {
                    $(this).html(startDateTime);
                });

                $('#contentPreview [role-name=contents]').each(function () {
                    $(this).html(data.contents);
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
                btns: _g.trumbowyg.default.btns,
                plugins: {
                    upload: {
                        serverPath: '{{ $routeTrumbowyg }}',
                        fileFieldName: 'image',
                        data: [_g.trumbowyg.uploadDataToken()],
                        urlPropertyName: 'url',
                        error: function (response) {
                            toastr.error(response.responseJSON.errors.image);
                        }
                    }
                }
            });

            $('#startDate, #endDate').datetimepicker({
                format: 'L',
                allowInputToggle: true,
            });

            $('#startTime, #endTime').datetimepicker({
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
                    $('#form').attr('action', '{{ $routeDuplicate }}');
                    $("#submit").unbind('click').click();
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

            /** selectPublicationDateTime **/
            $('input[name="selectPublicationDateTime"]').change(function () {
                var val = $('input[name="selectPublicationDateTime"]:checked').val();

                if (typeof val == 'undefined') {
                    $('input[name="startDate"], input[name="startTime"], input[name="endDate"], input[name="endTime"]').prop('readonly', true);
                } else if (val == 0) {
                    var dateTime = _g.dateTime.now();

                    $('input[name="startDate"]').val(dateTime.date);
                    $('input[name="startTime"]').val(dateTime.time);
                    $('input[id="startDateInput"], input[id="startTimeInput"]').prop('readonly', true);
                    $('input[name="endDate"], input[name="endTime"]').prop('readonly', false);
                } else {
                    $('input[name="startDate"], input[name="startTime"]').val('');
                    $('input[name="endDate"], input[name="endTime"]').val('');
                    $('input[name="startDate"], input[name="startTime"], input[name="endDate"], input[name="endTime"]').prop('readonly', false);
                }
            });

            /** displayTargetFlg **/
            $('input[name="displayTargetFlg"]').change(function () {
                var val = $('input[name="displayTargetFlg"]:checked').val();

                $('input[name="csv"]').prop('disabled', val != '{{ \Globals::mContentPlan()::DSPTARGET_UNIONMEMBER }}');
                $('select[name="utilizationBusiness[]"]').prop('disabled', val != '{{ \Globals::mContentPlan()::DSPTARGET_UB }}');
                $('select[name="affiliationOffice[]"]').prop('disabled', val != '{{ \Globals::mContentPlan()::DSPTARGET_AO }}');

                var dspTrgtFlgAddClass = val == '{{ \Globals::mContentPlan()::DSPTARGET_AO }}' ? 'is-ao' : 'is-ub';
                var dspTrgtFlgRemoveClass = dspTrgtFlgAddClass == 'is-ao' ? 'is-ub' : 'is-ao';
                $('.ub-ao-box').addClass(dspTrgtFlgAddClass).removeClass(dspTrgtFlgRemoveClass);

                var targetUserValueBoxAddClass = '';
                var targetUserValueBoxRemoveClass = '';
                switch (val) {
                    case '{{ \Globals::mContentPlan()::DSPTARGET_UNIONMEMBER }}':
                        targetUserValueBoxAddClass = 'is-csv';
                        targetUserValueBoxRemoveClass = 'is-select-option';
                        break;
                    case '{{ \Globals::mContentPlan()::DSPTARGET_UB }}':
                        targetUserValueBoxAddClass = 'is-select-option';
                        targetUserValueBoxRemoveClass = 'is-csv';
                        break;
                    case '{{ \Globals::mContentPlan()::DSPTARGET_AO }}':
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
            $(`input#topImage`).on('change', function (e) {
                _l.upload(this, e.target.id, 'openingImg', 'image', function () {
                    _l.reloadPreview();
                });
            });

            /** BUTTON PREVIEW **/
            $("#preview").click(function (e) {
                e.preventDefault();
                _l.reloadPreview();
                $('#contentPreview').modal('show');
            });
        });

        /*======================================================================
        * DOM INITIAL
        *======================================================================*/

        $(function () {
            var displayTargetFlg = $('input[name="displayTargetFlg"]:checked').val();

            if (!displayTargetFlg || typeof displayTargetFlg == 'undefined') {
                $('input[name="csv"]').prop('disabled', true);
                $('select[name="utilizationBusiness[]"]').prop('disabled', true);
                $('select[name="affiliationOffice[]"]').prop('disabled', true);
                $('.ub-ao-box').addClass('is-ub');
            } else {
                $('input[name="csv"]').prop('disabled', displayTargetFlg != '{{ \Globals::mContentPlan()::DSPTARGET_UNIONMEMBER }}');
                $('select[name="utilizationBusiness[]"]').prop('disabled', displayTargetFlg != '{{ \Globals::mContentPlan()::DSPTARGET_UB }}');
                $('select[name="affiliationOffice"]').prop('disabled', displayTargetFlg != '{{ \Globals::mContentPlan()::DSPTARGET_AO }}');

                var dspTrgtFlgAddClass = displayTargetFlg == '{{ \Globals::mContentPlan()::DSPTARGET_AO }}' ? 'is-ao' : 'is-ub';
                var dspTrgtFlgRemoveClass = dspTrgtFlgAddClass == 'is-ao' ? 'is-ub' : 'is-ao';
                $('.ub-ao-box').addClass(dspTrgtFlgAddClass).removeClass(dspTrgtFlgRemoveClass);
            }

            if ($('[name=selectPublicationDateTime]:checked').val() == 0) {
                $('input[name="startDate"], input[name="startTime"]').prop('readonly', true);
                $('input[name="endDate"], input[name="endTime"]').prop('readonly', false);
            } else if ($('[name=selectPublicationDateTime]:checked').val() == 1) {
                $('input[name="startDate"], input[name="startTime"], input[name="endDate"], input[name="endTime"]').prop('readonly', false);
            } else {
                $('input[name="startDate"], input[name="startTime"], input[name="endDate"], input[name="endTime"]').prop('readonly', true);
            }

            $('input[name="displayTargetFlg"]').change();
        });
    </script>
@endpush
