"use strict";

$(document).ready(function () {
    load_data();

    function load_data(startDate = "", endDate = "", orderType = "", code = "", status = "") {
        var table = $("#maintable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $("#maintable").attr("data-url"),
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    orderType: orderType,
                    code: code,
                    status: status,
                },
            },
            columns: [
                { data: "id", name: "id" },
                { data: "user_id", name: "user_id" },
                { data: "created_at", name: "created_at" },
                { data: "order_type", name: "order_type" },
                { data: "status", name: "status" },
                
                // Payment Status with JS Render Logic
                { 
                    data: "payment_status", 
                    name: "payment_status",
                    render: function (data, type, row) {
                        // 5 = PAID, 10 = UNPAID
                        if (data == 5) {
                            return '<span style="padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #d1fae5; color: #065f46;">Paid</span>';
                        } else if (data == 10) {
                            return '<span style="padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #fee2e2; color: #991b1b;">Unpaid</span>';
                        }
                        return '<span style="padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: #f3f4f6; color: #374151;">-</span>';
                    }
                }, 
                
                { data: "total", name: "total" },
                { data: "action", name: "action" },
            ],
            ordering: false,
        });

        let hidecolumn = $("#maintable").data("hidecolumn");
        if (!hidecolumn) {
            // Index 6 se 7 kar diya kyuki ek naya column add hua hai
            table.column(7).visible(false); 
        }
    }

    $("#date-search").on("click", function () {
        let startDate = $("#start_date").val();
        let endDate   = $("#end_date").val();
        let orderType = $("#order_type").val();
        let code      = $("#code").val();
        let status    = $("#status").val();
        $("#maintable").DataTable().destroy();
        load_data(startDate, endDate, orderType, code, status);
    });

    $("#refresh").on("click", function () {
        let orderPendingStatus = $("#maintable").attr("data-status");
        $("#start_date").val("");
        $("#end_date").val("");
        $("#order_type").val("");
        $("#code").val("");
        $("#status").val(orderPendingStatus);
        $("#maintable").DataTable().destroy();
        load_data();
    });

    $('#printBtn').on('click', function () {
        let divID = $(this).data('divId');

        $('.dt-length, .dt-search, .dt-info, .dt-paging').hide();
    
        var printContents = document.getElementById(divID).innerHTML;
        var originalContents = document.body.innerHTML;
    
        var printWindow = window.open();

        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<link rel="stylesheet" href="' + window.location.origin + '/backend/css/style.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
            $('.dt-length, .dt-search, .dt-info, .dt-paging').show();
        };
    });
});