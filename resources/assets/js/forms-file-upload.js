/**
 * File Upload
 */

'use strict';

(function () {
  // previewTemplate: Updated Dropzone default previewTemplate
  // ! Don't change it unless you really know what you are doing
  const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;

  // ? Start your code from here

  // Basic Dropzone
  // --------------------------------------------------------------------
  const dropzoneBasic = document.querySelector('#dropzone-basic');
  if (dropzoneBasic) {
    const myDropzone = new Dropzone(dropzoneBasic, {
      previewTemplate: previewTemplate,
      parallelUploads: 1,
      maxFilesize: 5,
      addRemoveLinks: true,
      maxFiles: 1
    });
    myDropzone.on("addedfile", function (file) {
      // Get the file as a Blob URL to send in the hidden input
      const fileReader = new FileReader();
      fileReader.onload = function (e) {
        document.getElementById('hiddenImageInput').value = e.target.result; // Set the Blob URL or file name
      };
      fileReader.readAsDataURL(file);
      console.log('Working');
    });
  }

  // Multiple Dropzone
  const dropzoneMulti = document.querySelector('#dropzone-multi');
  if (dropzoneMulti) {
    const actionUrl = dropzoneMulti.getAttribute('action');
    const removeActionUrl = dropzoneMulti.getAttribute('remove-action');
    const fieldName = dropzoneMulti.getAttribute('name');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token

    let currentPaths = $('#imageJson').val();
    let pathsArray = currentPaths ? JSON.parse(currentPaths) : [];

    const myDropzoneMulti = new Dropzone(dropzoneMulti, {
      url: actionUrl,
      paramName: fieldName,
      maxFilesize: 5, // Max file size in MB
      parallelUploads: 1,
      addRemoveLinks: true,
      headers: {
        'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
      },
      init: function () {
        this.on("success", function (file, response) {
          console.log("Successfully uploaded:", response);

          // Add the new path to the pathsArray and update the input field
          pathsArray.push(response);
          $('#imageJson').val(JSON.stringify(pathsArray));
        });

        this.on("error", function (file, response) {
          console.error("Error uploading:", response);
        });

        this.on("removedfile", function (file) {
          console.log("Removed file:", file.upload.filename);
          const filePath = file.xhr ? file.xhr.response : null; // Ensure filePath is from the response

          if (filePath) {
            console.log("File path for removal:", filePath);

            $.ajax({
              url: removeActionUrl,
              type: 'POST',
              data: {
                _token: csrfToken,
                filePath: filePath
              },
              success: function (response) {
                console.log("File successfully deleted:", response);
                pathsArray = pathsArray.filter(path => path !== filePath);
                $('#imageJson').val(JSON.stringify(pathsArray));
              },
              error: function (xhr, status, error) {
                console.error("Error deleting the file:", error);
              }
            });
          } else {
            console.error("Could not determine the file path to remove.");
          }
        });
      }
    });

    // Handle the "Remove" button for existing files
    document.querySelectorAll('.dz-remove').forEach(function (removeButton) {
      removeButton.addEventListener('click', function () {
        const photoPath = this.getAttribute('data-photo');
        console.log("Removing photo:", photoPath);

        if (photoPath) {
          // Remove the photo from the "imageJson" array
          pathsArray = pathsArray.filter(path => path !== photoPath);
          $('#imageJson').val(JSON.stringify(pathsArray));

          // Add the photo to the "removeImageJson" array
          let removePathsArray = $('#removeImageJson').val() ? JSON.parse($('#removeImageJson').val()) : [];
          removePathsArray.push(photoPath);
          $('#removeImageJson').val(JSON.stringify(removePathsArray));

          // Remove the preview from the Dropzone UI
          const dzPreviewElement = removeButton.closest('.dz-preview');
          if (dzPreviewElement) {
            dzPreviewElement.remove();
          }
        } else {
          console.error("Photo path not found.");
        }
      });
    });



  }

})();
