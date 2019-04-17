<?php require_once 'core/init.php'; ?>
<!DOCTYPE html>
  <html lang="en">
    <head>
      <?php include 'includes/include_head.php';?>
      <style media="screen">
          .small-text{
            font-size: 10px;
          }
          .card-header,.btn-block{
           margin:0;
           padding:0;
          }
          #accordion{
            margin: 30px;
          }
          #accordion .card{
            border-style: none;
          }
          hr{
            margin-top: 30px;
            margin-bottom: 30px;
            height: 2px;
            background-color: rgba(0,0,0,.05);
          }
          .title{
            margin-top: 40px;
            margin-bottom: 45px;
            position: relative;
            right:-18px;
            color: rgba(255, 0, 0, 0.5);
          }
          .bt-marge{
            border-bottom: 1px solid rgba(0,0,0,0.5);
            padding: 0;
          }
          .table td,.table th{
            padding:0;
          }
          .controles .btn{
            width: 180px;
            margin: 10px;
          }
          .bnt-control{
          margin: 0 auto;
          width: 24%;
          padding: 0;
          height: 40px;
        }
      </style>
      <script type="text/javascript">
        $(document).on("focus","#dateArriver",function(){
          var d = new Date();
          var month = d.getMonth()+1;
          var day = d.getDate();
          var output =(day<10 ? '0' : '') + day + '/' +
              (month<10 ? '0' : '') + month + '/' + d.getFullYear() ;
          this.value = output;
          this.type="date";
        });
        $(document).on("blur","#dateArriver",function(){
          this.type="text";
        });
        function isset(obj){
          return (typeof obj !== "undefined" && obj)?true:false;
        }
        function addAlert(type,strongMsg,msg){
          htm = `<div class="alert alert-`+type+` alert-dismissible fade in text-center" id="myNewAlert">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>`+strongMsg+`!</strong> `+msg+`.
                </div>`;
          $("nav").after(htm);
          $("#myNewAlert").fadeTo(8000, 1000).slideUp(500, function(){
            $("#myNewAlert").slideUp(500);
          });
        }
        $(document).ready(function(){
          $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#dataTable tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });
        function ignoreNull(variable) {
          return (variable === null)?"":variable;
        }
        function reload(from,lenght){
          if(from == 0 && lenght == 0){
            from = $('#dataTable tr').length;
            lenght = Math.round($('#dataTable tr').length/2);
          }
          var json = JSON.stringify({'from':from,'lenght':lenght});
          $.ajax({
            url:"load.php",
            method:"POST",
            data:{json:json},
            success:function(data){
              var json = JSON.parse(data);
              //var htm = $("#dataTable").html();;
              for (var i = 0; i < json.length; i++) {
                td='';
                td2='';
                if(json[i].fileID!= null){
                  td=`<td name="file"><a href='#' style='color:#E94B3C;'><i class="fa fa-file-pdf-o" aria-hidden="true" data-toggle="tooltip" title="الاطلاع على النسخة الضوئية!"></i></a></td>`;
                }else{
                  td=`<td name="addFile"><a href='#' style='color:#777;'><i class="fa fa-file-pdf-o" aria-hidden="true" data-toggle="tooltip" title="اضافة النسخة الضوئية!"></i></a></td>`
                }
                if(json[i].dateRemaind != null){
                  td2=`<td name='remaind'><a href='#' style='color:#88B04B;'><i class="fa fa-bell" aria-hidden="true" data-toggle="tooltip" title="ضبط تنبيه!"></i></a></td>`;
                }else{
                  td2=`<td name='remaind'><a href='#' style='color:#88B04B;'><i class="fa fa-bell-o" aria-hidden="true" data-toggle="tooltip" title="ضبط تنبيه!"></i></a></td>`;
                }
                row = `
                      <tr id="`+json[i].num_ordre+`">
                        <td name="editRow"><a href='#' style='color:#EFC050;'><i class='fa fa-pencil' aria-hidden='true' data-toggle="tooltip" title="تغيير!"></i></a></td>
                        <td name="multRow"><a href='#' style='color:#006E6D;'><i class="fa fa-files-o" aria-hidden="true" data-toggle="tooltip" title="نسخ!"></i></a></td>
                        <td>`+parseInt(ignoreNull(json[i].num_ordre.substring(json[i].num_ordre.length-10)))+`</td>
                        <td>`+ignoreNull(json[i].direction)+`</td>
                        <td>`+ignoreNull(json[i].dateArriver)+`</td>
                        <td>`+ignoreNull(json[i].expediteur)+`</td>
                        <td>`+ignoreNull(json[i].destinataire)+`</td>
                        <td>`+ignoreNull(json[i].type)+`</td>
                        <td>`+ignoreNull(json[i].objet)+`</td>
                        <td>`+ignoreNull(json[i].dossierAssocier)+`</td>`+
                        td+`
                        <td><a href='#'><i class="fa fa-file-word-o" aria-hidden="true" data-toggle="tooltip" title="تحميل الارسالية!"></i></a></td>`+
                        td2+`
                        <td><a href='#' style='color:#6B5B95;'><i class="fa fa-user-plus" aria-hidden="true" data-toggle="tooltip" title="احالة على!"></i></a></td>
                      </tr>
                `;
                if($('#dataTable').lenght){
                  $('#dataTable tr:last').after(row).fadeIn("slow");
                }else {
                  $('#dataTable').append(row).fadeIn("slow");
                }
              }
            }
          });
        }
        $(document).ready(function(){
              $.ajax({
                url:"atraf.php",
                method:"POST",
                success:function(data){
                  var json = JSON.parse(data);
                  var htm = "";
                  for (var i = 0; i < json.length; i++) {
                    htm+= '<option value="'+json[i].taraf+'">';
                  }
                  $("#lisDataAtraf").html(htm);
                }
              });
              $.ajax({
                url:"naw3.php",
                method:"POST",
                success:function(data){
                  var json = JSON.parse(data);
                  var htm = "";
                  for (var i = 0; i < json.length; i++) {
                    htm+= '<option value="'+json[i].naw3+'">';
                  }
                  $("#lisDataNaw3").html(htm);
                }
              });
              $.ajax({
                url:"mawdo3.php",
                method:"POST",
                success:function(data){
                  var json = JSON.parse(data);
                  var htm = "";
                  for (var i = 0; i < json.length; i++) {
                    htm+= '<option value="'+json[i].mawdo3+'">';
                  }
                  $("#lisDataMawdo3").html(htm);
                }
              });
              reload(0,10);
        });
        $(document).on('click', '#annulation', function(e){
          $("#expediteur").val('');
          $("#destinataire").val('');
          $("#type").val('');
          $("#object").val('');
          $("#dossierAssocier").val('');
          $("#dateArriver").val('');
        });
        function getok(){
          var file = document.getElementById('customFile');
          var lab = document.getElementById('filelab');
          var fullPath = file.value;
          if (fullPath) {
              var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
              var filename = fullPath.substring(startIndex);
              if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
                  filename = filename.substring(1);
              }
              lab.innerHTML = filename;
          }
        }
        function setremaind(){
          var hidden1 = document.getElementById('remaindDate');
          var hidden2 = document.getElementById('remaindText');
          hidden1.value = document.getElementById('Rdate').value;
          hidden2.value = document.getElementById('Rtext').value;
          document.getElementById('dismiss_modal').click();
        }
        function isChecked(x){
            $("#sendorinbox2").attr('checked', $(x).children().hasClass('off'));
            if($(x).children().hasClass('off')){
              $('#destinataire').val('رئيس مصلحة كتابة الضبط بالمحكمة الادارية بأكادير');
              $('#expediteur').val('');

            }else{
              $('#expediteur').val('رئيس مصلحة كتابة الضبط بالمحكمة الادارية بأكادير');
              $('#destinataire').val('');
            }
        }

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
        $(document).on('click', '#submit', function(e){
          if(confirm("سيتم اضافة تسجيل جديد :")){
            var form_data={
                            "sendorinbox" : $('#sendorinbox').is(':checked'),
                            "expediteur" : $('#expediteur').val(),
                            "destinataire" : $('#destinataire').val(),
                            "type" : $('#type').val(),
                            "object" : $('#object').val(),
                            "dossierAssocier" : $('#dossierAssocier').val(),
                            "dateArriver" : $('#dateArriver').val(),
                            "fileTmpName" : $('#fileTmpName').val(),
                            "remaindDate" : $('#remaindDate').val(),
                            "remaindText" : $('#remaindText').val(),
                            "lastId" : $("#dataTable tr:first td:nth-child(3)").html()
                          };
            json = JSON.stringify(form_data);
            $.ajax({
              url : "setRow.php",
              method : "POST",
              data : {json : json},
              success:function(data){
                json=JSON.parse(data);
                if(isset(json.json)){
                    row = "";
                  for (var i = 0; i < json.json.length; i++) {
                    if(json.json[i].fileID!= null){
                      td=`<td name="file"><a href='#' style='color:#E94B3C;'><i class="fa fa-file-pdf-o" aria-hidden="true" data-toggle="tooltip" title="الاطلاع على النسخة الضوئية!"></i></a></td>`;
                    }else{
                      td=`<td name="addFile"><a href='#' style='color:#777;'><i class="fa fa-file-pdf-o" aria-hidden="true" data-toggle="tooltip" title="اضافة النسخة الضوئية"!"></i></a></td>`;
                    }
                    if(json.json[i].dateRemaind != null){
                      td2=`<td name='remaind'><a href='#' style='color:#88B04B;'><i class="fa fa-bell" aria-hidden="true" data-toggle="tooltip" title="ضبط تنبيه!"></i></a></td>`;
                    }else{
                      td2=`<td name='remaind'><a href='#' style='color:#88B04B;'><i class="fa fa-bell-o" aria-hidden="true" data-toggle="tooltip" title="ضبط تنبيه!"></i></a></td>`;
                    }
                    row += `
                      <tr id="`+json.json[i].num_ordre+`">
                        <td name="editRow"><a href='#' style='color:#EFC050;'><i class='fa fa-pencil' aria-hidden='true' data-toggle="tooltip" title="تغيير!"></i></a></td>
                        <td name="multRow"><a href='#' style='color:#006E6D;'><i class="fa fa-files-o" aria-hidden="true" data-toggle="tooltip" title="نسخ!"></i></a></td>
                        <td>`+parseInt(ignoreNull(json.json[i].num_ordre.substring(json.json[i].num_ordre.length-10)))+`</td>
                        <td>`+ignoreNull(json.json[i].direction)+`</td>
                        <td>`+ignoreNull(json.json[i].dateArriver)+`</td>
                        <td>`+ignoreNull(json.json[i].expediteur)+`</td>
                        <td>`+ignoreNull(json.json[i].destinataire)+`</td>
                        <td>`+ignoreNull(json.json[i].type)+`</td>
                        <td>`+ignoreNull(json.json[i].objet)+`</td>
                        <td>`+ignoreNull(json.json[i].dossierAssocier)+`</td>`+
                        td+
                        `<td><a href='#'><i class="fa fa-file-word-o" aria-hidden="true" data-toggle="tooltip" title="تحميل الارسالية!"></i></a></td>`+
                        td2+
                        `<td><a href='#' style='color:#6B5B95;'><i class="fa fa-user-plus" aria-hidden="true" data-toggle="tooltip" title="احالة على!"></i></a></td>
                      </tr>
                    `;
                  }
                  ($('#dataTable tr').length>0)?$('#dataTable tr:first').before(row):$('#dataTable').html(row);
                  $('#dataTable tr:first').addClass("table-success");
                }else if(isset(json.validation)){
                  addAlert("warning","تنبيه",json.validation);
                }else if (isset(json.insert)) {
                  addAlert("danger","تحدير",json.insert);
                }else if (isset(json.error)) {
                  addAlert("danger","تحدير",json.error);
                }
                $('#fileTmpName').val("");
                $('#filelab').removeClass("bg-success");
                $('#filelab').removeClass("text-white");
                $('#remaindDate').val("");
                $('#remaindText').val("");
                $('#customFile').val("");
                $('#displayFileName').html(" نسخة الماسح الضوئي");
              }


            });
          }
        });
        $(document).on('click','td[name="file"]',function(e){
          e.preventDefault();
          if($("#pdfModal").css('display')== 'none'){
            var id=$(this).closest('tr').children('td:nth-child(3)').html();
            $.ajax({
              url : "getDoc.php",
              method : "POST",
              data : {json : id},
              success:function(data){
                src = JSON.parse(data);
                $("#embedPdf").attr("src", src);
              }
            });
          }else{
            $("#embedPdf").removeAttr("src");

          }
          $("#pdfModal").modal("toggle");

        });
        $(document).on('click','td[name="remaind"]',function(e){
          e.preventDefault();
          var id=$(this).closest('tr').children('td:nth-child(3)').html();
          $("#idForRemaindEdit").val(id);
          $("#Rdate2").val('');
          $("#Rtext2").val('');
          if(!$(this).children().children().hasClass("fa-bell-o")){
            json = JSON.stringify({"op":"get","id":id})
            $.ajax({
              url : "remaind.php",
              method : "POST",
              data : {json : json},
              success:function(data){
                rmd=JSON.parse(data);
                $("#Rdate2").val(rmd.dateRemaind);
                $("#Rtext2").val(rmd.textRemaind);
              }
            });
          }
          $("#ModalSetRemaind2").modal("toggle");
        });
        $(document).on('click',"#editRemaind",function(){
          var id=$("#idForRemaindEdit").val();
          json = JSON.stringify({"op":"set","id":id,"dRemaind":$('#Rdate2').val(),'tRemaind':$('#Rtext2').val()});
          $.ajax({
            url : "remaind.php",
            method : "POST",
            data : {json : json},
            success:function(data){
              if(data!="not ok"){
                $("#Rdate2").val("");
                $("#Rtext2").val("");
                $("#idForRemaindEdit").val("");
                addAlert('success','لقد ثم التغيير بنجاح','');
                var td = $("td").filter(function(){return $(this).text() == id;}).closest("tr").children().filter(function(){return $(this).attr("name")=="remaind";}).children().children();
                td.removeAttr("class");
                td.addClass("fa "+data);
              }else{
                addAlert('danger','تحدير','لم تنجح عملية التحيين');
              }
            }
          });
          $("#ModalSetRemaind2").modal("toggle");;
        });
        $(document).on('click','td[name="multRow"]',function(e){
          e.preventDefault();
          var id=$(this).closest('tr').children('td:nth-child(3)').html();
          $("#idForMultRow").val(id);
          $("#nbRow").val(id);
          $("#ModalSetRemaind3").modal("toggle");
        });
        $(document).on('click',"#goMultRow",function(){
          var id=$("#idForMultRow").val();
          var copy = $("#nbCopy").val()
          json = JSON.stringify({"id":id,"nbCopy":$("#nbCopy").val(),"fristRow":$('#dataTable tr:first td:eq(2)').html()});
          $.ajax({
            url : "mult.php",
            method : "POST",
            data : {json : json},
            success:function(data){
              json=JSON.parse(data);
              if(isset(json)){
                  row = "";
                for (var i = 0; i < json.length; i++) {
                  if(json[i].fileID!= null){
                    td=`<td name="file"><a href='#' style='color:#E94B3C;'><i class="fa fa-file-pdf-o" aria-hidden="true" data-toggle="tooltip" title="الاطلاع على النسخة الضوئية!"></i></a></td>`;
                  }else{
                    td='<td></td>';
                  }
                  if(json[i].dateRemaind != null){
                    td2=`<td name='remaind'><a href='#' style='color:#88B04B;'><i class="fa fa-bell" aria-hidden="true" data-toggle="tooltip" title="ضبط تنبيه!"></i></a></td>`;
                  }else{
                    td2=`<td name='remaind'><a href='#' style='color:#88B04B;'><i class="fa fa-bell-o" aria-hidden="true" data-toggle="tooltip" title="ضبط تنبيه!"></i></a></td>`;
                  }
                  if(i+1 <= copy){
                    row += `<tr id="`+json[i].num_ordre+`" class="table-info">`;
                  }else {
                    row += `<tr id="`+json[i].num_ordre+`">`;
                  }
                  row += `

                      <td name="editRow"><a href='#' style='color:#EFC050;'><i class='fa fa-pencil' aria-hidden='true' data-toggle="tooltip" title="تغيير!"></i></a></td>
                      <td name="multRow"><a href='#' style='color:#006E6D;'><i class="fa fa-files-o" aria-hidden="true" data-toggle="tooltip" title="نسخ!"></i></a></td>
                      <td>`+parseInt(ignoreNull(json[i].num_ordre.substring(json[i].num_ordre.length-10)))+`</td>
                      <td>`+ignoreNull(json[i].direction)+`</td>
                      <td>`+ignoreNull(json[i].dateArriver)+`</td>
                      <td>`+ignoreNull(json[i].expediteur)+`</td>
                      <td>`+ignoreNull(json[i].destinataire)+`</td>
                      <td>`+ignoreNull(json[i].type)+`</td>
                      <td>`+ignoreNull(json[i].objet)+`</td>
                      <td>`+ignoreNull(json[i].dossierAssocier)+`</td>`+
                      td+
                      `<td><a href='#'><i class="fa fa-file-word-o" aria-hidden="true" data-toggle="tooltip" title="تحميل الارسالية!"></i></a></td>`+
                      td2+
                      `<td><a href='#' style='color:#6B5B95;'><i class="fa fa-user-plus" aria-hidden="true" data-toggle="tooltip" title="احالة على!"></i></a></td>
                    </tr>
                  `;
                }
                ($('#dataTable tr').length>0)?$('#dataTable tr:first').before(row):$('#dataTable').html(row);
              }
            }
          });
          $("#ModalSetRemaind3").modal("toggle");
        });
        $(document).on('click','td[name="editRow"]',function(e){
          e.preventDefault();
          //var id=$(this).closest('tr').children('td:nth-child(3)').html();
          var id=$(this).closest('tr').attr('id');
          $("#idForEditRow2").val(id);
          $("#expediteur2").val("");
          $("#destinataire2").val("");
          $("#type2").val("");
          $("#object2").val("");
          $("#dossierAssocier2").val("");
          $("#dateArriver2").val("");
          json = JSON.stringify({"id":id});
          $.ajax({
            url : "getRow.php",
            method : "POST",
            data : {json : json},
            success:function(data){
              json=JSON.parse(data);
              if(json.direction == "وارد"){
                $("#sendorinbox2").bootstrapToggle('on');
              }else{
                $("#sendorinbox2").bootstrapToggle('off');
              }
              $("#expediteur2").val(json.expediteur);
              $("#destinataire2").val(json.destinataire);
              $("#type2").val(json.type);
              $("#objct2").val(json.objet);
              $("#dossierAssocier2").val(json.dossierAssocier);
              $("#dateArriver2").val(json.dateArriver);
            }
          });
          $("#ModalEditRow").modal("toggle");
        });
        $(document).on('click',"#goEditRow3",function(){
          var id=$("#idForEditRow2").val();
          json = JSON.stringify({"id":id,"direction":($('#sendorinbox2').is(':checked'))?"وارد":"صادر","expediteur":$("#expediteur2").val(),"destinataire":$('#destinataire2').val(),"type":$("#type2").val(),"object":$("#object2").val(),"dossierAssocier":$("#dossierAssocier2").val(),"dateArriver":$("#dateArriver2").val()});
          $.ajax({
            url : "editRow.php",
            method : "POST",
            data : {json : json},
            success:function(data){
              if(data == "ok"){
                $('#myTable tr[id='+id+'] td:eq(3)').html(($('#sendorinbox2').is(':checked'))?"وارد":"صادر");
                $('#myTable tr[id='+id+'] td:eq(5)').html($("#expediteur2").val());
                $('#myTable tr[id='+id+'] td:eq(6)').html($("#destinataire2").val());
                $('#myTable tr[id='+id+'] td:eq(7)').html($("#type2").val());
                $('#myTable tr[id='+id+'] td:eq(8)').html($("#objct2").val());
                $('#myTable tr[id='+id+'] td:eq(9)').html($("#dossierAssocier2").val());
                $('#myTable tr[id='+id+'] td:eq(4)').html($("#dateArriver2").val());
              }
            }
          });
          $("#ModalEditRow").modal("toggle");
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
          include 'includes/nav.php';?>


      <div class="container">
        <h3 class="text-right title"><i class="fa fa-caret-left" aria-hidden="true"></i> <i class="fa fa-book" aria-hidden="true"></i><u> تدبير سجل مكتب الضبط الالكتروني </u> "<?php echo $user->memeberOf("مكتب الضبط")["label"];?>"</h3>
        <div class="text-center controles">
          <button class="btn btn-success" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><strong><i class="fa fa-pencil-square-o" aria-hidden="true"></i> اضافة تسجيل جديد</strong></button>
          <button class="btn btn-warning" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><strong><i class="fa fa-search" aria-hidden="true"></i> بحث</strong></button>
          <button class="btn btn-info" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><strong><i class="fa fa-binoculars" aria-hidden="true"></i> بحث متعدد الوسائط</strong></button>
          <button class="btn btn-outline-secondary"><strong><i class="fa fa-file-excel-o" aria-hidden="true"></i> تحميل السجل</strong></button>
        </div>
        <div id="accordion">
          <div class="card">
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                <div class="text-right">
                  <form action="" method="get">
                    <div class="form-group row">
                      <div id="forcheck" class="col-2" onclick="isChecked(this);">
                        <input type="checkbox" name="sendorinbox" class="toogle-switch" id="sendorinbox" data-width="100" data-toggle="toggle" data-on="وارد" data-off="صادر" data-onstyle="success" data-offstyle="warning">
                      </div>
                      <div class="col-5">
                        <input class="form-control" list="lisDataAtraf" type="text" name="expediteur" placeholder="اسم المرسل" id="expediteur" value="رئيس مصلحة كتابة الضبط بالمحكمة الادارية بأكادير">
                      </div>
                      <div class="col-5">
                        <input class="form-control" list="lisDataAtraf" type="text" name="destinataire" placeholder="اسم المرسل اليه" id="destinataire">
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-3">
                        <input class="form-control" list="lisDataNaw3" type="text" name="type" placeholder="نوعها" id="type">
                      </div>
                      <div class="col-3">
                        <input class="form-control" list="lisDataMawdo3" type="text" name="object" placeholder="موضوعها" id="object">
                      </div>
                      <div class="col-3">
                        <input class="form-control" type="text" name="dossierAssocier" placeholder="مرتبطة بملف" id="dossierAssocier" autocomplete="on">
                      </div>
                      <div class="col-3">
                        <input placeholder="تاريخ الوصول" class="form-control" type="text" name="dateArriver" id="dateArriver">
                      </div>
                    </div>
                    <div class="form-group row">
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
                        <button type="button" class="btn btn-info bnt-control" data-toggle="modal" data-target="#ModalSetRemaind" /><i class="fa fa-bell-o" aria-hidden="true"> ضبط تذكير</i></button>
                        <input type="hidden" id="remaindDate" name="remaindDate" value="">
                        <input type="hidden" id="remaindText" name="remaindText" value="">
                        <button type="button" class="btn btn-success bnt-control" id="submit"><i class="fa fa-check" aria-hidden="true"> إضافة</i></button>
                        <button type="button" class="btn btn-danger bnt-control" data-toggle="collapse" data-target="#collapseOne" id="annulation"><i class="fa fa-undo" aria-hidden="true"> الغاء</i></button>
                    </div>
                  </from>
                </div>
              </div>
            </div>
          </div>
          <div class="card">
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
              </div>
            </div>
          </div>
          <div class="card">
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
              </div>
            </div>
          </div>
        </div>
        <hr />
        <div class="" id="rslt">
          <input id="myInput" type="text" placeholder="Search..">
          <table class="table table-striped table-bordered table-hover text-right" id="myTable">
            <thead class="thead-dark">
              <tr>
                <th class="align-middle text-center" style="width: 2%;"></th>
                <th class="align-middle text-center" style="width: 2%;"></th>
                <th class="align-middle" style="width: 4%;">الرقم الترتيبي</th>
                <th class="align-middle" style="width: 4%;">صادر / وارد</th>
                <th class="align-middle" style="width: 10%;">تاريخ الورود / الاصدار</th>
                <th class="align-middle" style="width: 21%;">المرسل</th>
                <th class="align-middle" style="width: 21%;">المرسل اليه</th>
                <th class="align-middle" style="width: 6%;">نوعها</th>
                <th class="align-middle" style="width: 12%;">موضوعها</th>
                <th class="align-middle" style="width: 10%;">الملف المرتبط</th>
                <th class="align-middle" style="width: 2%;"></th>
                <th class="align-middle" style="width: 2%;"></th>
                <th class="align-middle" style="width: 2%;"></th>
                <th class="align-middle" style="width: 2%;"></th>
              </tr>
            </thead>
            <tbody id='dataTable'>

            </tbody>
            <tfooter>
              <tr class="">
                <td colspan="14" class="text-center bt-marge" ><button type="button" onclick="reload(0,0);" class="btn btn-block collapsed btn-default">
                  <strong><i class="fa fa-caret-down" aria-hidden="true"></i> تحميل المزيد <i class="fa fa-caret-down" aria-hidden="true"></i></strong>
                </button></td>
              </tr>
            </tfooter>
          </table>
        </div>
      </div>
      <div id="outbody">
        <datalist id="lisDataAtraf">
        </datalist>
        <datalist id="lisDataNaw3">
        </datalist>
        <datalist id="lisDataMawdo3">
        </datalist>
        <div class="modal" id="pdfModal">
          <embed id="embedPdf" width="100%" height="100%" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">
        </div>
        <div class="modal fade" id="ModalSetRemaind" tabindex="-1" role="dialog" >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header row">
                <div class="text-right col-6">
                  <h5 class="modal-title">ضبط تذكير</h5>
                </div>
                <div class="col-5"></div>
                <div class="col-1">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
              <div class="modal-body">
                <form>
                  <input placeholder="تاريخ التذكير" class="form-control" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"  id="Rdate">
                  <textarea class="form-control" placeholder="ملاحظة"  id="Rtext"></textarea>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="margin-left:5px;" onclick="setremaind();">حفظ</button>
                <button type="button" class="btn btn-secondary" id="dismiss_modal" data-dismiss="modal">الغاء</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="ModalSetRemaind2" tabindex="-1" role="dialog" >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header row">
                <div class="text-right col-6">
                  <h5 class="modal-title">ضبط تذكير</h5>
                </div>
                <div class="col-5"></div>
                <div class="col-1">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
              <div class="modal-body">
                <form>
                  <input placeholder="تاريخ التذكير" class="form-control" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"  id="Rdate2">
                  <textarea class="form-control" placeholder="ملاحظة"  id="Rtext2"></textarea>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="margin-left:5px;" id="editRemaind">حفظ</button>
                <button type="button" class="btn btn-secondary" id="dismiss_modal" data-dismiss="modal">الغاء</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="ModalSetRemaind3" tabindex="-1" role="dialog" >
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header row">
                <div class="text-right col-6">
                  <h5 class="modal-title">ضبط تذكير</h5>
                </div>
                <div class="col-5"></div>
                <div class="col-1">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
              <div class="modal-body">
                <form>
                  <div class="input-group row" style="margin-bottom:10px;">
                    <label for="nbRow" class="col-4" >الرقم الترتيبي</label>
                    <input type="text" name="nbRow" id="nbRow" value="" class="col-4" disabled>
                  </div>
                  <div class="input-group row">
                    <label for="nbRow" class="col-4">عدد النسخ</label>
                    <input type="number" name="nbRow" id="nbCopy" class="col-4">
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="margin-left:5px;" id="goMultRow">حفظ</button>
                <button type="button" class="btn btn-secondary" id="dismiss_modal" data-dismiss="modal">الغاء</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="ModalEditRow" tabindex="-1" role="dialog" >
          <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header row">
                <div class="text-right col-6">
                  <h5 class="modal-title">تغيير التسجيل</h5>
                </div>
                <div class="col-5"></div>
                <div class="col-1">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
              <div class="modal-body ">
                <form>
                  <div class="form-group row">
                    <div id="forcheck2" class="col-2">
                      <input type="checkbox" name="sendorinbox2" class="toogle-switch" id="sendorinbox2" data-width="100" data-toggle="toggle" data-on="وارد" data-off="صادر" data-onstyle="success" data-offstyle="warning">
                    </div>
                    <div class="col-5">
                      <input class="form-control" list="lisDataAtraf" type="text" name="expediteur" placeholder="اسم المرسل" id="expediteur2" value="رئيس مصلحة كتابة الضبط بالمحكمة الادارية بأكادير">
                    </div>
                    <div class="col-5">
                      <input class="form-control" list="lisDataAtraf" type="text" name="destinataire" placeholder="اسم المرسل اليه" id="destinataire2">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-3">
                      <input class="form-control" list="lisDataNaw3" type="text" name="type" placeholder="نوعها" id="type2">
                    </div>
                    <div class="col-3">
                      <input class="form-control" list="lisDataMawdo3" type="text" name="object" placeholder="موضوعها" id="objct2">
                    </div>
                    <div class="col-3">
                      <input class="form-control" type="text" name="dossierAssocier" placeholder="مرتبطة بملف" id="dossierAssocier2" autocomplete="on">
                    </div>
                    <div class="col-3">
                      <input placeholder="تاريخ الوصول" class="form-control" type="text" name="dateArriver" id="dateArriver2">
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="margin-left:5px;" id="goEditRow3">تغيير</button>
                <button type="button" class="btn btn-secondary" id="dismiss_modal2" data-dismiss="modal">الغاء</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" id="idForRemaindEdit" name="" value="">
      <input type="hidden" id="idForMultRow" name="" value="">
      <input type="hidden" id="idForEditRow" name="" value="">
      <input type="hidden" id="idForEditRow2" name="" value="">

      <?php
        /*if ($user->hasPermissions("admin")) {
          echo "<p>You are an Administrator</p>";
        }
        if ($user->hasPermissions("modirator")) {
          echo "<p>You are a Modirator</p>";
        }*/
      }else{
        Redirect::to("login.php");
      }
      ?>


    </body>

  </html>
