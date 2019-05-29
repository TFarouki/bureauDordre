<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  $return->stat = true;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      $tokken = null;
      //deplace files
      $src = "./FileUpload/tmp/" . $user->data()->id . "/".$json->dataFile->name;
      $location = "./FileUpload/uploadFile/".date("Y")."/".date("m")."/".date("d")."/";
      if(file_exists($src)){
        if(!is_dir($location)){
          mkdir($location, 0777, true);
        }
        if(rename($src, $location.$json->dataFile->name)){
          //set file rows

          $tokken = Hash::salt(50);
          $ar0 = array("name"=>$json->dataFile->name,
                        "path"=>$location,
                        "type"=>$json->dataFile->type,
                        "size"=>$json->dataFile->size,
                        "tokken"=>$tokken
                      );
          if(!$db->insert("upfile_register",$ar0)){
            $return->updateReg = "لم يتم تسجيل الملف بقاعدة البيانات ...!";
            $return->stat = false;
          }
        }else {
            $return->move = "لقد وقع خطأ اثناء تحميل الملف ..!";
            $return->stat = false;
          }
      }

      //set numdoc row


      $db->get("upfile_register",array("tokken","=",$tokken));
      $fileId = $db->first()->idFile;
      $ar = array("type"=>$json->type,
                  "subType"=>"ok",
                  "demandeur"=>$json->demandeur,
                  "remarque"=>$json->remarque,
                  "dossierAssocier"=>$json->dossierAssocier,
                  "object"=>$json->objet,
                  "num_order"=>$json->num_order,
                  "idFile"=>$fileId);
      if(!$db->insert("docnum",$ar)){
        $return->stat = false;
      }
    }
    echo json_encode($return);
  }else{
      Redirect::to("login.php");
  }
?>
