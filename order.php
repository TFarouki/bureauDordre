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
    <h1> new page </h1>
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
