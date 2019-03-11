<?php
  require_once 'core/init.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
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
        include 'includes/nav.php';
    ?>
    <div class="container">
      

    </div>

    <?php
      }else{
        Redirect::to("login.php");
      }
    ?>
  </body>
</html>
