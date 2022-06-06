
'use strict';

/*======================================================================
* INITIALIZATION
*======================================================================*/

$(function () {
    _g.initGlobalElementVariable(_l);

    _l.elFieldContent.trumbowyg({
        lang: _l.lang.japanese,
        btnsDef: _g.trumbowyg.default.btnsDef,
        btns: _g.trumbowyg.default.btns,
        plugins: {
            upload: {
                serverPath: _l.route.trumbowyg,
                fileFieldName: 'image',
                data: [_g.trumbowyg.uploadDataToken()],
                urlPropertyName: 'url',
                error: function(response) {
                    toastr.error(response.responseJSON.message);
                }
            }
        }
    });

    _l.elFieldDates.datetimepicker({
        format: 'L',
        allowInputToggle: true
    });

    _l.elFieldTimes.datetimepicker({
        format: 'LT',
        allowInputToggle: true
    });
});

/*======================================================================
* DOM EVENTS
*======================================================================*/

$(function () {
    /** FORM **/
    _l.elForm.submit(function() {
        _l.elBody.css('pointer-events','none');
        _l.elWrapper.css('opacity', _l.style.halfOpacity);
        _l.elSpinnerOverlay.css('display', 'flex');
    });

    /** BUTTON DUPLICATE **/
    _l.elBtnDuplicate.click(function(e) {
        e.preventDefault();
        let callback = function () {
            _l.elForm.attr('action', _l.route.duplicate);
            _l.elBtnSubmit.unbind('click').click();
        }
        _g.modal.show(_l.text.confirmationModal, _l.text.duplicateConfirmationModal, callback);
    });

    /** BUTTON DELETE **/
    _l.elBtnDelete.click(function(e) {
        e.preventDefault();
        let callback = function () {
            if ($.trim(_l.route.delete) != '') {
                $(_l.el.form).attr('action', _l.route.delete);
                $("#submit").unbind('click').click();
            }
        }
        _g.modal.show(_l.text.confirmationModal, _l.text.deleteConfirmationModal, callback);
    });

    /** BUTTON CLEAR & UNDO **/
    _l.elBtnUndo.click(function() {
        _l.undoEdit();
    });

    /** selectPublicationDateTime **/
    $(`input[name="${ _l.input.name.selectPublicationDateTime }"]`).change(function () {
        var val = $(this).filter(':checked').val() ;
        var startDate = `input[name="${ _l.input.name.startDate }"]`;
        var startTime = `input[name="${ _l.input.name.startTime }"]`;
        var endDate = `input[name="${ _l.input.name.endDate }"]`;
        var endTime = `input[name="${ _l.input.name.endTime }"]`;
        var startDateTime = `${ startDate }, ${ startTime }`;
        var endDateTime = `${ endDate }, ${ endTime }`;

        if (typeof val == 'undefined') {
            $(`${ startDateTime }, ${ endDateTime }`).prop(_l.props.readonly, true);
        } else if (val == 0) {
            var dateTime = _g.dateTime.now();
            $(startDate).val(dateTime.date);
            $(startTime).val(dateTime.time);
            $(`${ endDate }, ${ endTime }`).prop(_l.props.readonly, false);
            $(`input[id="${ _l.input.id.startDateInput }"], input[id="${ _l.input.id.startTimeInput }"]`).prop(_l.props.readonly, true);
        } else {
            $(startDateTime).val('');
            $(endDateTime).val('');
            $(`${ startDateTime }, ${ endDateTime }`).prop(_l.props.readonly, false);
        }
    });

    $(`input[name=${ _l.elOther.cuponDisplayFlgInputName }]`).change(function () {
        _l.updateFieldsConByCuponDisplayFlg($(this).filter(':checked').val());
    });

    $(`input[name=${ _l.elOther.pointGrantFlgInputName }]`).change(function () {
        _l.pointGrantFlgDisable($(this).filter(':checked').val());
    });

    $(`input[name=${ _l.elOther.increaseFlgInputName }]`).change(function () {
        _l.increaseFlgDisable( $(`input[name="${ _l.elOther.increaseFlgInputName }"]:checked`).val());
    });

    $(`input[name="${ _l.elOther.cuponTypeInputName }"]`).change(function() {
        _l.updateFieldsWithCuponTypeCode7($(this).filter(':checked').val());
        _l.updateFieldsWithCuponTypeCode2And3($(this).filter(':checked').val());
    });

    $(`input[name=${ _l.elOther.productFlg }]`).change(function() {
        _l.updateFieldsWithProductFlgTrigger($(this).filter(':checked').val());
    });

    /** union member code csv **/
    $(`input[name="${ _l.elOther.unionMemberCSVUploadTrigger }"]`).on('change', function (e) {
        _l.upload(this, e.target.id, _l.elOther.unionMemberCSVInputName, _l.value.csv);
    });

    /** coupon product codes csv **/
    $(`input[name="${ _l.elOther.prodCodeCsvUploadTrigger }"]`).on('change', function (e) {
        _l.upload(this, e.target.id, _l.elOther.prodCodeCsvInputName, _l.value.csv);
    });

    /** coupon product categories csv **/
    $(`input[name="${ _l.elOther.prodCategoryCsvUploadTrigger }"]`).on('change', function (e) {
        _l.upload(this, e.target.id, _l.elOther.prodCategoryCsvInputName, _l.value.csv);
    });

    $(`input[name="${ _l.elOther.storeFlgInputName }"]`).on('change', function () {
        _l.storesCheckboxUpdate($(this).filter(':checked').val());
    });

    $(`input[name=${ _l.elOther.cuponTypeInputName }]`).on('click', function () {
        var val = $(this).val();

        if (val == 1) {
            $('input[name=cuponDisplayFlg][value=0]').prop('checked', true).change();
            $('input[name=useCount]').val('0').change();
            $('input[name=useTime]').val('0').change();
            $('input[name=priorityDisplayFlg][value=0]').prop('checked', true).change();
            $('input[name=autoEntryFlg][value=0]').prop('checked', true).change();
            $('input[name=pointGrantFlg][value=2]').prop('checked', true).change();
            $('input[name=pointGrantPurchasesCount]').val('1').change();
            $('input[name=productFlg][value=2]').prop('checked', true).change();
            $('input[name=storeFlg][value=0]').prop('checked', true).change();
            $('input[name=increaseFlg][value=1]').prop('checked', true).change();
        }
    });

    /** image **/
    $(`input#couponImage`).on('change',function (e) {
        _l.upload(this, e.target.id, 'cuponImg', 'image', function () {
            _l.reloadPreview();
        });
    });

    /** BUTTON PREVIEW **/
    _l.elBtnPreview.click(function (e) {
        e.preventDefault();
        _l.reloadPreview();
        $(_l.el.contentPreview).modal('show');
    });
});

/*======================================================================
* DOM INITIAL
*======================================================================*/

$(function () {
    var cuponDisplayFlg = $(`input[name=${ _l.elOther.cuponDisplayFlgInputName }]:checked`);
    var cuponTypeFlg = $(`input[name=${ _l.elOther.cuponTypeInputName }]:checked`);
    var pointGrantFlg = $(`input[name=${ _l.elOther.pointGrantFlgInputName }]:checked`);
    var productFlg = $(`input[name=${ _l.elOther.productFlg }]:checked`);
    var increaseFlg = $(`input[name=${ _l.elOther.increaseFlgInputName }]:checked`);
    var storeFlg = $(`input[name=${ _l.elOther.storeFlgInputName }]:checked`);

    _l.updateFieldsConByCuponDisplayFlg(cuponDisplayFlg.val());
    _l.pointGrantFlgDisable(pointGrantFlg.val());
    _l.updateFieldsWithCuponTypeCode7(cuponTypeFlg.val());
    _l.updateFieldsWithCuponTypeCode2And3(cuponTypeFlg.val());
    _l.updateFieldsWithProductFlgTrigger(productFlg.val());
    _l.increaseFlgDisable(increaseFlg.val());
    _l.storesCheckboxUpdate(storeFlg.val());

    if ($(`[name=${ _l.input.name.selectPublicationDateTime }]:checked`).val() == 0) {
        $(`input[name="${ _l.input.name.startDate }"], input[name="${ _l.input.name.startTime }"]`).prop(_l.props.readonly, true);
        $(`input[name="${ _l.input.name.endDate }"], input[name="${ _l.input.name.endTime }"]`).prop(_l.props.readonly, false);
    } else if ($(`[name=${ _l.input.name.selectPublicationDateTime }]:checked`).val() == 1) {
        $(`input[name="${ _l.input.name.startDate }"], input[name="${ _l.input.name.startTime }"], input[name="${ _l.input.name.endDate }"], input[name="${ _l.input.name.endTime }"]`).prop(_l.props.readonly, false);
    } else {
        $(`input[name="${ _l.input.name.startDate }"], input[name="${ _l.input.name.startTime }"], input[name="${ _l.input.name.endDate }"], input[name="${ _l.input.name.endTime }"]`).prop(_l.props.readonly, true);
    }
});
