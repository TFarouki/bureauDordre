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
      $(document).ready(function(){
        $('#progersUpload').hide();
      });

      $(document).on('change','#customFile',function(){
        var file = document.getElementById('customFile');
        var lab = document.getElementById('filelab');
        var fullPath = file.value;
        if (fullPath) {
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
        }
        $('#progersUpload').show();
        $('#fileUpload').hide();
      });
    </script>
  </head>
  <body>
    <?php

      if (Session::exists("success")) {
        echo Session::flash("success");
      }


      $user = new User();
      if($user->isLoggedIn()){
        include 'includes/nav.php';
    ?>
    <div class="container">
      <div class="form-group row" id="fileUpload">
            <div class="col-3 custom-file mb-3">
              <input type="file" class="custom-file-input" id="customFile" name="filename">
              <label class="custom-file-label text-left" for="customFile" id="filelab"><i class="fa fa-clone" aria-hidden="true"> نسخة الماسح الضوئي</i></label>
            </div>
      </div>
      <div class="form-group row" id="progersUpload">

            <div class="col-3 small-text">
              <span style="float:right;font:12px;font-weight: bold;" id="fileUploadName"></span><br/>
              <div class="row text-right">
                <div class="col-3"><span id="percentage">40%</span></div>
                <div class="col-5"></div>
                <div class="col-4"><span id="size"></span></div>
              </div>
              <div class="progress " style="height:2px">
                <div class="progress-bar" style="width:0%;height:2px"></div>
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
