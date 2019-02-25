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
    $db = Db::getInstance();
    $db->query("SELECT * FROM users");
    echo $db->first()->name;

    ?>
  </body>
</html>
