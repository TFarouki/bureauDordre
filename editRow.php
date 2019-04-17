<?php
  require_once 'core/init.php';
  $user = new User();
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
    $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
    $rmd = new stdClass();
    if(isset($_POST['json'])){
      $json=json_decode($_POST['json']);
      $id = $json->id;
      $db = Db::getInstance();
      $ajson = json_decode($_POST['json'],true);
      array_shift($ajson);
      if(!$db->update("register_bureaudordre",array("num_ordre" => $id),$ajson)){
        echo "not ok";
      }else {
        echo "ok";
      }
    }
  }else{
    Redirect::to('login.php');
  }

?>
