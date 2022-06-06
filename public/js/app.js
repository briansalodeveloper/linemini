'use strict';

let _g = {
    token: '',
    /**
     * Global variable _g initialization
     */
    init: () => {
        _g.token = $('[name=csrf-token]').attr('content');
    },
    initGlobalElementVariable: (v) => {
        if (typeof v != 'undefined') {
            if (typeof v['el'] != 'undefined') {
                for (var i in v['el']) {
                    v['el' + i.charAt(0).toUpperCase() + i.slice(1)] = $(v['el'][i]);
                }
            }
        }
    },
    basename: (url) => {
        url = $.trim(url);
        if (url == '') {
            return url;
        }

        return url.split('/').reverse()[0];
    },
    dateTime: {
        now: function () {
            var now = new Date();
            var fullYear = now.getFullYear();
            var month = now.getMonth() + 1;
            var date = now.getDate();
            var hour = now.getHours();
            var min = now.getMinutes();

            min = min < 10 ? '0' + min : min;
            hour = hour < 10 ? '0' + hour : hour;
            month = month < 10 ? '0' + month : month;
            date = date < 10 ? '0' + date : date;

            var fullDate = fullYear + '/' + month + '/' + date;
            var time = hour + ':' + min;

            return {
                date: fullDate,
                time: time
            }
        },
    },
    form: {
        val: (key, serializedForm) => {
            var values = {};

            $.each(serializedForm, function (i, field) {
                values[field.name] = field.value;
            });

            var getValue = function (valueName) {
                return values[valueName];
            };

            return getValue(key);
        },
        toArray: (serializedForm) => {
            var values = {};

            $.each(serializedForm, function (i, field) {
                values[field.name] = field.value;
            });

            return values;
        },
        undoEdit: (frm, csvExtensionRegex, imgExtensionRegex) => {
            if (typeof frm == 'undefined') {
                frm = 'form';
            }

            $(frm).trigger('reset');

            $(frm + ' [data-original-value]').each(function () {
                var _val = $(this).data('original-value');

                if (this.tagName.toLowerCase() == 'input') {
                    if ($(this).attr('type').toLowerCase() == 'text') {
                        $(this).val(_val);
                    } else if ($(this).attr('type').toLowerCase() == 'radio') {
                        var target = $(this).attr('name');

                        if ($.trim(_val) == '') {
                            $('input[name="' + target + '"]').prop('checked', false);
                        } else {
                            $('input[name="' + target + '"][value="' + _val + '"]').prop('checked', true);
                        }

                        $('input[name=' + target + ']').change();
                    } else if ($(this).attr('type').toLowerCase() == 'checkbox') {
                        var target = $(this).attr('name');
                        _val = _val.split(',');

                        $('input[name="' + target + '"]').prop('checked', false);

                        for(var i in _val) {
                            $('input[name="' + target + '"][value="' + _val[i] + '"]').prop('checked', true);
                            $('input[name="' + target + '"][value="' + _val[i] + '"]').change();
                        }
                    } else if ($(this).attr('type').toLowerCase() == 'hidden') {
                        var extension = _val.substr(_val.lastIndexOf("."));
                        var fileName = _val.substring(_val.lastIndexOf("/") + 1);
                        var logo = '';
                        var label = '';

                        if (csvExtensionRegex.test(extension)) {
                            logo = '<i class="fa fa-file-csv"></i> ';
                            label = '<a href="' + _val + '" download>' + logo + fileName + '</a>';
                        } else if (imgExtensionRegex.test(extension)) {
                            logo = '<i class="fa fa-image"></i> ';
                            label = '<a href="' + _val + '" data-toggle="lightbox" title="{{ __("words.Preview") }}">' + logo + fileName + '</a>';
                        }

                        $(this).siblings( ".label" ).empty().append(label)
                        $(this).val(_val);
                    }
                } else if (this.tagName.toLowerCase() == 'textarea') {
                    if (_val.length != 0) {
                        $(this).data().trumbowyg.empty();
                        $(this).data().trumbowyg.$ed.append($(_val).val())
                    }
                } else if (this.tagName.toLowerCase() == 'select') {
                    if (typeof $(this).data('select2') != 'undefined') {
                        if (typeof _val == 'undefined') {
                            _val = [];
                        } else {
                            _val = _val.split(',');
                        }

                        $(this).val(_val).change();
                    } else {
                        if (typeof _val == 'undefined') {
                            _val = -1;
                        }

                        $(this).val(_val);
                    }
                }
            });
        },
    },
    loading: {
        show: () => {
            $('body').css('pointer-events','none');
            $('.wrapper').css('opacity', .5);
            $('#spinner-overlay').css('display', 'flex');
        }
    },
    modal: {
        confirm: (callback) => {
            $("#modal-btn-confirm").on("click", function(){
                $("#modalConfirm").modal('hide');
                callback(true);
            });

            $("#modal-btn-cancel").on("click", function(){
                callback(false);
                $("#modalConfirm").modal('hide');
            });
        },
        show: (title, content, callback) => {
            $('.modal-title').text(title);
            $('.modal-body').text(content);
            $('#modalConfirm').modal('show')
            _g.modal.confirm(function(confirm){
               if (confirm) {
                callback();
               }
            });
        }
    },
    trumbowyg: {
        default: {
            btnsDef: {
                image: {
                    dropdown: ['insertImage', 'upload'],
                    ico: 'insertImage'
                }
            },
            btns: [
                ['viewHTML'],
                ['undo', 'redo'],
                ['formatting'],
                ['strong', 'em', 'del'],
                ['foreColor', 'backColor', 'fontsize', 'fontfamily', 'highlight'],
                ['emoji'],
                ['superscript', 'subscript'],
                ['link'],
                ['image'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat'],
                ['fullscreen']
            ],
        },
        uploadDataToken: () => {
            return {name: '_token', value: _g.token};
        }
    },
};

_g.init();

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
    $('[onclick-btn-close]').each(function () {
        $(this).on('click', function () {
            window.close();
        });
    });

    $('[onclick-showloading]').each(function () {
        $(this).on('click', function () {
            _g.loading.show();
        });
    });

    $('[onclick-password-eye]').each(function () {
        var target = $(this).data('target');
        var elEye = null;

        if ($(this).hasClass('fa-eye') || $(this).hasClass('fa-eye-slash')) {
            elEye = $(this);
        } else if ($(this).find('.fa-eye').length != 0) {
            elEye = $(this).find('.fa-eye');
        } else if ($(this).find('.fa-eye-slash').length != 0) {
            elEye = $(this).find('.fa-eye-slash');
        }

        if (typeof target != 'undefined' && elEye != null) {
            $(this).on('mousedown mouseup', function mouseState(e) {
                var target = $(this).data('target');
                var elEye = null;

                if ($(this).hasClass('fa-eye') || $(this).hasClass('fa-eye-slash')) {
                    elEye = $(this);
                } else if ($(this).find('.fa-eye').length != 0) {
                    elEye = $(this).find('.fa-eye');
                } else if ($(this).find('.fa-eye-slash').length != 0) {
                    elEye = $(this).find('.fa-eye-slash');
                }

                if (elEye != null) {
                    if ($(target).length != 0 && $(elEye).length != 0) {
                        if (e.type == "mousedown") {
                            $(target).attr('type', 'text');
                            $(elEye).removeClass('fa-eye-slash').addClass('fa-eye');
                        } else {
                            $(target).attr('type', 'password');
                            $(elEye).removeClass('fa-eye').addClass('fa-eye-slash');
                        }
                    }
                }
            });
        }
    });
});
