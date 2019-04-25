<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $src = "./FileUpload/tmp/" . $user->data()->id . "/".$json->file->name;
      if(file_exists($src)){
        if(!$json->newFile){
          $db->query("SELECT fileID FROM register_bureaudordre where num_ordre = '".$json->id."'");
          if($db->count()>0){
            $idFile = $db->first()->fileID;
          }
          if($idFile!= Null && $idFile != '' && $idFile != 0){
            $db->query("SELECT name,path FROM upfile_register where idFile = '".$idFile."'");
            if($db->count()>0){
              if(file_exists($db->first()->path.$db->first()->name)){
                unlink($db->first()->path.$db->first()->name);
                $db->delete("upfile_register",array('idFile','=', $idFile));

                  //update orderReg-> idFile

                }
              }
            }
          }
          $location = "./FileUpload/uploadFile/".date("Y")."/".date("m")."/".date("d")."/";
          if(!is_dir($location)){
            mkdir($location, 0777, true);
          }
          if(rename($src, $location.$json->file->name)){
            $tokken = Hash::salt(50);
            if(!$db->insert("upfile_register",array("name"=> $json->file->name,"type"=> $json->file->type,"size"=> $json->file->size,"path"=> $location,"statuts"=> '',"tokken"=>$tokken))){
              $return->insert = "لقد وقع خطأ اثناء تسجيل الملف بقاعدة البيانات ..!";
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
      }else{
        $return->postFile = "مرفق الاستعلام غير موجود";
      }
    }else{
      $return->post = "لم يتم ارسال اي استعلام";
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }

 ?>
