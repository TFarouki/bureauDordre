<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  $return->stat = true;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      $db->get("jugement",array("id","=",$json->id));
      $idFile = $db->first()->fileId;
      $subFolder = "";
      $code = explode("/",$db->first()->dossierAssocier);
      if($json->jugeType == "1" && $code[1]=="7102"){
        $subFolder = 14;
      }elseif ($json->jugeType == "1" && $code[1]=="7116") {
        $subFolder = 16;
      }elseif ($json->jugeType != "1"){
        $subFolder = 12;
      }else{
        $subFolder = 11;
      }
      $yearFolder = $json->yearJuge;
      $newFolder = "./FileUpload/jugement/".$yearFolder."/".$subFolder."/";
      $db->get("upfile_register",array("idFile","=",$idFile));
      $oldFolder = $db->first()->path;
      $fileName = $db->first()->name;
      if(!is_dir($newFolder)){
        mkdir($newFolder, 0777, true);
      }
      if(!rename($oldFolder.$fileName,$newFolder.$fileName)){
        $return->stat = false;
      }
      if(!$db->update("upfile_register",array("idFile"=> $idFile),array("path"=> $newFolder))){
        $return->stat = false;
      }
      if(!$db->update("jugement",array("id"=> $json->id),array("type"=> $json->jugeType,
                                                                "numJugement" => $json->numJuge,
                                                                "jugeYear" => $json->yearJuge,
                                                                "nb_pages"=> $json->nb_pages))){
        $return->stat = false;
      }
    }
    echo json_encode($return);
  }else{
      Redirect::to("login.php");
  }
?>
