<?php
  require_once 'core/init.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include 'includes/include_head.php';?>
    <script src="./library/PDFjs/pdf.js"></script>
    <script src="./library/PDFjs/pdf.worker.js"></script>
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
      .td-back{
        background:#f4f4f5;
        border:2px solid #fff;
        padding-right: 20px;

      }
      .td-border{border:1px solid #eceaea;
        color:#3493c7;
        padding-right: 20px;
      }
      .nav-tabs .nav-item{
        font-family:'DIN_Light'!important;
        font-weight: bold;
      }
    </style>
    <script type="text/javascript">
      function addAlert(type,strongMsg,msg){
        $("#myNewAlert").remove();
        htm = `<div style="margin-top:50px;" class="alert alert-`+type+` alert-dismissible fade in text-center fixed-top" id="myNewAlert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>`+strongMsg+`!</strong> `+msg+`.
              </div>`;
        $("nav").after(htm);
        $("#myNewAlert").fadeTo(8000, 1000).slideUp(500, function(){
          $("#myNewAlert").slideUp(500);
        });
      }
      function loadjugement(docNum){
        $('#tbRslt3').html('');
        json = JSON.stringify({'docNum':docNum});
        $.ajax({
          url : "loadJugement.php",
          method : "POST",
          data : {json : json},
          success:function(data){
            $('#loader').modal("hide");
            
            json = JSON.parse(data);
            $.each(json.rows, function( index, value ){
              if(value.type == 1){
                jugeType = "حكم قطعي";
              }else if (value.type == 2) {
                jugeType = "حكم أولي بالاختصاص";
              }else if (value.type == 3) {
                jugeType = "حكم تمهيدي بإجراء بحث";
              }else if (value.type == 4) {
                jugeType = "حكم تمهيدي بإجراء خبرة";
              }
              row = '<tr class="td-border" id="'+value.id+'"><td class="text-center"><i class="fa fa-pencil" style="color:rgba(255,120,0,0.8);" aria-hidden="true"></i></td><td style="padding-right:20px;">'+
                        jugeType+'</td><td class="text-center">'+
                        value.numJugement +'</td><td class="text-center" >'+
                        value.jugeYear+'</td><td class="text-center" >'+
                        value.nb_pages+'</td><td class="text-center" id="'+value.fileId+'"><i class="fa fa-file-pdf-o" style="color:#E94B3C;" aria-hidden="true"></i></td></tr>';
              $('#tbRslt3').append(row);
            });
            $('#nb_juge').html(json.rows.length);
          }
        });
      }
      $(document).on('click','#search',function(){
        if($('#numeroDossier').val()!=''){
          $('#loader').modal("toggle");
          $('#numDossier').html('');
          $('#date_reg').html('');
          $('#entity').html('');
          $('#objet').html('');
          $('#juge').html('');
          $('#etat').html('');
          $('#results').attr('style','');
          data = {NumeroDossier: $('#numeroDossier').val(), IdJuridiction: 293};
          $.ajax({
            url : "info2.php",
            method : "POST",
            data : {json : data},
            success:function(data){
              json = JSON.parse(data).d;
              if(json.CarteDossier !=null){
                $('#numDossierInfo').html(json.CarteDossier.NumeroCompletDossier);
                $('#date_reg').html(json.CarteDossier.DateEnregistrement);
                num = json.CarteDossier.NumeroCompletDossier.split('/');
                entity = '';
                switch(num[1]) {
                  case '7101':
                  case '7102':
                  case '7103':
                  case '7116':
                      entity = 'القضاء الاستعجالي';
                    break;
                  case '7106':
                  case '7110':
                      entity = 'قضاء الالغاء';
                    break;
                  default:
                    entity = 'القضاء الشامل';
                }
                $('#entity').html(entity);
                $('#objet').html(json.CarteDossier.Objet);
                $('#juge').html(json.CarteDossier.JugeRapporteur.substring(0,json.CarteDossier.JugeRapporteur.length-10));
                $('#etat').html(json.CarteDossier.EtatDossier == 'محكوم' ?'محكوم':'رائج');
                $('#nb_atraf').html(json.ListParties.length);
                $('#nb_pross').html(json.ListDecisions.length);
                $.each(json.ListParties, function( index, value ) {
                  avocat = (value.AvocatsPartie!=null)?value.AvocatsPartie:'';
                  row = '<tr class="td-border"><td>'+
                            value.RolePartie+'</td><td>'+
                            value.NomPrenomPartie+'</td><td>'+
                            avocat +'</td></tr>';
                  $('#tbRslt').append(row);
                });
                $.each(json.ListDecisions, function( index, value ) {
                  hours = (value.HourAudience!=null)?value.HourAudience:'';
                  decision = (value.Decision!=null)?value.Decision:'';
                  dateNextAudience = (value.DateNextAudience!=null)?value.DateNextAudience:'';

                  row = '<tr class="td-border"><td>'+
                            value.DateAudience+'</td><td>'+
                            hours +'</td><td>'+
                            decision+'</td><td>'+
                            value.ContenuDecision+'</td><td>'+
                            dateNextAudience +'</td></tr>';
                  $('#tbRslt2').append(row);
                });
                loadjugement(json.CarteDossier.NumeroCompletDossier);
              }else{
                addAlert("danger","حدث خطأ","الملف المطلوب غير موجود المرجوا المحاولة برقم جديد");
              }
            }
          });
          $('#results').show();
        }
      });
      $(document).on('keypress','#numeroDossier',function(e) {
         if(e.keyCode == 13){
           $('#search').click();
         }
       });


      $(document).on('click','#addJugement',function(){
        var d=new Date();
        var y = d.getFullYear();
        $('#yearJuge').val(y);

        $('#fileTmpName').val("");
        $('#filelab').removeClass("bg-success");
        $('#filelab').removeClass("text-white");
        $('#displayFileName').html(" نسخة الماسح الضوئي");
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
            var nbpages = '';
            var pdfDoc = file.files[0];
            if (!pdfDoc) {
              return;
            }
            var fileReader = new FileReader();
            fileReader.onload = function (e) {
                pdf = new Uint8Array(e.target.result);
                PDFJS.getDocument({data: pdf}).then(function(pdf) {
                  nbpages = pdf.pdfInfo.numPages;
                  form_data.append('nbPages',nbpages);
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
                });
            };
            fileReader.readAsArrayBuffer(pdfDoc);
          }
        }
      });
      $(document).on('click','#jugementSave',function(){
        $('#yearJuge').removeAttr('style');
        $('#filelab').removeAttr('style');
        $('#numJuge').removeAttr('style');
        var t = true;
        var d = new Date();
        var thisYear = d.getFullYear();
        if($('#yearJuge').val() == ''){
          $('#yearJuge').attr('style','border:1px solid rgba(255,0,0,0.8);');
          t=false;
        }
        if(parseInt($('#yearJuge').val())>thisYear || parseInt($('#yearJuge').val())<1994){
          $('#yearJuge').attr('style','border:1px solid rgba(255,0,0,0.8);');
          t=false;
        }
        if(!document.getElementById('customFile').value){
          $('#filelab').attr('style','border:1px solid rgba(255,0,0,0.8);');
          t=false;
        }
        if($('#numJuge').val() == ''){
          $('#numJuge').attr('style','border:1px solid rgba(255,0,0,0.8);');
          t=false;
        }

        if(t){
          if(confirm("سيتم اضافة تسجيل جديد :")){
            var form_data={"type" : $('#jugeType').val(),"fileTmpName" : $('#fileTmpName').val(), "dossierAssocier": $('#numDossierInfo').html(),"yearJuge" : $('#yearJuge').val(),"numJuge" : $('#numJuge').val()};
            json = JSON.stringify(form_data);
            $.ajax({
              url : "setJugement.php",
              method : "POST",
              data : {json : json},
              success:function(data){
                json = JSON.parse(data);
                if(json.stat){
                  loadjugement($('#numDossierInfo').html());
                }
              }
            });
            $('#modalAddJugement').modal('toggle');
          }
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
            <input type="text" id="numeroDossier" class="form-control text-center" placeholder="رقم الملف">
            <div class="input-group-append">
              <button class="input-group-text" type='button' id='search' ><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
      <div id="results" style="display:none;">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#docInfo"><i class="fa fa-info-circle" aria-hidden="true"></i> معلومات الملف</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#atraf"><i class="fa fa-users" aria-hidden="true"></i> الأطراف <span id='nb_atraf' class="badge badge-danger"></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#pross"><i class="fa fa-history" aria-hidden="true"></i> الإجراءات <span id='nb_pross' class="badge badge-danger"></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#docNum"><i class="fa fa-tasks" aria-hidden="true"></i> الملف الالكتروني <span id='nb_docNum' class="badge badge-danger">9</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#jugeTab"><i class="fa fa-gavel" aria-hidden="true"></i> الاحكام <span id='nb_juge' class="badge badge-danger"></span></a>
          </li>
        </ul>
        <div class="tab-content" >
          <div id="docInfo" class="tab-pane fade">
            <br/><br/>
            <table class="text-right" style="width:90%;direction:rtl;margin:0 auto;font-size:1.5em;font-family:'DIN_Light'!important;font-weight: bold;">
              <tbody>
                <tr>
                  <td class="td-back">رقم الملف</td>
                  <td class="td-border" id="numDossierInfo"></td>
                </tr>
                <tr>
                  <td class="td-back">تاريخ التسجيل	</td>
                  <td class="td-border" id="date_reg"></td>
                </tr>
                <tr>
                  <td class="td-back">الشعبة</td>
                  <td class="td-border" id="entity"></td>
                </tr>
                <tr>
                  <td class="td-back">الموضوع</td>
                  <td class="td-border" id="objet"></td>
                </tr>
                <tr>
                  <td class="td-back">القاضي المقرر</td>
                  <td class="td-border" id="juge"></td>
                </tr>
                <tr>
                  <td class="td-back">حالة الملف</td>
                  <td class="td-border" id="etat"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div id="atraf" class="tab-pane fade">
            <br/><br/>
            <table class="table text-right table-bordered ">
              <thead>
                <tr class="text-center td-back" style="width:90%;direction:rtl;margin:0 auto;font-size:1.5em;font-family:'DIN_Light'!important;font-weight: bold;">
                  <th>الصفة</th>
                  <th>اسم الطرف</th>
                  <th>دفاع الطرف</th>
                </tr>
              </thead>
              <tbody id='tbRslt' style="font-family:'DIN_Light'!important;font-weight: bold;">

              </tbody>
            </table>
          </div>
          <div id="pross" class="tab-pane fade">
            <br/><br/>
            <table class="table text-right table-bordered ">
              <thead>
                <tr class="text-center td-back" style="width:90%;direction:rtl;margin:0 auto;font-size:1.5em;font-family:'DIN_Light'!important;font-weight: bold;">
                  <th>تاريخ الاجراء</th>
                  <th>الساعة</th>
                  <th>نوع المقرر</th>
                  <th>مضمون المقرر</th>
                  <th>الجلسة المقبلة</th>
                </tr>
              </thead>
              <tbody id='tbRslt2' style="font-family:'DIN_Light'!important;font-weight: bold;">

              </tbody>
            </table>
          </div>
          <div id="docNum" class="tab-pane fade">
            <br/><br/>
            <table class="table text-right table-bordered ">
              <thead>
                <tr class="text-center td-back" style="width:90%;direction:rtl;margin:0 auto;font-size:1.5em;font-family:'DIN_Light'!important;font-weight: bold;">
                  <th>تاريخ الاجراء</th>
                  <th>الساعة</th>
                  <th>نوع المقرر</th>
                  <th>مضمون المقرر</th>
                  <th>الجلسة المقبلة</th>
                </tr>
              </thead>
              <tbody id='tbRslt2' style="font-family:'DIN_Light'!important;font-weight: bold;">

              </tbody>
            </table>
          </div>
          <div id="jugeTab" class="tab-pane active">
            <br/><br/>
            <div class="row">
              <div class="col-4">
              </div>
              <div class="col-4">
                <button type="input" id="addJugement" class="btn btn-success btn-block">اضافة حكم <i class="fa fa-plus" aria-hidden="true"></i></button>
              </div>
            </div>
          </br/>
          <br/><br/>
          <table class="table text-right table-bordered " style="margin:0 auto;width:70%;">
            <thead>
            	<tr class="text-center td-back" style="direction:rtl;font-size:1.5em;font-family:'DIN_Light'!important;font-weight: bold;">
                  <th></th>
                  <th>نوع الحكم</th>
                  <th>الرقم</th>
                  <th>السنة</th>
                  <th>عدد الصفحات</th>
                  <th></th>
                </tr>
            </thead>
            <tbody id="tbRslt3" style="font-family:'DIN_Light'!important;font-weight: bold;">

            </tbody>
          </table>
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
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="yearJuge" style="float:right;">سنة الحكم :</label>
                        <input type="year" class="form-control" placeholder="سنة الحكم" id="yearJuge">
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="yearJuge" style="float:right;">رقم الحكم :</label>
                        <input type="year" class="form-control" placeholder="رقم الحكم..." id="numJuge">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="jugeType" style="float:right;">نوع الحكم :</label>
                    <select class="form-control" id="jugeType">
                      <option value='1'>حكم قطعي</option>
                      <option value='2'>حكم أولي بالاختصاص</option>
                      <option value='3'>حكم تمهيدي بإجراء بحث</option>
                      <option value='4'>حكم تمهيدي بإجراء خبرة</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="customFile" style="float:right;">سنة الحكم :</label>
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
        <div class="modal fade" id="loader" tabindex="-1" role="dialog" >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                <span class="float-right display-4">جاري التحميل...</span>
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
