<?php
  require_once 'core/init.php';
  $user = new User();
  $response = new stdClass();
  if($user->isLoggedIn()){
     $db= Db::getInstance();
     $db->query('SELECT DISTINCT YEAR(dateEnrg) AS year  FROM register_bureaudordre');
     echo json_encode($db->results());
  }else{
    Redirect::to("login.php");
  }
?>
