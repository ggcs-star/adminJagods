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



/*
|--------------------------------------------------------------------------
| Cover Images Upload & Preview
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    const coverInput = document.getElementById('coverImageInput');
    const coverPreview = document.getElementById('coverImagePreview');
    const coverImageCount = document.getElementById('coverImageCount');
    const deletedCoverImagesContainer = document.getElementById('deletedCoverImagesContainer');

    if (!coverInput || !coverPreview) {
        return;
    }

    let selectedCoverFiles = [];


    /*
    |--------------------------------------------------------------------------
    | Update Cover Image Count
    |--------------------------------------------------------------------------
    */

    function updateCoverImageCount() {

        const existingCovers = coverPreview.querySelectorAll(
            '.existing-cover-image'
        ).length;

        const newCovers = selectedCoverFiles.length;

        const totalCovers = existingCovers + newCovers;

        if (coverImageCount) {
            coverImageCount.innerHTML = `
                <i class="fa-solid fa-images mr-1"></i>
                ${totalCovers} cover image(s)
            `;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Update Input Files
    |--------------------------------------------------------------------------
    */

    function updateCoverInputFiles() {

        const dataTransfer = new DataTransfer();

        selectedCoverFiles.forEach(function (file) {
            dataTransfer.items.add(file);
        });

        coverInput.files = dataTransfer.files;
    }


    /*
    |--------------------------------------------------------------------------
    | Render New Cover Images
    |--------------------------------------------------------------------------
    */

    function renderNewCoverImages() {

        coverPreview
            .querySelectorAll('.new-cover-image')
            .forEach(function (element) {
                element.remove();
            });


        selectedCoverFiles.forEach(function (file, index) {

            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                const wrapper = document.createElement('div');

                wrapper.className =
                    'cover-preview-item new-cover-image relative group rounded-lg overflow-hidden border border-gray-200 bg-white';

                wrapper.innerHTML = `
                    <img
                        src="${event.target.result}"
                        alt="Cover Preview"
                        class="w-full h-32 object-cover"
                    >

                    <button
                        type="button"
                        class="remove-new-cover absolute top-2 right-2 w-8 h-8 rounded-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center z-20 shadow"
                        data-index="${index}"
                        title="Remove cover image"
                    >
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>

                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1">
                        <p class="text-white text-xs truncate">
                            ${file.name}
                        </p>
                    </div>
                `;

                coverPreview.appendChild(wrapper);

                updateCoverImageCount();
            };

            reader.readAsDataURL(file);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Select Cover Images
    |--------------------------------------------------------------------------
    */

    coverInput.addEventListener('change', function (event) {

        const files = Array.from(event.target.files)
            .filter(function (file) {
                return file.type.startsWith('image/');
            });


        selectedCoverFiles = [
            ...selectedCoverFiles,
            ...files
        ];


        updateCoverInputFiles();

        renderNewCoverImages();

        updateCoverImageCount();
    });


    /*
    |--------------------------------------------------------------------------
    | Remove Cover Images
    |--------------------------------------------------------------------------
    */

    coverPreview.addEventListener('click', function (event) {


        /*
        |--------------------------------------------------------------------------
        | Remove Existing Cover
        |--------------------------------------------------------------------------
        */

        const existingButton = event.target.closest(
            '.remove-existing-cover'
        );

        if (existingButton) {

            const mediaId = existingButton.dataset.mediaId;

            const wrapper = existingButton.closest(
                '.existing-cover-image'
            );

            if (wrapper) {
                wrapper.remove();
            }


            /*
            |--------------------------------------------------------------------------
            | Hidden Input for Laravel
            |--------------------------------------------------------------------------
            */

            const hiddenInput = document.createElement('input');

            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deleted_cover_images[]';
            hiddenInput.value = mediaId;

            deletedCoverImagesContainer.appendChild(hiddenInput);

            updateCoverImageCount();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Remove New Cover
        |--------------------------------------------------------------------------
        */

        const newButton = event.target.closest(
            '.remove-new-cover'
        );

        if (newButton) {

            const index = parseInt(
                newButton.dataset.index,
                10
            );


            selectedCoverFiles.splice(index, 1);

            updateCoverInputFiles();

            renderNewCoverImages();

            updateCoverImageCount();

            return;
        }

    });


    /*
    |--------------------------------------------------------------------------
    | Drag & Drop
    |--------------------------------------------------------------------------
    */

    const coverUploadBox = document.getElementById(
        'coverImageUploadBox'
    );

    if (coverUploadBox) {

        coverUploadBox.addEventListener(
            'dragover',
            function (event) {

                event.preventDefault();

                coverUploadBox.classList.add(
                    'border-primary',
                    'bg-primary/5'
                );
            }
        );


        coverUploadBox.addEventListener(
            'dragleave',
            function () {

                coverUploadBox.classList.remove(
                    'border-primary',
                    'bg-primary/5'
                );
            }
        );


        coverUploadBox.addEventListener(
            'drop',
            function (event) {

                event.preventDefault();

                coverUploadBox.classList.remove(
                    'border-primary',
                    'bg-primary/5'
                );


                const files = Array.from(
                    event.dataTransfer.files
                ).filter(function (file) {

                    return file.type.startsWith('image/');
                });


                selectedCoverFiles = [
                    ...selectedCoverFiles,
                    ...files
                ];


                updateCoverInputFiles();

                renderNewCoverImages();

                updateCoverImageCount();
            }
        );
    }


    updateCoverImageCount();

});