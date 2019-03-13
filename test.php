<?php
  require_once 'core/init.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include 'includes/include_head.php';?>
    <title></title>
    <style>
      .small-text{
        font-size: 10px;
      }
    </style>
    <script type="text/javascript">
      $(document).on('change','#customFile',function(){

        var file = document.getElementById('customFile');
        var hidden = $('#fileTmpName').val();
        var lab = document.getElementById('filelab');
        var fullPath = file.value;
        if (fullPath) {
          $('#progersUpload').show();
          $('#fileUpload').hide();
            var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
            var filename = fullPath.substring(startIndex);
            if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                filename = filename.substring(1);
            }
            //lab.innerHTML = filename;
            $('#fileUploadName').html(filename);
            sizefile = $('#customFile')[0].files[0].size;
            sizefile = sizefile / (1024*1024);
            $('#size').html(sizefile.toFixed(2) + " Mo");

            var property = file.files[0];
            var form_data = new FormData();
            form_data.append('hidden',hidden);
            var file_name = property.name;
            var ext = file_name.split('.').pop().toLowerCase();
            var allowExt = ['pdf','doc','docx','bmp','gif','jpeg','jpg','png','tif','tiff','xls','xlsx','mdb'];
            if($.inArray(ext,allowExt) == -1){
              alert('المرجوا التأكد من صيغة الملف..!');
              $.ajax({
                url:"upfile.php",
                method:"POST",
                data : form_data,
                contentType : false,
                processData : false,
              });
              $('#fileTmpName').val("");
              $('#fileUpload').show();
              $('#progersUpload').hide();
              $('#filelab').removeClass("bg-success text-white");
              $('#displayFileName').html(" نسخة الماسح الضوئي");
            }else{
              form_data.append('file',property);
              $.ajax({
                xhr: function(){
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            percentComplete = percentComplete.toFixed(2)+'%';
                            $('#progressbar').width(percentComplete);
                            $('#percentage').html(percentComplete);
                            $('#size').width(evt.total);
                        }
                   }, false);
                   return xhr;
                },
                url:"upfile.php",
                method:"POST",
                data : form_data,
                contentType : false,
                processData : false,
                beforeSend:function(){
                  $('#progressbar').width('0%')
                },
                success:function(data){
                  $('#fileUpload').show();
                  $('#progersUpload').hide();
                  $('#filelab').addClass("bg-success text-white");
                  $('#displayFileName').html(" " + file_name);
                  $('#fileTmpName').val(data);
                }
              });
            }
        }
      });
    </script>
  </head>
  <body>
    <?php

      if(Session::exists("success")){
        echo Session::flash("success");
      }

      $user = new User();
      if($user->isLoggedIn()){
        include 'includes/nav.php';
    ?>
    <div class="container">
      <div id="upload&Progressbar">
        <div class="form-group row" id="fileUpload">
              <div class="col-3 custom-file mb-3">
                <input type="file" class="custom-file-input" id="customFile" name="filename">
                <label class="custom-file-label text-center" for="customFile" id="filelab"><i style="font-size:12px;" class="fa fa-clone" aria-hidden="true" id="displayFileName"> نسخة الماسح الضوئي</i></label>
                <input type="hidden" value="" id="fileTmpName">
              </div>
        </div>
        <div class="form-group row" style="display:none;" id="progersUpload">

              <div class="col-3 small-text">
                <span style="float:right;font:12px;font-weight: bold;" id="fileUploadName"></span><br/>
                <div class="row text-right">
                  <div class="col-3"><span id="percentage"></span></div>
                  <div class="col-5"></div>
                  <div class="col-4"><span id="size"></span></div>
                </div>
                <div class="progress " style="height:2px">
                  <div class="progress-bar" id="progressbar" style="width:0%;height:2px"></div>
                </div>
              </div>
          </div>

      </div>

    </div>

    <?php
      }else{
        Redirect::to("login.php");
      }
    ?>
  </body>
</html>
