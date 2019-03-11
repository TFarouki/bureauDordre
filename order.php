<?php require_once 'core/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include 'includes/include_head.php';?>
    <title></title>
  </head>
  <body>
    <?php
      if (Session::exists("success")) {
        echo Session::flash("success");
      }
      $user = new User();
      if($user->isLoggedIn()){
    ?>
    <?php include 'includes/nav.php';?>
    <style media="screen">
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
          $("#sendorinbox").attr('checked', $(x).children().hasClass('off'));
          if($(x).children().hasClass('off')){
            $('#destinataire').val('رئيس مصلحة كتابة الضبط بالمحكمة الادارية بأكادير');
            $('#expediteur').val('');

          }else{
            $('#expediteur').val('رئيس مصلحة كتابة الضبط بالمحكمة الادارية بأكادير');
            $('#destinataire').val('');
          }
      }
    </script>
    <div class="container">
      <h3 class="text-right title"><i class="fa fa-caret-left" aria-hidden="true"></i> <i class="fa fa-book" aria-hidden="true"></i><u> تدبير سجل مكتب الضبط الالكتروني </u></h3>
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
                <form action="order.php" method="get">
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
                      <input class="form-control" type="text" name="dossierAssocier" placeholder="مرتبطة بملف" id="dossierAssocier">
                    </div>
                    <div class="col-3">
                      <input placeholder="تاريخ الوصول" class="form-control" type="text" name="dateArriver" onfocus="(this.type='date')" onblur="(this.type='text')"  id="dateArriver">
                    </div>
                  </div>
                  <div class="form-group row">
                      <div class=" bnt-control">
                        <div class="custom-file mb-3">
                          <input type="file" onchange="getok();" class="custom-file-input" id="customFile" name="filename">
                          <label class="custom-file-label text-left" for="customFile" id="filelab"><i class="fa fa-clone" aria-hidden="true"> نسخة الماسح الضوئي</i></label>
                        </div>
                      </div>
                      <button type="button" class="btn btn-info bnt-control" data-toggle="modal" data-target="#ModalSetRemaind" /><i class="fa fa-bell-o" aria-hidden="true"> ضبط تذكير</i></button
                      <input type="hidden" id="remaindDate" name="remaindDate" value="">
                      <input type="hidden" id="remaindText" name="remaindText" value="">
                      <button class="btn btn-success bnt-control"><i class="fa fa-check" aria-hidden="true"> إضافة</i></button>
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
        <table class="table table-hover">
          <thead class="thead-dark">
            <tr>
              <th>Firstname</th>
              <th>Lastname</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>John</td>
              <td>Doe</td>
              <td>john@example.com</td>
            </tr>
            <tr>
              <td>Mary</td>
              <td>Moe</td>
              <td>mary@example.com</td>
            </tr>
            <tr>
              <td>July</td>
              <td>Dooley</td>
              <td>july@example.com</td>
            </tr>
          </tbody>
          <tfooter>
            <tr class="">
              <td colspan="3" class="text-center bt-marge" ><button type="button" class="btn btn-block collapsed btn-default">
                <strong><i class="fa fa-caret-down" aria-hidden="true"></i> تحميل المزيد <i class="fa fa-caret-down" aria-hidden="true"></i></strong>
              </button></td>
            </tr>
          </tfooter>
        </table>
      </div>
    </div>




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
  <datalist id="lisDataAtraf">
  </datalist>
  <datalist id="lisDataNaw3">
  </datalist>
  <datalist id="lisDataMawdo3">
  </datalist>
</html>

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
