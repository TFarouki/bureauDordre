<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  $return->stat = true;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      //get id file
      $db->get("jugement",array("id","=",$json->id));
      $idFile = $db->first()->fileId;
      //get old file and delete it
      $db->get("upfile_register",array("idFile","=",$idFile));
      if(file_exists($db->first()->path . $db->first()->name)){
        unlink($db->first()->path . $db->first()->name);
      }
      $src = "./fileUpload/tmp/" . $user->data()->id . "/" . $json->upfile->name;
      if(!rename($src, $db->first()->path . $json->upfile->name)){
        $return->stat = false;
      }
      //update upfile_reg row with same id File (dateEnrg , name , type , size)
      if(!$db->update("upfile_register",array("idFile"=> $idFile),array("name"=> $json->upfile->name,
                                                                        "type"=> $json->upfile->type,
                                                                        "size"=> $json->upfile->size))){
        $return->stat = false;
      }
      if(!$db->update("jugement",array("id"=> $json->id),array("nb_pages"=>$json->upfile->nbPages))){
        $return->stat = false;
      }
    }
    echo json_encode($return);
  }else{
      Redirect::to("login.php");
  }
?>
