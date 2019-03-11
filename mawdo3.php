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
      $db->query("SELECT DISTINCT `objet` AS mawdo3 FROM `register_bureaudordre`
                  WHERE `objet` not like ''
                  ORDER BY `objet` ASC;");
      echo json_encode($db->results());
  }else{
    Redirect::to("login.php");
  }
?>
