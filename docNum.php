<?php
  require_once 'core/init.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include 'includes/include_head.php';?>
    <title>الملف الالكتروني</title>
    <style media="screen">
      .container{
        margin-top: 50px;
      }
      .title{
        margin-top: 40px;
        margin-bottom: 45px;
        position: relative;
        right:-18px;
        color: rgba(255, 0, 0, 0.5);
      }
    </style>
    <script type="text/javascript">
      $(document).on('click','#search',function(){
        $('#results').attr('style','');
      });
      $(document).on('click','#addJugement',function(){
        $('#modalAddJugement').modal("toggle");
      });
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
      $(document).on('click','#jugementSave',function(){
        if(confirm("سيتم اضافة تسجيل جديد :")){
          var form_data={"type" : $('#jugeType').val(),"fileTmpName" : $('#fileTmpName').val()};
          json = JSON.stringify(form_data);
          $.ajax({
            url : "setJugement.php",
            method : "POST",
            data : {json : json},
            success:function(data){

            }
          });
          $('#modalAddJugement').modal('toggle');
        }
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
      <h3 class="text-right title">
        <i class="fa fa-caret-left" aria-hidden="true"></i>
        <i class="fa fa-folder-open" aria-hidden="true"></i>
        <u> الاطلاع على الملف الالكتروني </u>
      </h3>

      <div class="row">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
          <div class="input-group mb-3" style="direction:LTR">
            <input type="text" class="form-control text-center" placeholder="رقم الملف">
            <div class="input-group-append">
              <button class="input-group-text" type='button' id='search' ><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
      <div id="results" style="display:;">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#docInfo">معلومات الملف</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#atraf">الأطراف <span id='nb_atraf' class="badge badge-danger">5</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#pross">الإجراءات <span id='nb_pross' class="badge badge-danger">7</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#docNum">الملف الالكتروني <span id='nb_docNum' class="badge badge-danger">9</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#juge">الاحكام <span id='nb_juge' class="badge badge-danger">3</span></a>
          </li>
        </ul>
        <div class="tab-content" >
          <div id="docInfo" class="tab-pane fade">
            <h4>معلومات الملف</h4>
          </div>
          <div id="atraf" class="tab-pane fade">
            <h4>الأطراف</h4>
          </div>
          <div id="pross" class="tab-pane fade">
            <h4>الإجراءات</h4>
          </div>
          <div id="docNum" class="tab-pane fade">
            <h4>الملف الالكتروني</h4>
          </div>
          <div id="juge" class="tab-pane active">
            <br/><br/>
            <div class="row">
              <div class="col-4">
              </div>
              <div class="col-4">
                <button type="input" id="addJugement" class="btn btn-success btn-block">اضافة حكم <i class="fa fa-plus" aria-hidden="true"></i></button>
              </div>
            </div>
          </br/>
          </div>
      </div>

      <div class="outbody">
        <div class="modal fade" id="modalAddJugement" tabindex="-1" role="dialog" >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header row">
                <div class="text-right col-6">
                  <h5 class="modal-title">اضافة نسخة الحكم</h5>
                </div>
                <div class="col-5"></div>
                <div class="col-1">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
              <div class="modal-body">
                <form style="width:80%;margin:0 auto;">
                  <div class="form-group">
                    <label for="jugeType" style="float:right;">نوع الحكم :</label>
                    <select class="form-control" id="jugeType">
                      <option value='1'>حكم قطعي</option>
                      <option value='2'>حكم أولي بالاختصاص</option>
                      <option value='3'>حكم تمهيدي بإجراء بحث</option>
                      <option value='4'>حكم تمهيدي بإجراء خبرة</option>
                    </select>
                  </div>
                  <div class=" bnt-control">
                      <div id="upload&Progressbar">
                        <div class="form-group" id="fileUpload">
                              <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="customFile" name="filename">
                                <label class="custom-file-label text-center" for="customFile" id="filelab"><i style="font-size:12px;" class="fa fa-clone" aria-hidden="true" id="displayFileName"> نسخة الماسح الضوئي</i></label>
                                <input type="hidden" value="" id="fileTmpName">
                              </div>
                        </div>
                        <div class="form-group" style="display:none;" id="progersUpload">
                              <div class="small-text">
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
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="margin-left:5px;" id='jugementSave'>اضافة</button>
                <button type="button" class="btn btn-secondary" id="dismiss_modal" data-dismiss="modal">الغاء</button>
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
