<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  $return->stat = true;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      $db->get("register_bureaudordre",array("num_ordre","=",$json->num_order));
      $fileId = $db->first()->fileID;
      echo $fileId;
      /*$db->insert("docNum",array("type"=>,
                                  "subtype"=>,
                                  "demandeur"=>,
                                  "remarque"=>,
                                  "idFile"=>$fileId,
                                  "dossierAssocier"=>,)
                  );*/
    }
    //echo json_encode($return);
  }else{
      Redirect::to("login.php");
  }
?>
