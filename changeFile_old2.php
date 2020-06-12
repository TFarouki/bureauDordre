<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $stat = null;
  $stat2 = null;
  $db = Db::getInstance();
  $data = null;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $src = "./FileUpload/tmp/" . $user->data()->id . "/".$json->file->name;
      if(file_exists($src)){
        $db->query("SELECT fileID,stat FROM register_bureaudordre where num_ordre = '".$json->id."'");
        if($db->count()>0){
          $idFile = $db->first()->fileID;
          $stat = $db->first()->stat;
          if($stat != NULL){
            $stat2 = explode("-",$db->first()->stat)[0];
          }
        }
        if(!$json->newFile && $stat2 != "link to"){
            if($idFile!= Null && $idFile != '' && $idFile != 0){
              $db->query("SELECT name,path FROM upfile_register where idFile = '".$idFile."'");
              if($db->count()>0){
                if(file_exists($db->first()->path.$db->first()->name)){
                  unlink($db->first()->path.$db->first()->name);
                  $location = "./FileUpload/uploadFile/".date("Y")."/".date("m")."/".date("d")."/";
                  if(!is_dir($location)){
                    mkdir($location, 0777, true);
                  }
                  if(rename($src, $location.$json->file->name)){
                      if(!$db->update("upfile_register",array('idFile'=> $idFile),array("name"=>$json->file->name,"path"=>$location))){
                        $return->updateReg = "لم يتم تحيين سجل الملفات..!";
                      }else{
                        $return->statut = true;
                      }
                  }else {
                    $return->move = "لقد وقع خطأ اثناء تحميل الملف ..!";
                  }
                }
              }
            }
        }else {
          //copy file tmp to numdoc
            $location = "./FileUpload/uploadFile/".date("Y")."/".date("m")."/".date("d")."/";
            if(!is_dir($location)){
              mkdir($location, 0777, true);
            }
            if(rename($src, $location.$json->file->name)){
              //insert row into doc reg
                $tokken = Hash::salt(32);
                $ar= array("name"=>$json->file->name,
                            "path"=>$location,
                            "type"=>$json->file->type,
                            "size"=>$json->file->size,
                            "tokken"=>$tokken);
                if(!$db->insert("upfile_register",$ar)){
                  $return->updateReg = "لم يتم اضافة المستند الى سجل الملفات ...!";
                }else{
                  //update bureaudordre
                  $db->query("SELECT idFile FROM upfile_register where tokken = '".$tokken."'");
                  if($db->count()>0){
                      $newId = $db->first()->idFile;
                      $ar = null;
                      if($stat2 == "link to"){
                        $ar = array("fileID"=>$newId,"stat"=>"copy of-".explode("-",$stat)[1]);
                      }else {
                        $ar = array("fileID"=>$newId);
                      }
                      if(!$db->update("register_bureaudordre",array('num_ordre'=>$json->id),$ar)){
                        $return->updateReg = "لم يتم تحيين سجل الصادر و الوارد ....!";
                      }else{
                        $return->statut = true;
                      }
                  }
                }
            }else {
              $return->move = "لقد وقع خطأ اثناء تحميل الملف ..!";
            }

        }

      }else{
        $return->postFile = "مرفق الاستعلام غير موجود";
      }
    }else{
      $return->post = "لم يتم ارسال اي استعلام";
    }
    //_________________get datafile to make link to docnum_______
    if($return->statut){
      $db->query("SELECT type,dossierAssocier,num_ordre FROM register_bureaudordre where num_ordre = '".$json->id."'");
      if($db->count()>0){
        $return->data = $db->first();
      }
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }

 ?>
