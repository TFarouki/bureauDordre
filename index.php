<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>

    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
<?php
require_once 'core/init.php';

if (Session::exists("success")) {
  echo Session::flash("success");
}


$user = new User();
if($user->isLoggedIn()){
?>
  <p> Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a>!</p>
  <ul>
    <li> <a href="logout.php">Log Out</a></li>
    <li> <a href="update.php">update details</a></li>
    <li> <a href="changePassword.php">update password</a></li>
  </ul>
<?php

if ($user->hasPermissions("admin")) {
  echo "<p>You are an Administrator</p>";
}
if ($user->hasPermissions("modirator")) {
    echo "<p>You are a Modirator</p>";
  }
}else{
  Redirect::to("login.php");
/*?>

  <p>You need to <a href="login.php">login</a> or <a href="register.php">register</a>!</p>
<?php*/
}
?>

</body>
</html>