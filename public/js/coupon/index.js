"use strict";

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Filter Toggle
    |--------------------------------------------------------------------------
    */

    $('.table-filter-btn').on('click', function (e) {
        
        e.preventDefault();
 console.log('FILTER BUTTON CLICKED');
        const filterDiv = $('.table-filter-div');

        if (filterDiv.is(':visible')) {
            filterDiv.hide();
        } else {
            filterDiv.show();
        }
    });


    /*
    |--------------------------------------------------------------------------
    | Initial DataTable
    |--------------------------------------------------------------------------
    */

    load_data();


    /*
    |--------------------------------------------------------------------------
    | Apply Filter
    |--------------------------------------------------------------------------
    */

    $('#filter-search').on('click', function (e) {
        e.preventDefault();

        const status = $('#status').val();

        if ($.fn.DataTable.isDataTable('#maintable')) {
            $('#maintable').DataTable().destroy();
        }

        load_data(status);
    });


    /*
    |--------------------------------------------------------------------------
    | Clear Filter
    |--------------------------------------------------------------------------
    */

    $('#refresh').on('click', function (e) {
        e.preventDefault();

        $('#status').val('');

        if ($.fn.DataTable.isDataTable('#maintable')) {
            $('#maintable').DataTable().destroy();
        }

        load_data('');
    });


    /*
    |--------------------------------------------------------------------------
    | DataTable
    |--------------------------------------------------------------------------
    */

    function load_data(status = '') {

        const table = $('#maintable').DataTable({

            processing: true,

            serverSide: true,

            ajax: {
                url: $('#maintable').data('url'),

                data: function (data) {
                    data.status = status;
                }
            },

            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'slug',
                    name: 'slug'
                },
                {
                    data: 'coupon_type',
                    name: 'coupon_type'
                },
                {
                    data: 'limit',
                    name: 'limit'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],

            ordering: false
        });


        /*
        |--------------------------------------------------------------------------
        | Hide Action Column
        |--------------------------------------------------------------------------
        */

        const hideColumn = $('#maintable').data('hidecolumn');

        if (!hideColumn) {
            table.column(5).visible(false);
        }
    }

});