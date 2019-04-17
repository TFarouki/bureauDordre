<?php
  require_once 'core/init.php';
  $user = new User();
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
    $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
    $rmd = new stdClass();
    if(isset($_POST['json'])){
      $json=json_decode($_POST['json']);
      $db = Db::getInstance();
      $db->get("register_bureaudordre",array('num_ordre' , '=',$json->id));
      echo json_encode($db->first());
    }
  }else{
    Redirect::to('login.php');
  }

?>
