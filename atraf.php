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
      $db->query("SELECT DISTINCT taraf FROM
                  (SELECT DISTINCT expediteur AS taraf FROM register_bureaudordre
                  UNION
                  SELECT DISTINCT destinataire AS taraf FROM register_bureaudordre) AS atraf
                  WHERE taraf not like ''
                  ORDER BY taraf ASC;");
      echo json_encode($db->results());
  }else{
    Redirect::to("login.php");
  }
?>
