
$(function () {
    $('#loader').hide();
    $('#to_date').css('position', 'relative');
    $('#to_date').css('zIndex', 3000);
    $('#from_date').css('position', 'relative');
    $('#from_date').css('zIndex', 3000);

    $('#reset').click(function () {
        window.location.href = appName + '/stock/search-procurements';
    });

    var startDateTextBox = $('#from_date');
    var endDateTextBox = $('#to_date');


    startDateTextBox.datepicker({
        minDate: "-5Y",
        maxDate: "+5Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    endDateTextBox.datepicker('setDate', testStartDate);
            } else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    });
    endDateTextBox.datepicker({
        minDate: "-5Y",
        maxDate: "+5Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        onClose: function (dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.datepicker('setDate', testEndDate);
            } else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    });

    $("#eta").datepicker({
        minDate: 0,
        maxDate: "+10Y",
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
});

$(".update-eta").click(function () {

    $('#shipment_id').val($(this).attr('pkId'));
    $('#eta').val($(this).attr('pkId2'));
    
    $('#t_eta').html($(this).attr('pkId3'));
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-space-status",
        data: {shipment_id: $(this).attr('pkId'), eta: $('#eta').val()},
        dataType: 'html',
        success: function (data) {

            $('#space-estimate').html(data);
        }
    });
});

$("#eta").change(function () {

    $('#space-estimate').html($('#loader').show());
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-space-status",

        data: {shipment_id: $('#shipment_id').val(), eta: $('#eta').val()},
        dataType: 'html',
        success: function (data) {

            $('#space-estimate').html(data);
        }
    });
});


$('.future_arrival_details').click(function () {
    $("#future_arrival_details").hide('slow');

    Metronic.startPageLoading('Please wait...');
    $.ajax({
        type: "POST",
        url: appName + "/stock/ajax-pipeline-consignment-details",
        data: {id: $(this).attr("id")},
        dataType: 'html',
        success: function (data) {
            $('#future_arrival_details').html(data);
            Metronic.stopPageLoading();
            $("#future_arrival_details").show('slow');

            $('[data-toggle="notyfy"]').click(function () {
                $.notyfy.closeAll();
                var self = $(this);
                var id = self.attr("id");
                notyfy({
                    text: notification[self.data('type')],
                    type: self.data('type'),
                    dismissQueue: true,
                    layout: self.data('layout'),
                    buttons: (self.data('type') != 'confirm') ? false : [
                        {
                            addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                            text: '<i></i> Ok',
                            onClick: function ($notyfy) {
                                var id = self.attr("id");
                                $notyfy.close();
                                $.ajax({
                                    type: "POST",
                                    url: appName + '/stock/ajax-delete-pipeline-consignment?id=' + id,
                                    data: {id: self.data('bind')},
                                    dataType: 'html',
                                    success: function (data) {
                                        notyfy({
                                            force: true,
                                            text: 'Future consignment has been deleted!',
                                            type: 'success',
                                            layout: self.data('layout')
                                        });
                                        self.closest("tr").remove();
                                    }
                                });
                            }
                        },
                        {
                            addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                            text: '<i></i> Cancel',
                            onClick: function ($notyfy) {
                                $notyfy.close();
                            }
                        }
                    ]
                });
                return false;
            });

            $('#received').click(function () {
                $.notyfy.closeAll();
                notyfy({
                    text: notification["confirm"],
                    type: "confirm",
                    dismissQueue: true,
                    layout: "top",
                    buttons: ("confirm" != 'confirm') ? false : [
                        {
                            addClass: 'btn btn-success btn-small btn-icon glyphicons ok_2',
                            text: '<i></i> Ok',
                            onClick: function ($notyfy) {
                                $notyfy.close();
                                $("#pipeline-form").submit();
                            }
                        },
                        {
                            addClass: 'btn btn-danger btn-small btn-icon glyphicons remove_2',
                            text: '<i></i> Cancel',
                            onClick: function ($notyfy) {
                                $notyfy.close();
                            }
                        }
                    ]
                });
                return false;
            });

            var notification = [];
            notification['confirm'] = 'Do you want to continue?';

            /*$("td[id$='-editable']").dblclick(function (e) {
             e.stopPropagation();      //<-------stop the bubbling of the event here
             var attr_id = $(this).attr("id");
             var id = attr_id.replace("-editable", "");
             
             /*var currentEle = $(this);
             var value = $.trim($(this).html());
             value = value.replace(",", "");
             updateVal(currentEle, value, id);*/
            //});

            /*function updateVal(currentEle, value, id) {
             $(currentEle).html('<input id="' + id + '" class="form-control" type="text" value="' + value + '" />');
             $('#' + id).focus();
             $('#' + id).keyup(function (event) {
             if (event.keyCode == 13) {
             $(currentEle).html($('#' + id).val().trim());
             }
             });
             
             $(document).click(function (event) {
             if (event.target.id == id) {
             return;
             }
             
             if ($(event.target).closest('#' + id).length) {
             return;
             }
             
             if ($('#' + id).val() != '') {
             $(currentEle).html($('#' + id).val().trim());
             $('#' + id + '-received').val($('#' + id + '-editable').html().trim());
             }
             });
             }*/
        }
    });
});

