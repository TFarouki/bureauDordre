<?php
  require_once 'core/init.php';


  if (Session::exists("success")) {
    echo Session::flash("success");
  }


  $user = new User();
  if($user->isLoggedIn()){
  //  if(isset($_POST["q"])){
  //    $q = $_POST["q"];
      $db = Db::getInstance();
      $db->query("SELECT DISTINCT `type` AS naw3 FROM `register_bureaudordre`
                  WHERE `type` not like ''
                  ORDER BY `type` ASC;");
      echo json_encode($db->results());
  }else{
    Redirect::to("login.php");
  }
?>
