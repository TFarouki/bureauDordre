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
              <span class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></span>
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
