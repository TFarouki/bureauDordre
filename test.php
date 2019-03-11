<?php
  require_once 'core/init.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php include 'includes/include_head.php';?>

    <title></title>
    <style media="screen">
      .ul{
        background-color: rgb(255,255,255);
        cursor: pointer;

        border: solid 1px #444;
        padding:0px;
        border-bottom: none;
      }
      .li{
        padding: 12px;
        border-bottom: solid 1px #444;
      }
      .li:hover{
        background-color:#eee;
      }
      .autocomplete {
        position: absolute;
        z-index: 500 !important;
        min-width: 100%;
      }
    </style>
    <script type="text/javascript">
      $(document).ready(function(){
        $("#expideteur").keyup(function(){
          var q=$(this).val();
          if(q.length==3){
            $.ajax({
              url:"ok.php",
              method:"POST",
              data:{q:q},
              success:function(data){
                $("#rslt").html(data);
              }
            });
          }else{
            $("#rslt").html("");
          }
        });
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

      <h3 class="text-center">textbox autocomplete useing php,Bootstrap,jqeury,AJAX</h3>
      <div class="row">

        <div class="col-3" style="width:40%; margin :0 auto;">
          <!--input type="text" class="text-right form-control" placeholder="اسم المرسل" name="expideteur" id="expideteur">
          <div class="autocomplete"></div-->
          <input list="rslt" id="expideteur" name="browser" placeholder="اسم المرسل" class="form-control">
          <datalist id="rslt">
          </datalist>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-3"></div>
        <div class="col-3">
          <button type="button" class="btn btn-primary" name="button">حفظ المعلومات</button>
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
