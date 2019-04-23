<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $return->statut = false;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    $file = new stdClass();
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $src = "./FileUpload/tmp/" . $user->data()->id . "/".$json->file->name;
      if(file_exists($src)){
        $location = "./FileUpload/uploadFile/".date("Y")."/".date("m")."/".date("d")."/";
        if(!is_dir($location)){
          mkdir($location, 0777, true);
        }
        if(rename($src, $location.$json->file->name)){
          $tokken = Hash::salt(50);
          if(!$db->insert("upfile_register",array("name"=>$json->file->name,"type"=>$json->file->type,"size"=>$json->file->size,"path"=>$location,"statuts"=>"","tokken"=>$tokken))){
            $return->uploadReg = "لم يتم تسجيل معلومات الملف بالسجل..!";
          }else{
            $db->query("SELECT idFile FROM upfile_register where tokken = '".$tokken."'");
            if($db->count()){
              $idFile = $db->first()->idFile;
              if(!$db->update("register_bureaudordre",array("num_ordre"=> $json->id),array("fileID" => $idFile))){
                $return->updateReg = "لم يتم تحيين سجل الصادر و الوارد ....!";
              }else{
                $return->statut = true;
              }
            }
          }
        }else{
          $return->move = "لقد وقع خطأ اثناء تحميل الملف ..!";
        }
      }
    }
  }else{
    Redirect::to('login.php');
  }
?>
