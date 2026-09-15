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

// File name
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

if (jQuery().summernote) {
    $(".summernote-simple").summernote({
        dialogsInBody: true,
        minHeight: 230,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['para', ['paragraph']]
        ]
    });
}

$(document).ready(function () {

    // Other Select2
    $('.select2:not(#tags)').select2();

    // Tags Select2
    $('#tags').select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: 'Select or type tags',
        allowClear: true,
        width: '100%'
    });
});

ClassicEditor.create(document.querySelector('#editor'), {
    editorContainer: {
        height: '500px',
        width: '100%',
    }
})
.then(editor => {
    editor.ui.view.editable.element.style.height = "130px";
    editor.ui.view.editable.element.style.overflow = "auto";
})
.catch(error => {
    console.error(error);
});