<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  $return->stat = false;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $json->fileTmpName = json_decode($json->fileTmpName);
      //move file to upload folder

      $db = Db::getInstance();
      if(isset($json->fileTmpName->name) && !empty($json->fileTmpName->name)){
        $src = "./FileUpload/tmp/".$user->data()->id."/".$json->fileTmpName->name;
        $subFolder = "";
        $code = explode("/",$json->dossierAssocier);
        if($json->type == "1" && $code[1]=="7102"){
          $subFolder = 14;
        }elseif ($json->type == "1" && $code[1]=="7116") {
          $subFolder = 16;
        }elseif ($json->type != "1"){
          $subFolder = 12;
        }else{
          $subFolder = 11;
        }
        $location = "./FileUpload/jugement/".$json->yearJuge."/".$subFolder."/";
        if(!is_dir($location)){
          mkdir($location, 0777, true);
        }
        if(rename($src, $location.$json->fileTmpName->name)){
          $tokken = Hash::salt(50);
          if(!$db->insert("upfile_register",array("name"=>$json->fileTmpName->name,"type"=>$json->fileTmpName->type,"size"=>$json->fileTmpName->size,"path"=>$location,"statuts"=>"","tokken"=>$tokken))){
            $return->uploadReg = "لم يتم تسجيل معلومات الملف بالسجل..!";
          }else{
            $db->query("SELECT idFile FROM upfile_register where tokken = '".$tokken."'");
            if($db->count()){
              $idFile = $db->first()->idFile;
              if(!$db->insert("jugement",array("type"=>$json->type,"fileId"=>$idFile,"dossierAssocier"=>$json->dossierAssocier,"nb_pages"=>$json->fileTmpName->nbPages,"numJugement"=>$json->numJuge,"jugeYear"=>$json->yearJuge))){
                $return->uploadReg = "لم يتم تسجيل معلومات الملف بالسجل..!";
              }else{
                $return->stat = true;
              }
            }
          }
        }else{
          $return->move = "لقد وقع خطأ اثناء تحميل الملف ..!";
        }
      }
    }else{
      $return->post = "لم يتم ارسال اي استعلام";
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }
?>
