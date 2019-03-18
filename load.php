<?php
  require_once 'core/init.php';
  if (Session::exists("success")) {
    echo Session::flash("success");
  }


  $user = new User();
  if($user->isLoggedIn()){
    if (isset($_POST["json"])) {
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      $db->query("SELECT * FROM register_bureaudordre ORDER bY num_ordre DESC LIMIT {$json->from},{$json->lenght}");
      echo json_encode($db->results());
    }else {
      echo "not ok";
    }
  }else{
    Redirect::to("login.php");
  }
?>
