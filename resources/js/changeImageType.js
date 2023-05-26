document.getElementById("image_files").addEventListener("change", function(event) {
  Array.from(event.target.files).forEach(file => {
      if(file.type == "image/heic") {
          heic2any({
              blob: file,
              toType: "image/jpeg",
              quality: 0.8
          }).then(function(conversionResult) {
              // conversionResult is a blob. Convert it to a file and replace original file in the FileList.
              let newFile = new File([conversionResult], file.name, {type: "image/jpeg"});
              replaceFileInFileList(event.target.files, file, newFile);
          });
      }
  });
});

// Function to replace a file in a FileList
function replaceFileInFileList(fileList, oldFile, newFile) {
  // Currently, there's no direct way to modify a FileList object. We'll need to create a new DataTransfer object.
  let dataTransfer = new DataTransfer();

  // Add all files from the fileList to the dataTransfer object, replacing the oldFile with the newFile.
  Array.from(fileList).forEach(file => {
      dataTransfer.items.add(file === oldFile ? newFile : file);
  });

  // Replace the existing FileList with the one from the dataTransfer object.
  document.getElementById("image_files").files = dataTransfer.files;
}