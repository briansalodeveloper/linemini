@php
    $routeUpload = '';
    $routeDuplicate = '';
    $routeDelete = '';
    $routeUpload = route('flyer.upload');
    $routeDuplicate = route('flyer.store', [config('searchQuery.param.copy') => config('searchQuery.value.copyYes')]);
    $confirmationModal = __('words.FinalConfirmation');
    $deleteConfirmationModal = __('words.DoYouWishToProceedDeletion');
    $duplicateConfirmationModal = __('words.DoYouWantToContinueDuplication');
    
    if ($data->isNotEmpty) {
        $routeDelete = route('flyer.destroy', $data->id);
    }
@endphp
@push('js')
    <script>
        'use strict';

        /*======================================================================
        * CONSTANTS
        *======================================================================*/

        const csvExtensionRegex = /({{ \Globals::implode(\Globals::CSV_ACCEPTEDEXTENSION, '|', '\\.') }})$/i;
        const flyerImgExtensionRegex = /({{ \Globals::implode(\Globals::IMG_ACCEPTEDEXTENSION, '|', '\\.') }})$/i;

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

                if (typeof data.startDate != 'undefined') {
                    startDateTime = new Date(data.startDate);
                    startDateTime = startDateTime.getFullYear() + '年' + (startDateTime.getMonth() + 1) + '月' + startDateTime.getDate() + '日配信';
                }

                if ($.trim(data.flyerImg) == '') {
                    $('#flyerPreview [role-name=flyerImg]').hide();
                } else {
                    $('#flyerPreview [role-name=flyerImg]').show();
                    $('#flyerPreview [role-name=flyerImg]').each(function () {
                        $(this).attr('src', data.flyerImg);
                    });
                }

                if ($.trim(data.flyerUraImg) == '') {
                    $('#flyerPreview [role-name=flyerUraImg]').hide();
                } else {
                    $('#flyerPreview [role-name=flyerUraImg]').show();
                    $('#flyerPreview [role-name=flyerUraImg]').each(function () {
                        $(this).attr('src', data.flyerUraImg);
                    });
                }

                $('#flyerPreview [role-name=title]').each(function () {
                    $(this).html(data.flyerName);
                });
            },
            undoEdit: function () {
                _g.form.undoEdit('form', csvExtensionRegex, flyerImgExtensionRegex);
            }
        };

        /*======================================================================
        * INITIALIZATION
        *======================================================================*/

        $(function () {
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
            $('input[name="selectPublicationDateTime"]').click(function () {
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

                $('input[name="csv"]').prop('disabled', val != '{{ \Globals::mFlyerPlan()::DSPTARGET_UNIONMEMBER }}');
                $('select[name="utilizationBusiness[]"]').prop('disabled', val != '{{ \Globals::mFlyerPlan()::DSPTARGET_UB }}');
                $('select[name="affiliationOffice[]"]').prop('disabled', val != '{{ \Globals::mFlyerPlan()::DSPTARGET_AO }}');

                var dspTrgtFlgAddClass = val == '{{ \Globals::mFlyerPlan()::DSPTARGET_AO }}' ? 'is-ao' : 'is-ub';
                var dspTrgtFlgRemoveClass = dspTrgtFlgAddClass == 'is-ao' ? 'is-ub' : 'is-ao';
                $('.ub-ao-box').addClass(dspTrgtFlgAddClass).removeClass(dspTrgtFlgRemoveClass);

                var targetUserValueBoxAddClass = '';
                var targetUserValueBoxRemoveClass = '';
                switch (val) {
                    case '{{ \Globals::mFlyerPlan()::DSPTARGET_UNIONMEMBER }}':
                        targetUserValueBoxAddClass = 'is-csv';
                        targetUserValueBoxRemoveClass = 'is-select-option';
                        break;
                    case '{{ \Globals::mFlyerPlan()::DSPTARGET_UB }}':
                        targetUserValueBoxAddClass = 'is-select-option';
                        targetUserValueBoxRemoveClass = 'is-csv';
                        break;
                    case '{{ \Globals::mFlyerPlan()::DSPTARGET_AO }}':
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

            /** flyerImageFront **/
            $('input#flyerImageFront').on('change',function (e) {
                _l.upload(this, e.target.id, 'flyerImg', 'image', function () {
                    _l.reloadPreview();
                });
            });

            /** flyerImageBack **/
            $('input#flyerImageBack').on('change',function (e) {
                _l.upload(this, e.target.id, 'flyerUraImg', 'image', function () {
                    _l.reloadPreview();
                });
            });

            /** BUTTON PREVIEW **/
            $("#preview").click(function (e) {
                e.preventDefault();
                _l.reloadPreview();
                $('#flyerPreview').modal('show');
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
                $('input[name="csv"]').prop('disabled', displayTargetFlg != '{{ \Globals::mFlyerPlan()::DSPTARGET_UNIONMEMBER }}');
                $('select[name="utilizationBusiness[]"]').prop('disabled', displayTargetFlg != '{{ \Globals::mFlyerPlan()::DSPTARGET_UB }}');
                $('select[name="affiliationOffice"]').prop('disabled', displayTargetFlg != '{{ \Globals::mFlyerPlan()::DSPTARGET_AO }}');

                var dspTrgtFlgAddClass = displayTargetFlg == '{{ \Globals::mFlyerPlan()::DSPTARGET_AO }}' ? 'is-ao' : 'is-ub';
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
