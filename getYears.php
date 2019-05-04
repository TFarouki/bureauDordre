<?php
  require_once 'core/init.php';
  $user = new User();
  $response = new stdClass();
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
     $db= Db::getInstance();
     $db->query('SELECT DISTINCT YEAR(dateEnrg) AS year  FROM register_bureaudordre where group_reg ="'.$memeberOfLabel.'" ORDER BY year');
     echo json_encode($db->results());
  }else{
    Redirect::to("login.php");
  }
?>
