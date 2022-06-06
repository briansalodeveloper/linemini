@php
    // $routeUpload = route('notice.upload');
    $routeTrumbowyg = route('stamp.uploadTrumbowygImage');
@endphp 

@push('js')
<script>

    /*======================================================================
    * VARIABLES
    *======================================================================*/
let _l = {
    reloadPreview: function () {
        var data = $('#form').serializeArray();
        let startDateTime = '';
        data = _g.form.toArray(data);
            
        if (typeof data.startDate != 'undefined' && $.trim(data.startDate) != '' && $.trim(data.endDate) != '') {
            startDateTime = new Date(data.startDate);
            startDateTime = startDateTime.getFullYear() + '年' + (startDateTime.getMonth() + 1) + '月' + startDateTime.getDate() + '';
            endDateTime = new Date(data.endDate);
            endDateTime = endDateTime.getFullYear() + '年' + (endDateTime.getMonth() + 1) + '月' + endDateTime.getDate() + '';
            $('#stampPreview [role-name=stampStartDate]').each(function () {
                $(this).html(startDateTime);
            });
            $('#stampPreview [role-name=stampEndDate]').each(function () {
                $(this).html(endDateTime);
            });
        }

        if(data.stampImage) {
            $('#stampPreview [role-name=stampImage]').show();
            $('#stampPreview [role-name=imgButton]').show();
            $('#stampPreview [role-name=stampImage]').each(function () {
                $(this).attr('src', data.stampImage);
            });
        }else{
            $('#stampPreview [role-name=imgButton]').hide();
        }

        $('#stampPreview [role-name=stampDetail]').each(function () {
             $(this).html(data.stampText);
        });
        $('#stampPreview [role-name=title]').each(function () {
            $(this).html(data.openingLetter);
        });
    },

    undo: function () {
        var data = $('#form').serializeArray();
        for(x=0; x < data.length; x++){
            var className = document.getElementsByName(data[x].name);

            if(className[0].getAttribute('type') == 'radio'){
                //*check the original value of all radio*//
                for (i = 0; i < className.length; i++) {
                    if (className[i].value == className[0].getAttribute('origvalue')) {
                        className[i].checked = true;
                    }else{
                        className[i].checked = false;
                    }   
                }
                //*select the original value depends on what radio*//
                if(data[x].name == 'stampDisplayFlg' &&  className[0].getAttribute('origvalue') == '2'){ 
                    selected = document.getElementsByName('utilizationBusiness[]');    
                    var str = selected[0].getAttribute('origvalue');
                    var split = str.split(',');
                    $('select[auto-init-select2]').val(split);       
                }

                if(data[x].name == 'stampDisplayFlg' &&  className[0].getAttribute('origvalue') == '3'){
                    selected = document.getElementsByName('affiliationOffice[]');    
                    var str = selected[0].getAttribute('origvalue');
                    var split = str.split(',');
                    $('select[auto-init-select2]').val(split);                      
                }

                if(data[x].name == 'stampGrantFlg' &&  className[0].getAttribute('origvalue') == '1'){    
                    $('#SpecifiedAmount').val($("#SpecifiedAmount").attr('origvalue')); 
                }

                if(data[x].name == 'stampGrantFlg' &&  className[0].getAttribute('origvalue') == '2'){  
                    $('#SpecifiedNumberOfPurchase').val($("#SpecifiedNumberOfPurchase").attr('origvalue'));  
                }

                if(data[x].name == 'productFlg' &&  className[0].getAttribute('origvalue') == '3'){  
                    $('#departmentCode').val($("#departmentCode").attr('origvalue'));  
                }

                if(data[x].name == 'increaseFlg' &&  className[0].getAttribute('origvalue') == '1'){  
                    $('#SpecifiedNumberOfPoints').val($("#SpecifiedNumberOfPoints").attr('origvalue'));  
                }

                if(data[x].name == 'increaseFlg' &&  className[0].getAttribute('origvalue') == '2'){  
                    $('#SpecifiedCouponId').val($("#SpecifiedCouponId").attr('origvalue'));  
                }
                        
                $('input:radio').trigger('change');
                $('select[auto-init-select2]').trigger('change');
            continue; 
            }

            if(className[0].getAttribute('type') == 'checkbox'){      
                for (i = 0; i < className.length; i++) {
                    var str = className[0].getAttribute('origvalue');
                    var split = str.split(',');

                    if(split.includes(className[i].value) == true){
                        className[i].checked = true;
                    }else{
                        className[i].checked = false;
                    }

                }
            continue;
            }

            if(className[0].getAttribute('type') == 'text'){
                //*assign the value to original value*//
                $('#'+data[x].name).val($("#"+data[x].name).attr("origvalue"));
            continue;
            }
        }
        //-for date and time-//
            $('#startDateInput').val($('#startDateInput').attr("origvalue"));
            $('#startTimeInput').val($('#startTimeInput').attr("origvalue"));
            $('#endDateInput').val($('#endDateInput').attr("origvalue"));
            $('#endTimeInput').val($('#endTimeInput').attr("origvalue"));
        //-for stamp image-//
            var url = $('#stampImage').attr('origvalue');
            var name = $('#stampImage').attr('origname');
            var html = '<a href="' + url + '" data-toggle="lightbox"><i class="fa fa-image"></i> ' + name + '</a>';
            html += '<span role="btnRemove" class="text-danger ml-2">x</span>';
            $('#stampImage').val($('#stampImage').attr("origvalue"));
            $('#stampImage + button + span').html(html);
        //-for union member code-//
            $('#unionMemberCode').val('');
            $('#unionMemberCode + button + span').html("{{__('words.NotSelected')}}"); 
        //-for stamp text-//
            $('.trumbowyg-editor').html($('#stampText').attr("origvalue"));
    },

    clear: function () {
        document.getElementById("form").reset();
            $('.trumbowyg-editor').empty();
            $('input:radio').removeAttr('checked');
            $('input:radio').trigger('change');
            $('input:checkbox').removeAttr('checked');
            $('#unionMemberCode').val('');
            $('#stampImage').val('');
            $('#unionMemberCode + button + span').html("{{__('words.NotSelected')}}");
            $('#stampImage + button + span').html("{{__('words.NotSelected')}}");
    },

    upload: function (routeUpload, inputFile, fileId, fileName, type, callback) {
                fileHandle.upload(routeUpload, inputFile, fileId, fileName, type, callback);
            },


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

        $(document).ready(function() {
            $('input[name="selectPublicationDateTime"]').trigger('change');
            $('input[name="stampDisplayFlg"]').trigger('change');
            $('input[name="stampGrantFlg"]').trigger('change');
            $('input[name="increaseFlg"]').trigger('change');
            $('input[name="productFlg"]').trigger('change');
        })

    });

    /*======================================================================
    * DOM EVENTS
    *======================================================================*/
$(function () {
        //-Date and Time-//
        $('input[name="selectPublicationDateTime"]').change(function () {
            var val = $('input[name="selectPublicationDateTime"]:checked').val();
            if (val == 0) {
                var dateTime = _g.dateTime.now();
                $('input[name="startDate"]').val(dateTime.date);
                $('input[name="startTime"]').val(dateTime.time);
                $('input[id="startDateInput"], input[id="startTimeInput"]').prop('readonly', true);
                $('input[name="endDate"], input[name="endTime"]').prop('readonly', false);
            } else if(val == 1) {
                $('input[name="startDate"], input[name="startTime"]').val('');
                $('input[name="endDate"], input[name="endTime"]').val('');
                $('input[name="startDate"], input[name="startTime"], input[name="endDate"], input[name="endTime"]').prop('readonly', false);
            }else{
                $('input[name="startDate"], input[name="startTime"], input[name="endDate"], input[name="endTime"]').prop('readonly', true);
            }
        });
       
        //-Union members to display-//
        $('input[name="stampDisplayFlg"]').change(function () {
            var val = $('input[name="stampDisplayFlg"]:checked').val();
            if (val == "1") {
                //unhide the unionmemberfield
                $('#unionMemberCsvDiv').removeClass('d-none');
                //hide the utilizationBusiness, affiliationOffice field and remove the the value
                $('#utilizationBusinessDiv').addClass('d-none');
                $('select[name="utilizationBusiness[]"]').val(null).trigger('change');
                $('#affiliationOfficeDiv').addClass('d-none');
                $('select[name="affiliationOffice[]"]').val(null).trigger('change');
            }else if(val == "2"){
                //unhide the utilizationBusiness field
                $('#utilizationBusinessDiv').removeClass('d-none');
                //hide the unionmember field and remove the value
                $('#unionMemberCsvDiv').addClass('d-none');
                $('#affiliationOfficeDiv').addClass('d-none');
                $('select[name="affiliationOffice[]"]').val(null).trigger('change');
                $('#unionMemberCode').val(''); 
                $('#unionMemberCode + button + span').html("{{__('words.NotSelected')}}"); 

            }else if(val == "3"){
                //unhide the utilizationBusiness field
                $('#affiliationOfficeDiv').removeClass('d-none');
                //hide the unionmember utilizationBusiness field and remove the value
                $('#utilizationBusinessDiv').addClass('d-none');
                $('select[name="utilizationBusiness[]"]').val(null).trigger('change');
                $('#unionMemberCsvDiv').addClass('d-none');
                $('#unionMemberCode').val(''); 
                $('#unionMemberCode + button + span').html("{{__('words.NotSelected')}}"); 

            }else{
                //hide all the field and remove the value
                $('select[name="utilizationBusiness[]"]').val(null).trigger('change');
                $('#unionMemberCsvDiv').addClass('d-none');
                $('#affiliationOfficeDiv').addClass('d-none');
                $('select[name="affiliationOffice[]"]').val(null).trigger('change');
                $('#utilizationBusinessDiv').addClass('d-none');
                $('#unionMemberCode').val('');
                $('#unionMemberCode + button + span').html("{{__('words.NotSelected')}}"); 

            }
        });
        
        //-Stamping conditions-//  
        $('input[name="stampGrantFlg"]').change(function () {
            var val = $("input[name='stampGrantFlg']:checked").val();
            if (val == "1") {
                //unhide the SpecifiedAmount field
                $('#SpecifiedAmountDiv').removeClass('d-none');
                //hide the SpecifiedNumberOfPurchase and remove the value
                $("#SpecifiedNumberOfPurchase").val('');
                $('#SpecifiedNumberOfPurchaseDiv').addClass('d-none');
            }else if(val == "2"){
                //unhide the SpecifiedNumberOfPurchase field
                $('#SpecifiedNumberOfPurchaseDiv').removeClass('d-none');
                //hide the specifiedAmount field and remove the value
                $('#SpecifiedAmountDiv').addClass('d-none');
                $("#SpecifiedAmount").val('');
            }else{
                //hide all the field and remove the value
                $('#SpecifiedAmountDiv').addClass('d-none');
                $('#SpecifiedNumberOfPurchaseDiv').addClass('d-none');
                $("#SpecifiedNumberOfPurchase").val('');
                $("#SpecifiedAmount").val('');
            }
        });

        //-type of benefits-//
        $('input[name="increaseFlg"]').change(function () {
            var val = $("input[name='increaseFlg']:checked").val();
            if (val == "1") {
                //unhide the SpecifiedNumberOfPoints field
                $('#SpecifiedNumberOfPointsDiv').removeClass('d-none');
                //hide the SpecifiedCouponId, ProductRedumption field and remove the value
                $("#SpecifiedCouponId").val('');
                $('#SpecifiedCouponIdDiv').addClass('d-none');
                $('#ProductRedumptionDiv').addClass('d-none');
                $('#csvUploadProductRedumption').val('');
            }else if(val == "2"){
                //unhide the SpecifiedCouponId field
                $('#SpecifiedCouponIdDiv').removeClass('d-none');
                //hide the SpecifiedNumberOfPoints, ProductRedumption field and remove the value
                $("#SpecifiedNumberOfPoints").val('');
                $('#SpecifiedNumberOfPointsDiv').addClass('d-none');
                $('#ProductRedumptionDiv').addClass('d-none');
                $('#csvUploadProductRedumption').val('');
            }else if(val == "4"){
                //unhide the ProductRedumption field
                $('#ProductRedumptionDiv').removeClass('d-none');
                //hide the SpecifiedNumberOfPoints, SpecifiedCouponId field and remove the value
                $("#SpecifiedNumberOfPoints").val('');
                $('#SpecifiedNumberOfPointsDiv').addClass('d-none');
                $("#SpecifiedCouponId").val('');
                $('#SpecifiedCouponIdDiv').addClass('d-none');
            }else{
                //hide all the fields and remove all value
                $("#SpecifiedNumberOfPoints").val('');
                $('#SpecifiedNumberOfPointsDiv').addClass('d-none');
                $("#SpecifiedCouponId").val('');
                $('#SpecifiedCouponIdDiv').addClass('d-none');
                $('#ProductRedumptionDiv').addClass('d-none');
                $('#csvUploadProductRedumption').val('');
            }
        });

        //-granting benefits-//
        $('input[name="productFlg"]').change(function () {
            var val = $("input[name='productFlg']:checked").val();
            if (val == "2") {
                //unhide the productDesignation field
                $('#ProductDesignationDiv').removeClass('d-none');
                //hide the SpecifiedNumberOfPurchase and remove the value
                $("#departmentCode").val('');
                $('#DepartmentDiv').addClass('d-none');
            }else if(val == "3"){
                //unhide the SpecifiedNumberOfPurchase field
                $('#DepartmentDiv').removeClass('d-none');
                //hide the productDesignation field and remove the value
                $('#ProductDesignationDiv').addClass('d-none');
                $("#specifiedProdCodeCsv").val('');
                $('#specifiedProdCodeCsvTrigger + button + span').html("{{__('words.NotSelected')}}")
            }else{
                //hide all the field and remove the value
                $('#ProductDesignationDiv').addClass('d-none');
                $('#DepartmentDiv').addClass('d-none');
                $("#departmentCode").val('');
                $("#specifiedProdCodeCsv").val('');
                $('#specifiedProdCodeCsvTrigger + button + span').html("{{__('words.NotSelected')}}")
            }
        });

        //-stamp image-//
        $('#stampImageTrigger').change(function(e){
            var routeUpload = "{{ route('stamp.upload')}}"
            var inputFile = document.getElementById("stampImageTrigger");
             var fileId = 'stampImageTrigger';
            var fileName = 'stampImage';
            var type = 'image';
            _l.upload(routeUpload, inputFile, fileId, fileName, type);
        });

        //-product designation-//
        $('#specifiedProdCodeCsvTrigger').change(function(e){
            var routeUpload = "{{ route('stamp.upload')}}"
            var inputFile = document.getElementById("specifiedProdCodeCsvTrigger");
            var fileId = 'specifiedProdCodeCsvTrigger';
            var fileName = 'specifiedProdCodeCsv';
            var type = 'csv';
            _l.upload(routeUpload, inputFile, fileId, fileName, type);
        });

        $('#unionMemberCodeTrigger').change(function(e){
            var routeUpload = "{{ route('stamp.upload')}}"
            var inputFile = document.getElementById("unionMemberCodeTrigger");
            var fileId = 'unionMemberCodeTrigger';
            var fileName = 'unionMemberCode';
            var type = 'csv';
            _l.upload(routeUpload, inputFile, fileId, fileName, type);
        });

        $('#stampText').trumbowyg({
            lang: 'ja',
            btnsDef: _g.trumbowyg.default.btnsDef,
            btns: _g.trumbowyg.default.btns,
            plugins: {
                upload: {
                    serverPath: '{{ $routeTrumbowyg }}',
                    fileFieldName: 'image',
                    data: [_g.trumbowyg.uploadDataToken()],
                    urlPropertyName: 'url'
                }
            }
        });

        $('#clearBtn').click(function () {
            _l.clear();
        });

        $('#undoBtn').click(function (e) {
            _l.undo();
        });

        $("#duplicateProject").click(function (e) {
            e.preventDefault();
            $('#stampDuplicateProject').modal('show');
        });

        $("#deleteProject").click(function (e) {
            e.preventDefault();
            $('#stampDeleteProject').modal('show');
        });

        $("#preview").click(function (e) {
            e.preventDefault();
            _l.reloadPreview();
            $('#stampPreview').modal('show');
        });
       
});

</script>
@endpush