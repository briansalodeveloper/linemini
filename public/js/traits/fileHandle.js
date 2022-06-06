'use strict';

let fileHandle = {
    upload: function (routeUpload, inputFile, fileId, fileName, type, alwaysCallback) {
        if (inputFile.files.length != 0) {
            var formData = new FormData();

            $('body').addClass(type + '-uploading');
            $('.' + fileId).addClass('uploading');
            formData.append(fileId, inputFile.files[0]);
            formData.append(type, inputFile.files[0]);
            formData.append('validateField', fileId);
            formData.append('fileType', type);
    
            $.ajax({
                headers: {'X-CSRF-TOKEN': _g.token},
                url: routeUpload,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function (response) {
                    var html = '';
                    $(inputFile).css("color", "green");
                    $('#' + fileId + '-error').text('');
                    $('input[name="' + fileName + '"]').val(response.url);

                    if (type == 'image') {
                        html = '<a href="' + response.url + '" data-toggle="lightbox"><i class="fa fa-image"></i> ' + _g.basename(response.url) + '</a>';
                        html += '<span role="btnRemove" class="text-danger ml-2">x</span>';
                    } else if (type == 'csv') {
                        html = '<a href="' + response.url + '" download><i class="fa fa-file-csv"></i> ' + _g.basename(response.url) + '</a>';
                        html += '<span role="btnRemove" class="text-danger ml-2">x</span>';
                    } else {
                        html = _g.basename(response.url);
                    }
                    
                    $('.' + fileId + ' span.label').html(html);
                },
                error: function(response) {
                    $(inputFile).css("color", "red");
                    $('.' + fileId).addClass('is-invalid');
    
                    var errMsgs = response.responseJSON.errors[fileId];
                    let res = response.responseJSON;
                    var errMsg = '';
    
                    var ctr = 0;
    
                    for (var msg in errMsgs) {
                        if (ctr != 0) {
                            errMsg += '<br>';
                        }
    
                        errMsg = errMsgs[msg];
                    }
    
                    if (typeof res.custom != 'undefined') {
                        if (typeof res.custom.invalidCsvUrl != 'undefined') {
                            var html = '<a href="' + res.custom.invalidCsvUrl + '" class="text-danger" download><i class="fa fa-file-arrow-down"></i> ' + _g.basename(res.custom.invalidCsvUrl) + '</a>';
                            $('#' + fileId).parents('.custom-input-file').find('.label').html(html);
                            $('input[name="' + fileName + '"]').val(res.custom.invalidCsvUrl);
                        } else if (typeof res.custom.invalidLabel != 'undefined') {
                            var html = '<span class="text-danger"><i class="fa fa-circle-exclamation"></i> ' + res.custom.invalidLabel + '</a>';
                            $('#' + fileId).parents('.custom-input-file').find('.label').html(html);
                            $('input[name="' + fileName + '"]').val(res.custom.invalidCsvUrl);
                        }
                    }
    
                    $('#' + fileId + '-error').html(errMsg);
                },
            }).always(function () {
                $(inputFile).val(null);
                $('body').removeClass(type + '-uploading');
                $('.' + fileId).removeClass('uploading');
    
                if (typeof alwaysCallback != 'undefined') {
                    alwaysCallback();
                }
            });
        }
    },
};

$(function(){

    /*======================================================================
     * ENUMS
     *======================================================================*/

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    /*======================================================================
     * OTHER VARIABLES
     *======================================================================*/

    /*======================================================================
     * METHODS
     *======================================================================*/

    /*======================================================================
     * DOM EVENTS
     *======================================================================*/

    $('.custom-input-file').on('click', '[role=btnRemove]', function () {
        $(this).parents('.custom-input-file').find('input[type=file]').val(null);
        $(this).parents('.custom-input-file').find('input[type=hidden]').val('');
        $(this).parents('.custom-input-file').find('.label').html('');
    });
});