"use strict";

$(document).ready(function () {

    load_data();


    // Search / Filter
    $('#filter-search').on('click', function (e) {

        e.preventDefault();

        if ($.fn.DataTable.isDataTable('#maintable')) {
            $('#maintable').DataTable().destroy();
        }

        load_data();
    });


    // Clear filters
    $('#refresh').on('click', function (e) {

        e.preventDefault();

        $('#restaurant_id').val('');
        $('#status').val('');

        if ($.fn.DataTable.isDataTable('#maintable')) {
            $('#maintable').DataTable().destroy();
        }

        load_data();
    });


    function load_data() {

        var table = $('#maintable').DataTable({

            processing: true,

            serverSide: true,

            ajax: {

                url: $('#maintable').attr('data-url'),

                data: function (data) {

                    data.restaurant_id = $('#restaurant_id').val() || '';

                    data.status = $('#status').val() || '';
                }
            },

            columns: [

                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'categories',
                    name: 'categories'
                },

                {
                    data: 'status',
                    name: 'status'
                },

                {
                    data: 'unit_price',
                    name: 'unit_price'
                },

                {
                    data: 'action',
                    name: 'action'
                }

            ],

            ordering: false
        });


        let hidecolumn = $('#maintable').data('hidecolumn');

        if (!hidecolumn) {
            table.column(4).visible(false);
        }
    }

});