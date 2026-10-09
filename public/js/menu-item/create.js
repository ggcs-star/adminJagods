/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

/* ============================================================
 * Common Helpers
 * ============================================================ */

function readURL(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#previewImage').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}


/* ============================================================
 * Custom File Input
 * ============================================================ */

$(".custom-file-input").on("change", function () {
    const fileName = $(this).val().split("\\").pop();

    $(this)
        .siblings(".custom-file-label")
        .addClass("selected")
        .html(fileName);
});


/* ============================================================
 * Summernote
 * ============================================================ */

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


/* ============================================================
 * Select2
 * ============================================================ */

$(document).ready(function () {

    // Normal Select2
    $('.select2:not(#tags)').select2();

    // Tags Select2
    $('#tags').select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: 'Enter tags',
        allowClear: true,
        width: '100%'
    });

});


/* ============================================================
 * CKEditor
 * ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    const editorElement = document.querySelector('#editor');

    if (!editorElement) {
        return;
    }

    // Prevent duplicate CKEditor initialization
    if (editorElement.classList.contains('ck-editor__editable')) {
        return;
    }

    ClassicEditor
        .create(editorElement)
        .then(editor => {

            editor.ui.view.editable.element.style.height = '130px';
            editor.ui.view.editable.element.style.overflowY = 'auto';

        })
        .catch(error => {
            console.error('CKEditor Error:', error);
        });

});


/* ============================================================
 * Cover Images
 * ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    const coverInput = document.getElementById('coverImageInput');
    const coverPreview = document.getElementById('coverImagePreview');
    const coverImageCount = document.getElementById('coverImageCount');
    const coverUploadBox = document.getElementById('coverImageUploadBox');

    if (!coverInput || !coverPreview) {
        return;
    }

    let selectedCoverFiles = [];


    /* ------------------------------------------------------------
     * File Selection
     * ------------------------------------------------------------ */

    coverInput.addEventListener('change', function () {

        const files = Array.from(this.files);

        selectedCoverFiles = [
            ...selectedCoverFiles,
            ...files
        ];

        updateCoverInputFiles();
        renderCoverPreviews();
    });


    /* ------------------------------------------------------------
     * Update Input Files
     * ------------------------------------------------------------ */

    function updateCoverInputFiles() {

        const dataTransfer = new DataTransfer();

        selectedCoverFiles.forEach(function (file) {
            dataTransfer.items.add(file);
        });

        coverInput.files = dataTransfer.files;
    }


    /* ------------------------------------------------------------
     * Render Cover Previews
     * ------------------------------------------------------------ */

    function renderCoverPreviews() {

        coverPreview.innerHTML = '';

        if (selectedCoverFiles.length === 0) {

            if (coverImageCount) {
                coverImageCount.classList.add('hidden');
                coverImageCount.innerHTML = '';
            }

            return;
        }


        if (coverImageCount) {

            coverImageCount.classList.remove('hidden');

            coverImageCount.innerHTML = `
                <i class="fa-solid fa-images mr-1"></i>
                ${selectedCoverFiles.length}
                cover image${selectedCoverFiles.length > 1 ? 's' : ''}
                selected
            `;
        }


        selectedCoverFiles.forEach(function (file, index) {

            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                const wrapper = document.createElement('div');

                wrapper.className =
                    'relative group rounded-lg overflow-hidden border border-gray-200 bg-white';

                wrapper.innerHTML = `
                    <img
                        src="${event.target.result}"
                        alt="Cover Preview"
                        class="w-full h-32 object-cover"
                    >

                    <button
                        type="button"
                        data-index="${index}"
                        class="remove-cover-image absolute top-2 right-2
                               w-7 h-7 rounded-full bg-red-500 text-white
                               flex items-center justify-center
                               opacity-0 group-hover:opacity-100
                               transition z-20"
                        title="Remove cover image"
                    >
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>

                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1">
                        <p class="text-white text-xs truncate">
                            ${file.name}
                        </p>
                    </div>
                `;

                coverPreview.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
        });
    }


    /* ------------------------------------------------------------
     * Remove Cover Image
     * ------------------------------------------------------------ */

    coverPreview.addEventListener('click', function (event) {

        const button = event.target.closest('.remove-cover-image');

        if (!button) {
            return;
        }

        const index = parseInt(button.dataset.index, 10);

        if (isNaN(index)) {
            return;
        }

        selectedCoverFiles.splice(index, 1);

        updateCoverInputFiles();
        renderCoverPreviews();
    });


    /* ------------------------------------------------------------
     * Drag & Drop
     * ------------------------------------------------------------ */

    if (coverUploadBox) {

        coverUploadBox.addEventListener('dragover', function (event) {

            event.preventDefault();

            coverUploadBox.classList.add(
                'border-primary',
                'bg-primary/5'
            );
        });


        coverUploadBox.addEventListener('dragleave', function () {

            coverUploadBox.classList.remove(
                'border-primary',
                'bg-primary/5'
            );
        });


        coverUploadBox.addEventListener('drop', function (event) {

            event.preventDefault();

            coverUploadBox.classList.remove(
                'border-primary',
                'bg-primary/5'
            );

            const files = Array.from(event.dataTransfer.files)
                .filter(function (file) {
                    return file.type.startsWith('image/');
                });

            selectedCoverFiles = [
                ...selectedCoverFiles,
                ...files
            ];

            updateCoverInputFiles();
            renderCoverPreviews();
        });
    }

});