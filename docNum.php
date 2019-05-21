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
      .caret-off::after {
         visibility:hidden;
      }
      .dropdown-item:hover{
        background-color: rgba(255,255,255,0.5);
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
      function loadjugement(docNum,highlight=false){
        $('#tbRslt3').html('');
        json = JSON.stringify({'docNum':docNum});
        $.ajax({
          url : "loadJugement.php",
          method : "POST",
          data : {json : json},
          success:function(data){
            //$('#loader').modal("hide");
            json = JSON.parse(data);
            if(json.count>0){
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
                dropup = `<div class="dropup">
                            <a class="dropdown-toggle caret-off" data-toggle="dropdown" href="#" style="color:#E94B3C;">
                              <i class="fa fa-file-pdf-o" style="color:#E94B3C;" aria-hidden="true"></i>
                            </a>
                            <div class="dropdown-menu bg-primary   text-right">
                              <a class="dropdown-item opt1" href="#" style="color:#fff"><i class="fa fa-folder-open-o" aria-hidden="true"></i> الاطلاع على النسخة</a>
                              <a class="dropdown-item opt2" href="#" style="color:#fff"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> تعديل النسخة</a>
                            </div>
                          </div>`;
                row = '<tr class="td-border" id="'+value.id+'"><td class="text-center"><i class="fa fa-pencil opt3" style="color:rgba(255,120,0,0.8);" aria-hidden="true"></i></td><td style="padding-right:20px;">'+
                          jugeType+'</td><td class="text-center">'+
                          value.numJugement +'</td><td class="text-center" >'+
                          value.jugeYear+'</td><td class="text-center" >'+
                          value.nb_pages+'</td><td class="text-center opt0" id="'+value.fileId+
                          '">'+dropup+'</td></tr>';
                $('#tbRslt3').append(row);
              });
              $('#nb_juge').html(json.rows.length);
              if(highlight){
                $('#tbRslt3 tr:first').attr("style","background-color:rgba(0,200,160,0.7);color:#fff;");
              }
            }else{
              addAlert("warning","تنبيه !..  ","هدا الملف لا يتوفر على احكام ممسوحة الكترونيا ");
            }
          }
        });
      }
      $(document).on('click','#search',function(){
        if($('#numeroDossier').val()!=''){
          //$('#loader').modal("toggle");
          $('#numDossier').html('');
          $('#date_reg').html('');
          $('#entity').html('');
          $('#objet').html('');
          $('#juge').html('');
          $('#etat').html('');
          $('#results').attr('style','');
          data = {NumeroDossier: $('#numeroDossier').val(), IdJuridiction: 293};

          /*$.ajax({
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
          */
          loadjugement($('#numeroDossier').val());
          $('#results').show();
        }
      });
      $(document).on('keypress','#numeroDossier',function(e) {
         if(e.keyCode == 13){
           $('#search').click();
         }
       });
      $(document).on('click','#addJugement',function(){
        $('#numJuge').val('');
        $('#jugeType').val('1');
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
      $(document).on('change','#customFile2',function(){
        var file = document.getElementById('customFile2');

        var hidden = $('#fileTmpName2').val();
        var lab = document.getElementById('filelab2');
        var fullPath = file.value;
        if (fullPath) {
          $('#progersUpload2').show();
          $('#fileUpload2').hide();
          var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
          var filename = fullPath.substring(startIndex);
          if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
              filename = filename.substring(1);
          }
          //lab.innerHTML = filename;
          $('#fileUploadName2').html(filename);
          sizefile = $('#customFile2')[0].files[0].size;
          sizefile = sizefile / (1024*1024);
          $('#size2').html(sizefile.toFixed(2) + " Mo");

          var property = file.files[0];
          var form_data = new FormData();
          form_data.append('hidden',hidden);
          var file_name = property.name;
          var ext = file_name.split('.').pop().toLowerCase();
          var allowExt = ['pdf','doc','docx','bmp','gif','jpeg','jpg','png','tif','tiff','xls','xlsx','mdb'];

          if($.inArray(ext,allowExt) == -1){
            alert('المرجوا التأكد من صيغة الملف..!');
            $('#fileTmpName2').val("");
            $('#fileUpload2').show();
            $('#progersUpload2').hide();
            $('#filelab2').removeClass("bg-success text-white");
            $('#displayFileName2').html(" نسخة الماسح الضوئي");
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
                                $('#Progressbar2').width(percentComplete);
                                $('#percentage2').html(percentComplete);
                                $('#size2').width(evt.total);
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
                      $('#progressbar2').width('0%')
                    },
                    success:function(data){
                      $('#fileUpload2').show();
                      $('#progersUpload2').hide();
                      $('#filelab2').addClass("bg-success text-white");
                      $('#displayFileName2').html(" " + file_name);
                      $('#fileTmpName2').val(data);
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
            var form_data={"type" : $('#jugeType').val(),"fileTmpName" : $('#fileTmpName').val(), "dossierAssocier": $('#numeroDossier').val() /*$('#numDossierInfo').html()*/ ,"yearJuge" : $('#yearJuge').val(),"numJuge" : $('#numJuge').val()};
            json = JSON.stringify(form_data);
            $.ajax({
              url : "setJugement.php",
              method : "POST",
              data : {json : json},
              success:function(data){
                json = JSON.parse(data);
                if(json.stat){
                  //loadjugement($('#numDossierInfo').html());
                  loadjugement($('#numeroDossier').val(),true);
                  //$('#tbRslt3 tr:first').addClass("bg-success");
                }
              }
            });
            $('#modalAddJugement').modal('toggle');
          }
        }
      });
      $(document).on('click','.opt0 .opt1',function(){
        var id=$(this).closest('td').attr('id');
        json = JSON.stringify({"id":id});
        $.ajax({
          url : "getDocJuge.php",
          method : "POST",
          data : {json : json},
          success:function(data){
            json = JSON.parse(data);
            if(json.stat){
              $('#embedPdf').removeAttr('src');
              $('#embedPdf').attr('src',json.path);
              $('#pdfModal').modal('toggle');
            }else{
              addAlert("danger","تنبيه !","حدث خطأ اثناء عرض النسخة الالكترونية");
            }
          }
        });
      });
      $(document).on('click','.opt0 .opt2',function(){
        $('#idChangeCopy').val($(this).closest('tr').attr('id'));
        $('#fileTmpName2').val("");
        $('#filelab2').removeClass("bg-success");
        $('#filelab2').removeClass("text-white");
        $('#displayFileName2').html(" نسخة الماسح الضوئي");
        $('#modalChangeCopy').modal('toggle');
      });
      $(document).on('click','#jugementSave2',function(){
        if(confirm("سيتم اضافة تسجيل جديد :")){
          var upfile = JSON.parse($('#fileTmpName2').val());
          var form_data={"id":$("#idChangeCopy").val(),"type" : $('#jugeType').val(),"dossierAssocier": $('#numeroDossier').val() /*$('#numDossierInfo').html()*/,"upfile":upfile};
          json = JSON.stringify(form_data);
          $.ajax({
            url : "changeCopyJugement.php",
            method : "POST",
            data : {json : json},
            success:function(data){
              json = JSON.parse(data);
              if(json.stat){
                addAlert("success","لقد تم التعديل بنجاح","");
              }else {
                addAlert("danger","حدث خطأ اثناء التعديل","");
              }
            }
          });
          loadjugement($("#numeroDossier").val());
          $('#modalChangeCopy').modal('toggle');
        }
      });
      $(document).on('click','.opt3',function(){
        $('#idChangeJuge').val($(this).closest('tr').attr('id'));
        tr=$(this).closest('tr');
        type = tr.children('td:nth-child(2)').html();
        $("#yearJuge2").val(tr.children('td:nth-child(4)').html());
        $("#numJuge2").val(tr.children('td:nth-child(3)').html());
        $("#nb_pages2").val(tr.children('td:nth-child(5)').html());
        if(type == "حكم قطعي"){
          type = 1;
        }else if (type == "حكم أولي بالاختصاص") {
          type = 2;
        }else if (type == "حكم تمهيدي بإجراء بحث") {
          type = 3;
        }else if (type == "حكم تمهيدي بإجراء خبرة") {
          type = 4;
        }
        $("#jugeType2").val(type);
        $('#modalEditJugement').modal('toggle');

      });
      $(document).on('click','#jugementSave3',function(){
        id=$('#idChangeJuge').val();
        json = JSON.stringify({"id":id,"yearJuge":$("#yearJuge2").val(),"numJuge":$("#numJuge2").val(),"nb_pages":$("#nb_pages2").val(),"jugeType":$("#jugeType2").val()});
        $.ajax({
          url : "changeDataJugement.php",
          method : "POST",
          data : {json : json},
          success:function(data){
            json = JSON.parse(data);
            if(json.stat){
              loadjugement($("#numeroDossier").val());
              addAlert("success","لقد تم تحديث المعطيات بنجاح","");
            }else{
              addAlert("warning","لقد حدث خطأ اثناء تحديث المعطيات","");
            }
          }
        });
        $('#modalEditJugement').modal('toggle');
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
                        <label for="numJuge" style="float:right;">رقم الحكم :</label>
                        <input type="number" class="form-control" placeholder="رقم الحكم..." id="numJuge">
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
                    <label for="customFile" style="float:right;">نسخة الحكم :</label>
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
        <div class="modal fade" id="modalEditJugement" tabindex="-1" role="dialog" >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header row">
                <div class="text-right col-6">
                  <h5 class="modal-title">تعديل معلومات نسخة الحكم</h5>
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
                        <label for="yearJuge2" style="float:right;">سنة الحكم :</label>
                        <input type="year" class="form-control" placeholder="سنة الحكم" id="yearJuge2">
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="numJuge2" style="float:right;">رقم الحكم :</label>
                        <input type="number" class="form-control" placeholder="رقم الحكم..." id="numJuge2">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="nb_pages2" style="float:right;">عدد صفحات الحكم :</label>
                    <input type="number" class="form-control" placeholder="عدد صفحات الحكم" id="nb_pages2">
                  </div>
                  <div class="form-group">
                    <label for="jugeType2" style="float:right;">نوع الحكم :</label>
                    <select class="form-control" id="jugeType2">
                      <option value='1'>حكم قطعي</option>
                      <option value='2'>حكم أولي بالاختصاص</option>
                      <option value='3'>حكم تمهيدي بإجراء بحث</option>
                      <option value='4'>حكم تمهيدي بإجراء خبرة</option>
                    </select>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="margin-left:5px;" id='jugementSave3'>اضافة</button>
                <button type="button" class="btn btn-secondary" id="dismiss_modal3" data-dismiss="modal">الغاء</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal" id="pdfModal">
          <embed id="embedPdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">

        </div>
        <div class="modal fade" id="modalChangeCopy" tabindex="-1" role="dialog" >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header row">
                <div class="text-right col-6">
                  <h5 class="modal-title">تغيير نسخة الحكم</h5>
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
                    <label for="customFile" style="float:right;">نسخة الحكم :</label>
                    <div class=" bnt-control">
                        <div id="upload&Progressbar2">
                          <div class="form-group" id="fileUpload2">
                                <div class="custom-file mb-3">
                                  <input type="file" class="custom-file-input" id="customFile2" name="filename">
                                  <label class="custom-file-label text-center" for="customFile2" id="filelab2"><i style="font-size:12px;" class="fa fa-clone" aria-hidden="true" id="displayFileName2"> نسخة الماسح الضوئي</i></label>
                                  <input type="hidden" value="" id="fileTmpName2">
                                </div>
                          </div>
                          <div class="form-group" style="display:none;" id="progersUpload2">
                                <div class="small-text">
                                  <span style="float:right;font:12px;font-weight: bold;" id="fileUploadName2"></span><br/>
                                  <div class="row text-right">
                                    <div class="col-3"><span id="percentage2"></span></div>
                                    <div class="col-5"></div>
                                    <div class="col-4"><span id="size2"></span></div>
                                  </div>
                                  <div class="progress " style="height:2px">
                                    <div class="progress-bar" id="progressbar2" style="width:0%;height:2px"></div>
                                  </div>
                                </div>
                            </div>

                        </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="margin-left:5px;" id='jugementSave2'>اضافة</button>
                <button type="button" class="btn btn-secondary" id="dismiss_modal2" data-dismiss="modal">الغاء</button>
              </div>
            </div>
          </div>
        </div>

        <input type="hidden" id="idChangeCopy" name="" value="">
        <input type="hidden" id="idChangeJuge" name="" value="">
      </div>

    <?php
      }else{
        Redirect::to("login.php");
      }
    ?>
  </body>
</html>
