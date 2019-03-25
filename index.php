<?php
require_once 'core/init.php'; ?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
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
<?php include 'includes/nav.php';
    Redirect::to("order.php");
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
