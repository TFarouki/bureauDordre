<?php
  require_once 'core/init.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    $salt="taoufik farouki";
    $string="123456789";

    echo "password: <textarea>".$string."</textarea>";
    echo "salt    : <textarea>".$salt."</textarea>";
    echo "hash    : <textarea>".hash('sha256' , $string . $salt)."</textarea>";
    ?>
  </body>
</html>
