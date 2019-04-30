<?php
  require_once 'core/init.php';
  if (Session::exists("success")) {
    echo Session::flash("success");
  }
  $user = new User();
  $db= Db::getInstance();
  if($user->isLoggedIn())
  {
    $memeberOfLabl = $user->memeberOf("مكتب الضبط")["label"];
    $sql = 'SELECT * FROM register_bureaudordre WHERE group_reg = "'.$memeberOfLabl.'" AND dateRemaind <= "'. date('Y-m-d').'" ORDER BY dateRemaind ASC';
    $db->query($sql);
    echo json_encode($db->results());
  }else{
    Redirect::to("login.php");
  }
?>
