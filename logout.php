<?php
  require_once 'core/init.php';

  $user = new User();
  rmdir("./tmp/".$user->data()->id);
  rmdir("./FileUpload/tmp/".$user->data()->id);
  $user->logout();

  Redirect::to("index.php");

?>
