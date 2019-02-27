<?php
require_once 'core/init.php'; ?>
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
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
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
