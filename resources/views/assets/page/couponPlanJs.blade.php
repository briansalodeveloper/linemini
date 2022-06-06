@push('js')
    @php
        /** ROUTE **/
        $routeTrumbowyg = route("coupon.uploadTrumbowygImage");;
        $routeUpload = route('coupon.upload');
        $routeDuplicate =  route('coupon.store', [config('searchQuery.param.copy') => config('searchQuery.value.copyYes')]);
        $routeDelete =  route('coupon.destroy', $data->id);

        /** WORDS **/
        $confirmationModal = __('words.FinalConfirmation');
        $deleteConfirmationModal = __('words.DoYouWishToProceedDeletion');
        $duplicateConfirmationModal = __('words.DoYouWantToContinueDuplication');

        /** CONSTANT **/
        $csvExtensionRegex = \Globals::implode($listCsvAcceptedExtension, '|', '\\.');
        $imgExtensionRegex = \Globals::implode($listImageAcceptedExtension, '|', '\\.');
        $thmbnailExtension = \Globals::implode($listImageAcceptedExtension, ', ');
        $dspTargetUnionCode = \Globals::mCouponPlan()::DSPTARGET_UNIONMEMBER;
        $dspTargetUBCode = \Globals::mCouponPlan()::DSPTARGET_UB;
        $dspTargetAOCode = \Globals::mCouponPlan()::DSPTARGET_AO;
        $codeAmnt = \Globals::mCouponPlan()::CODE_AMOUNT;
        $codePurchase = \Globals::mCouponPlan()::CODE_PURCHASENUM;
        $codePointsAwarded = \Globals::mCouponPlan()::CODE_POINTSAWARDED;
        $productDesignationCode = \Globals::mCouponPlan()::CODE_PRODUCTDESIGNATION;
        $categoryDesignationCode = \Globals::mCouponPlan()::CODE_CATEGORYDESIGNATION;
        $storeFlgCodeUnspecified = \Globals::mCouponPlan()::CODE_NOTSPECIFIED;
        $storeFlgCodeSpecified = \Globals::mCouponPlan()::CODE_SPECIFIED;
    @endphp
    <script>
        'use strict';

        /*======================================================================
        * VARIABLES
        *======================================================================*/

        let _l = {
            el: {
                body: 'body',
                wrapper: '.wrapper',
                spinnerOverlay: '#spinner-overlay',
                form: '#form',
                btnDuplicate: '#duplicateBtn',
                btnSubmit: 'form #submit',
                btnUndo: '#clearBtn, #undoEdit',
                btnDelete: '#deleteBtn',
                btnPreview: '#preview',
                fieldContent: '#cuponText',
                cuponImgUploadTrigger: '#cuponImgTrigger',
                contentPreview: '#couponPreview',
                fieldDates: '#startDate, #endDate',
                fieldTimes: '#startTime, #endTime',
                startTime: '#startTime',
                endTime: '#endTime',
                fileThmbnail: '.file-thumbnail',
                fileCSV: '.file-csv',
                ubAoBox: '.ub-ao-box',
                fileType: '#fileType',
                targetCouponValBox: '.target-coupon-value-box',
                targetUserValBox: '.target-user-value-box'
            },
            route: {
                delete: '{{ $routeDelete }}',
                duplicate: '{{ $routeDuplicate }}',
                trumbowyg: '{{ $routeTrumbowyg }}',
                upload: '{{ $routeUpload }}',
            },
            text: {
                confirmationModal: '{{ $confirmationModal }}',
                deleteConfirmationModal: '{{ $deleteConfirmationModal }}',
                duplicateConfirmationModal: '{{ $duplicateConfirmationModal }}',
            },
            constant: {
                csvExtensionRegex: /({{ $csvExtensionRegex }})$/i,
                imgExtensionRegex: /({{ $imgExtensionRegex }})$/i,
                thumbnailExtension: /({{ $thmbnailExtension }})$/i,
                dspTargetUnionCode: '{{ $dspTargetUnionCode }}',
                dspTargetUBCode: '{{ $dspTargetUBCode }}',
                dspTargetAOCode: '{{ $dspTargetAOCode }}',
                codeAmnt: '{{ $codeAmnt }}',
                codePurchase: '{{ $codePurchase }}',
                codePointsAwarded: '{{ $codePointsAwarded }}',
                productDesignationCode: '{{ $productDesignationCode }}',
                categoryDesignationCode: '{{ $categoryDesignationCode }}',
                storeFlgCodeUnspecified: '{{ $storeFlgCodeUnspecified }}',
                storeFlgCodeSpecified: '{{ $storeFlgCodeSpecified }}',
            },
            elOther: {
                cuponImgInputNameHidden: 'cuponImg',
                unionMemberCSVUploadTrigger: 'unionMemberCsvTrigger',
                unionMemberCSVInputName: 'unionMemberCsv',
                cuponDisplayFlgInputName: 'cuponDisplayFlg',
                pointGrantFlgInputName: 'pointGrantFlg',
                openingImg: 'opening-image',
                endDatePreview: 'end-date',
                cuponName: 'cupon-name',
                useLimit: 'use-limit',
                grantPoint: 'grant-point',
                utilBusiness: 'utilizationBusiness',
                affiliationOffice: 'affiliationOffice',
                increaseFlgInputName: 'increaseFlg',
                pointGrantPurchasesPriceInputName: 'pointGrantPurchasesPrice',
                pointGrantPurchasesCountInputName: 'pointGrantPurchasesCount',
                grantPointInputName: 'grantPoint',
                uploadNameRegex: /([^#]).*(?=trigger)/i,
                storesInputName: 'stores[]',
                couponText: 'coupon-text',
                cuponTypeInputName: 'cuponType',
                priorityDspFlgInputName: 'priorityDisplayFlg',
                autoEntryFlgInputName: 'autoEntryFlg',
                productFlg: 'productFlg',
                prodCodeCsvUploadTrigger: 'specifiedProdCodeCsvTrigger',
                prodCodeCsvInputName: 'specifiedProdCodeCsv',
                prodCategoryCsvUploadTrigger: 'prodCategoryCsvTrigger',
                prodCategoryCsvInputName: 'prodCategoryCsv',
                isCsvProdcode: 'is-csv-prodcode',
                isCsvProdCategory: 'is-csv-prodcategory',
                storeFlgInputName: 'storeFlg'
            },
            props: {
                disabled: 'disabled',
                readonly: 'readonly',
                checked: 'checked'
            },
            message: {
                errMsgUploadFile: '{{ __('messages.custom.failedUpload') }}'
            },
            style: {
                successColor: 'green',
                errColor: 'red',
                halfOpacity: .5,
                inlineBlock: 'inline-block',
                none: 'none'
            },
            input: {
                name: {
                    selectPublicationDateTime: 'selectPublicationDateTime',
                    startDate: 'startDate',
                    startTime: 'startTime',
                    endDate: 'endDate',
                    endTime: 'endTime'
                },
                id: {
                    startDateInput: 'startDateInput',
                    startTimeInput: 'startTimeInput'
                }
            },
            lang: {
                japanese: 'ja'
            },
            value: {
                type: {
                    undefined: 'undefined'
                },
                csv: 'csv',
                thmbnail: 'thumbnail'
            },
            class: {
                isCsv: 'is-csv',
                isUb: 'is-ub',
                isAo: 'is-ao',
                isSelectOption: 'is-select-option'
            },
            errClass: '-error',
            maxMbSize: 2,
            onekilobyte: 1024,
            thumbailUploadClass: 'thumbnail-uploading',
            uploadingClass: 'uploading',
            img : 'thumbnail',
            upload: function (inputFile, fileId, fileName, type, callback) {
                fileHandle.upload("{{ $routeUpload }}", inputFile, fileId, fileName, type, callback);
            },
            reloadPreview: function () {
                let data = _l.elForm.serializeArray();
                let endDateTime = '';
                data = _g.form.toArray(data);
                fullYear = '{{ __('words.Dates.Year') }}';
                month = '{{ __('words.Dates.Month') }}';
                date  = '{{ __('words.Dates.Date') }}';
                day = '{{ __('words.Dates.DayDefault') }}';

                if (typeof data.endDate != _l.value.type.undefined && data.endDate != '') {
                    endDateTime = new Date(data.endDate);
                    var fullYear = endDateTime.getFullYear();
                    var month =  (endDateTime.getMonth() + 1);
                    var date  = endDateTime.getDate();
                    var day = '{{ __('words.Dates.Days') }}'.split('_')[endDateTime.getDay() ];
                }

                $(`${ _l.el.contentPreview } [role-name=${ _l.elOther.openingImg}]`).each(function () {
                    $(this).attr('src', data.cuponImg);
                });

                $(`${ _l.el.contentPreview } [role-name=${ _l.elOther.endDatePreview}]`).each(function () {
                    endDateTime = fullYear + '/' + month + '/' + date + '(' + day + '){{ __("words.To") }}';
                    $(this).html(endDateTime);
                });

                $(`${ _l.el.contentPreview } [role-name=${ _l.elOther.cuponName}]`).each(function () {
                    $(this).html(data.cuponName);
                });

                $(`${ _l.el.contentPreview } [role-name=${ _l.elOther.useLimit}]`).each(function () {
                    var useCount = data.useCount == 0 ? '{{ __("words.AnyNumOfTimes") }}' : data.useCount + '{{ __("words.UpToTimes") }}';
                    $(this).html(useCount);
                });

                $(`${ _l.el.contentPreview } [role-name=${ _l.elOther.grantPoint}]`).each(function () {
                    var point = '{{ __("words.Point") }}';
                    if (typeof data.grantPoint != _l.value.type.undefined && data.grantPoint != 0) {
                        point = data.grantPoint + point;
                    }
                    $(this).html(point);
                });

                $(`${ _l.el.contentPreview } [role-name=${ _l.elOther.couponText}]`).each(function () {
                    $(this).html(data.cuponText);
                });

                $(`${ _l.el.contentPreview } [role-name=${ _l.elOther.useLimit}]`).first().parent().after(function () {
                    var storeBtns = '';
                    $( ".btn-custom-store" ).remove();

                    for (var store of $(`input[name="${  _l.elOther.storesInputName }"]:checked`).next()) {
                        storeBtns += `<span class="p-2"><button class="btn btn-custom-store rounded-pill">${ $(store).html() }</button></span>`
                    }

                    return storeBtns;
                });
            },
            undoEdit: function () {
                _g.form.undoEdit('form', _l.constant.csvExtensionRegex, _l.constant.imgExtensionRegex);
            },
            updateFieldsWithCuponTypeCode7: function (value) {
                var shouldDisable = false;
                var codeCuponDspFlgUnconditional = 0;
                var codePriorityDspFlgDntDsp = 0;
                var codeAutoEntryFlgDntEnter = 0;
                var codeStoreFlgDntEnter = 0;
                var codeStampCompletedCouponType = '7';
                var displayVal = _l.style.inlineBlock;
                var cuponDisplayFlgEl = `input[name=${ _l.elOther.cuponDisplayFlgInputName }]`;
                var priorityDisplayFlgEl = `input[name=${ _l.elOther.priorityDspFlgInputName }]`;
                var autoEntryFlgEl = `input[name=${ _l.elOther.autoEntryFlgInputName }]`;
                var pointGrantFlgEl = `input[name=${ _l.elOther.pointGrantFlgInputName }]`;
                var storeFlgEl = `input[name=${ _l.elOther.storeFlgInputName }]`;
                var disabledElements = $(`${ cuponDisplayFlgEl }, ${ priorityDisplayFlgEl }, ${ autoEntryFlgEl }, ${ pointGrantFlgEl }, ${ storeFlgEl }`);
                var disabledElWithVal = $(`${ cuponDisplayFlgEl }[value=${ codeCuponDspFlgUnconditional }],
                    ${ priorityDisplayFlgEl }[value=${ codePriorityDspFlgDntDsp }],
                    ${ autoEntryFlgEl }[value=${ codeAutoEntryFlgDntEnter }], ${ storeFlgEl }[value=${ codeStoreFlgDntEnter }]
                `);

                if (value === codeStampCompletedCouponType) {
                    shouldDisable = true;
                    displayVal = _l.style.none;
                    disabledElWithVal.prop(_l.props.checked, true);
                    disabledElWithVal.trigger('change');
                    $(`${ pointGrantFlgEl }`).prop(_l.props.checked, false);
                } else if (!shouldDisable && !$(cuponDisplayFlgEl).prop(_l.props.disabled)) {
                    return 0;
                }

                disabledElements.closest('.form-group').find('label req').css('display', displayVal);
                disabledElements.prop(_l.props.disabled, shouldDisable);
            },
            pointGrantFlgDisable: function (value) {
                $(`input[name="${ _l.elOther.pointGrantPurchasesPriceInputName }"]`).prop(_l.props.disabled, value != _l.constant.codeAmnt);
                $(`input[name="${ _l.elOther.pointGrantPurchasesCountInputName }"]`).prop(_l.props.disabled, value != _l.constant.codePurchase);
            },
            updateFieldsWithProductFlgTrigger: function (value) {
                var targetUserValueBoxAddClass = '';
                var targetUserValueBoxRemoveClass = '';

                switch (value) {
                    case _l.constant.productDesignationCode:
                        targetUserValueBoxAddClass = _l.elOther.isCsvProdcode;
                        targetUserValueBoxRemoveClass = _l.elOther.isCsvProdCategory;
                        break;
                    case _l.constant.categoryDesignationCode:
                        targetUserValueBoxAddClass = _l.elOther.isCsvProdCategory;
                        targetUserValueBoxRemoveClass =_l.elOther.isCsvProdcode;
                        break;
                    default:
                        targetUserValueBoxRemoveClass = `${ _l.elOther.isCsvProdCategory } ${ _l.elOther.isCsvProdcode }`;
                        break;
                }

                $(`${  _l.el.targetCouponValBox }`).addClass(targetUserValueBoxAddClass).removeClass(targetUserValueBoxRemoveClass);
            },
            increaseFlgDisable: function (value) {
                $(`input[name="${ _l.elOther.grantPointInputName }"]`).prop(_l.props.disabled, value != _l.constant.codePointsAwarded);
            },
            updateFieldsConByCuponDisplayFlg: function (value) {
                var dspTrgtFlgAddClass = value == _l.constant.dspTargetAOCode ? _l.class.isAo : _l.class.isUb;
                var dspTrgtFlgRemoveClass = dspTrgtFlgAddClass == _l.class.isAo ? _l.class.isUb : _l.class.isAo;
                $(_l.el.ubAoBox).addClass(dspTrgtFlgAddClass).removeClass(dspTrgtFlgRemoveClass);

                var targetUserValueBoxAddClass = '';
                var targetUserValueBoxRemoveClass = '';

                switch (value) {
                    case _l.constant.dspTargetUnionCode:
                        targetUserValueBoxAddClass = _l.class.isCsv;
                        targetUserValueBoxRemoveClass = _l.class.isSelectOption;
                        break;
                    case _l.constant.dspTargetUBCode:
                        targetUserValueBoxAddClass =  _l.class.isSelectOption;
                        targetUserValueBoxRemoveClass = _l.class.isCsv;
                        break;
                    case _l.constant.dspTargetAOCode:
                        targetUserValueBoxAddClass =  _l.class.isSelectOption;
                        targetUserValueBoxRemoveClass = _l.class.isCsv;
                        break;
                    default:
                        targetUserValueBoxRemoveClass = `${ _l.class.isCsv } ${  _l.class.isSelectOption }`;
                        break;
                }

                $(_l.el.targetUserValBox).addClass(targetUserValueBoxAddClass).removeClass(targetUserValueBoxRemoveClass);
            },
            storesCheckboxUpdate: function (value) {
                var storesEl = $(`input[name="${ _l.elOther.storesInputName }"]`);
                storesEl.closest('.form-group').find('label req').css('display',  value == _l.constant.storeFlgCodeSpecified ? 'inline-block' : 'none');
                storesEl.prop(_l.props.disabled, value == _l.constant.storeFlgCodeUnspecified);

                if (value == _l.constant.storeFlgCodeUnspecified) {
                    storesEl.prop('checked', false);
                }
            },
            updateFieldsWithCuponTypeCode2And3: function (value) {
                var shouldDisable = false;
                var codeProdDesignation = '2';
                var prodTrialCouponType = '2';
                var codeCategoryDesignation = '3';
                var codeCategoryCouponType = '3';
                var displayVal = _l.style.inlineBlock;
                var disabledElements = $(`input[name=${ _l.elOther.productFlg }]`);
                var disabledElWithVal = `input[name=${ _l.elOther.productFlg }]`;

                if (value === prodTrialCouponType) {
                    shouldDisable = true;
                    displayVal = _l.style.none;
                    disabledElWithVal += `[value=${ codeProdDesignation }]`;
                    $(disabledElWithVal).prop(_l.props.checked, true);
                    $(disabledElWithVal).trigger('change');
                } else if (value === codeCategoryCouponType) {
                    shouldDisable = true;
                    displayVal = _l.style.none;
                    disabledElWithVal += `[value=${ codeCategoryDesignation }]`;
                    $(disabledElWithVal).prop(_l.props.checked, true);
                    $(disabledElWithVal).trigger('change');
                } else if (!shouldDisable && !$(`input[name=${ _l.elOther.productFlg }]`).prop(_l.props.disabled)) {
                    return 0;
                }

                disabledElements.closest('.form-group').find('label req').css('display', displayVal);
                disabledElements.prop(_l.props.disabled, shouldDisable);
            }
        };
    </script>
    <script src="{{ _vers('js/page/couponPlan.js') }}"></script>
@endpush
