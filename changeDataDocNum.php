<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  $return->stat = true;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      if(!$db->update("docnum",array("ref"=> $json->id),array("type"=> $json->type,
                                                                "object" => $json->object,
                                                                "demandeur" => $json->demandeur,
                                                                "remarque"=> $json->remarque))){
        $return->stat = false;
      }
    }
    echo json_encode($return);
  }else{
      Redirect::to("login.php");
  }
?>
