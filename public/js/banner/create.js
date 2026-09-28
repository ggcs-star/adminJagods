"use strict";

function readURL(input) {

    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {
            $('#previewImage').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


// Target Type Change
$('#target_type').on('change', function () {

    let targetType = $(this).val();

    let $target = $('#target_id');

    $target.empty();

    $target.append(
        '<option value="">Select Target</option>'
    );

    if (targetType === 'restaurant') {

        restaurants.forEach(function (restaurant) {

            $target.append(
                '<option value="' + restaurant.id + '">' +
                restaurant.name +
                '</option>'
            );

        });

    } else if (targetType === 'category') {

        categories.forEach(function (category) {

            $target.append(
                '<option value="' + category.id + '">' +
                category.name +
                '</option>'
            );

        });
    }

});


// Old value / validation error
if (oldTargetType) {

    $('#target_type').val(oldTargetType);

    $('#target_type').trigger('change');

    if (oldTargetId) {
        $('#target_id').val(oldTargetId);
    }
}


// File name
$(".custom-file-input").on("change", function() {

    let fileName = $(this)
        .val()
        .split("\\")
        .pop();

    $(this)
        .siblings(".custom-file-label")
        .addClass("selected")
        .html(fileName);
});


if (jQuery().summernote) {

    $(".summernote").summernote({
        dialogsInBody: true,
        minHeight: 250,
    });

    $(".summernote-simple").summernote({
        dialogsInBody: true,
        minHeight: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['paragraph']]
        ]
    });
}