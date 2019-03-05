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

        [type=file] {
          display : none;
        }
        input,
        [type=file] + label {
        border-radius: 3px;
        padding: 5px;
        }
        [type=file] + label {
        text-align: center;
        vertical-align: middle;
        /* Decorative */
        background: #333;
        color: #fff;
        border: none;
        cursor: pointer;
        }
        [type=file] + label:hover {
        background: #3399ff;
        }
    </style>

    <script type="text/javascript">
    $("[type=file]").on("change", function(){
      // Name of file and placeholder
      var file = this.files[0].name;
      var dflt = $(this).attr("placeholder");
      if($(this).val()!=""){
        $(this).next().text(file);
      } else {
        $(this).next().text(dflt);
      }
      });
    </script>
    <div class="container">
      <h3 class="text-right title"><i class="fa fa-caret-left" aria-hidden="true"></i> <i class="fa fa-book" aria-hidden="true"></i><u> تدبير سجل مكتب الضبط الالكتروني </u></h3>
      <style>
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
      </style>
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
                <div class="form-group row">
                  <div class="col-2">
                    <input type="checkbox" class="toogle-switch" checked id="example-switch-input1" data-width="100" data-toggle="toggle" data-on="وارد" data-off="صادر" data-onstyle="success" data-offstyle="warning">
                  </div>
                  <div class="col-5">
                    <input class="form-control" type="text" placeholder="اسم المرسل" id="example-text-input2">
                  </div>
                  <div class="col-5">
                    <input class="form-control" type="text" placeholder="اسم المرسل اليه" id="example-search-input3">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-3">
                    <input class="form-control" type="text" placeholder="نوعها" id="example-search-input4">
                  </div>
                  <div class="col-3">
                    <input class="form-control" type="text" placeholder="موضوعها" id="example-search-input5">
                  </div>
                  <div class="col-3">
                    <input class="form-control" type="text" placeholder="مرتبطة بملف" id="example-search-input6">
                  </div>
                  <div class="col-3">
                    <input placeholder="تاريخ الوصول" class="form-control" type="text" onfocus="(this.type='date')" onblur="(this.type='text')"  id="example-search-input7">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-3">
                    <input id="f02" type="file" placeholder="Add profile picture" />
                    <label for="f02">نسحة الماسح الضوئي</label>
                  </div>
                  <div class="col-3">
                    <div class="dropdown">
                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        ضبط تدكير
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Link 1</a>
                        <a class="dropdown-item" href="#">Link 2</a>
                        <a class="dropdown-item" href="#">Link 3</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-3">
                    <button class="btn btn-primary">إضافة</button>
                  </div>
                  <div class="col-3">
                    <button class="btn btn-danger">الغاء</button>
                  </div>
                </div>
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
              <td colspan="3" class="text-center bt-marge" ><button class="btn btn-block collapsed btn-default">
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
</html>
