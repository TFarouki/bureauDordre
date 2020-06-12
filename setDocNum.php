<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  $return->stat = true;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      $db->get("docnum",array("num_ordre","=",$json->num_order));
      if(!$db->count()){
        $db->get("register_bureaudordre",array("num_ordre","=",$json->num_order));
        $fileId = $db->first()->fileID;
        $ar = array("type"=>$json->type,
                    "demandeur"=>$json->demandeur,
                    "remarque"=>$json->remarque,
                    "idFile"=>$fileId,
                    "dossierAssocier"=>$json->dossierAssocier,
                    "object"=>$json->objet,
                    "num_ordre"=>$json->num_order);
        if(!$db->insert("docnum",$ar)){
                        $return->stat = false;
        }
      }else{
        $db->get("register_bureaudordre",array("num_ordre","=",$json->num_order));
        $fileId = $db->first()->fileID;
        if(!$db->update("docnum",array('num_ordre'=> $json->num_order),array("idFile"=>$fileId))){
          $return->stat = false;
        }
      }
    }
    echo json_encode($return);
  }else{
      Redirect::to("login.php");
  }
?>
