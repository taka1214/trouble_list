<script>
  document.getElementById("image_files").addEventListener("change", function(event) {
    // FileListをArrayに変換してforEachで処理
    Array.from(event.target.files).forEach(file => {
      resizeImage(file, 800, 800, function (resizedBlob) {
        let newFile = new File([resizedBlob], file.name, { type: file.type });
        replaceFileInFileList(event.target.files, file, newFile);
      });
    });
});

function resizeImage(file, maxWidth, maxHeight, callback) {
    let image = new Image();
  image.onload = function() {
    let canvas = document.createElement('canvas');

  let width = image.width;
  let height = image.height;
        if (width > height) {
            if (width > maxWidth) {
    height *= maxWidth / width;
  width = maxWidth;
            }
        } else {
            if (height > maxHeight) {
    width *= maxHeight / height;
  height = maxHeight;
            }
        }

  canvas.width = width;
  canvas.height = height;

  let ctx = canvas.getContext("2d");
  ctx.drawImage(image, 0, 0, width, height);

  canvas.toBlob(function(blob) {
    callback(blob);
        }, file.type);
    };
  image.src = URL.createObjectURL(file);
}


  function replaceFileInFileList(fileList, oldFile, newFile) {
    let dataTransfer = new DataTransfer();
    Array.from(fileList).forEach(file => {
    dataTransfer.items.add(file === oldFile ? newFile : file);
    });
  document.getElementById("image_files").files = dataTransfer.files;
}
</script>