<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>JS Bin</title>
  <script src="https://code.jquery.com/jquery-2.1.4.js"></script>
  <script src="./library/PDFjs/pdf.js"></script>
  <script src="./library/PDFjs/pdf.worker.js"></script>
</head>
<body>

  <div class="container">
    <p>Select pdf file to check number of pages and links inside.</>
    <form>
      <input type=file id=uploader>
    </form>
  </div>


<script id="jsbin-source-javascript" type="text/javascript">

  $(document).on('change','#uploader', function() {
    var pdfDoc = this.files[0];
    if (!pdfDoc) {
      return;
    }
    var fileReader = new FileReader();
    fileReader.onload = function (e) {
        pdf = new Uint8Array(e.target.result);
        PDFJS.getDocument({data: pdf}).then(function(pdf) {
          alert(pdf.pdfInfo.numPages);
        });
    };
    fileReader.readAsArrayBuffer(pdfDoc);
  });
</script>
</body>
</html>
