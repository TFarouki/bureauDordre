<?php
  require_once 'core/init.php';


  /*header("Content-Type application/vnd.msword");
  /*header("Expires :0");
  header("Cache-Control : must-revalidate, post-check=0 pre-check=0");*/
  /*header("Content-Disposition:attachment;filename=jm8132.doc");
  echo "<html>";

  echo "</html>";*/
//  readfile("jm8132.docx");
?>

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

    ?>

    <div class="container">
      <?php
        $read = file('1.docx');
        foreach ($read as $sread) {
          $sread = 'im in a <DATE> becuse i al ';
          if(strpos($sread,'<DATE>') !== false){
            echo "ok";
          }
        }
      ?>
    </div>

    <?php
      }else{
        Redirect::to("login.php");
      }
    ?>
  </body>
</html>
