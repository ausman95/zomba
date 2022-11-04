let uploader = new plupload.Uploader({
    runtimes: 'html5,flash,silverlight,html4',

    browse_button: 'browse-btn', // you can pass in id...
    container: document.getElementById('files-container'), // ... or DOM Element itself

    url: uploadUrl,

    filters: {
        max_file_size: '10mb',
        mime_types: mimes,
        chunk_size: '5mb',
    },

    // Flash settings
    flash_swf_url: '/plupload/js/Moxie.swf',

    // Silverlight settings
    silverlight_xap_url: '/plupload/js/Moxie.xap',


    init: {
        PostInit: function () {
            document.getElementById('file-list').innerHTML = "";
            document.getElementById('upload-btn').onclick = function () {
                uploader.start();
                return false;
            };
        },

        FilesAdded: function (up, files) {
            plupload.each(files, function (file) {
                let markup = '<li class="list-group-item" id="' + file.id + '">';
                markup += '<i class="fa fa-file"></i>&nbsp;';
                markup += file.name;
                markup += ' (' + plupload.formatSize(file.size) + ') <b></b>';
                markup += "</li>"
                document.getElementById('file-list').innerHTML += markup;
            });
        },

        UploadProgress: function (up, file) {
            document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        },

        Error: function (up, err) {
            $("#" + err.file.id).hide(10);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'File upload failed: Error #' + err.code + " - " + err.message,
            })
        },

        FileUploaded: function (up, file, result) {
            $("#" + file.id).hide(10);
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "File successfully uploaded : " + file.name,
            })
        },
    }
});

uploader.init();

console.log(uploader);
