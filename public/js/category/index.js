/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

load_data();

$('#date-search').on('click', function () {
    let status    = $('#status').val();
    let requested = $('#requested').val();

    $('#maintable').DataTable().destroy();

    load_data(status, requested);
});

$('#refresh').on('click', function () {
    $('#status').val('');
    $('#requested').val('');

    $('#maintable').DataTable().destroy();

    load_data();
});

function load_data(status = '', requested = '') {

    var table = $('#maintable').DataTable({

        processing: true,
        serverSide: true,

        ajax: {
            url: $('#maintable').attr('data-url'),
            data: {
                status: status,
                requested: requested
            }
        },

        columns: [

            // Category Name
            {
                data: 'name',
                name: 'name'
            },

            // Category Group
            {
                data: null,
                name: 'category_group',
                render: function (data, type, row) {

                    return row.category_group?.name ?? '-';
                }
            },

            // Main Category
            {
                data: null,
                name: 'main_category',
                render: function (data, type, row) {

                    // Agar sub-category hai to parent ka name
                    if (row.parent?.name) {
                        return row.parent.name;
                    }

                    // Agar main category hai to uska own name
                    return row.name ?? '-';
                }
            },

            // Status
            {
                data: 'status',
                name: 'status'
            },

            // Action
            {
                data: 'action',
                name: 'action'
            }

        ],

        ordering: false
    });

    // let hidecolumn = $('#maintable').data('hidecolumn');
    // if (!hidecolumn) {
    //     table.column(5).visible(false);
    // }
}