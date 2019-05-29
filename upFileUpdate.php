<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $return->stat=true;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $src = "./FileUpload/tmp/" . $user->data()->id . "/".$json->upfile->name;
      if(file_exists($src)){
        if(!$json->id == Null && $json->id != '' && $json->id != 0){
          $db = Db::getInstance();
          $db->get("upfile_register",array("idFile","=",$json->id));
          if($db->count()>0){
            if(file_exists($db->first()->path.$db->first()->name)){
              unlink($db->first()->path.$db->first()->name);
            }
            $location = "./FileUpload/uploadFile/".date("Y")."/".date("m")."/".date("d")."/";
            if(!is_dir($location)){
              mkdir($location, 0777, true);
            }
            if(rename($src, $location.$json->upfile->name)){
                if(!$db->update("upfile_register",array('idFile'=> $json->id),array("name"=>$json->upfile->name,"path"=>$location,"size"=>$json->upfile->size,"type"=>$json->upfile->type))){
                  $return->updateReg = "لم يتم تحيين سجل الصادر و الوارد ....!";
                  $return->stat=false;
                }
            }else{
              $return->move = "لقد وقع خطأ اثناء تحميل الملف ..!";
              $return->stat=false;
            }
          }
        }
      }else{
        $return->postFile = "مرفق الاستعلام غير موجود";
        $return->stat=false;

      }
    }else{
      $return->post = "لم يتم ارسال اي استعلام";
      $return->stat=false;

    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }

 ?>
