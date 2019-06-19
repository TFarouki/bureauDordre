<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $db = Db::getInstance();
  $return->stat = false;
  $return->icon = false;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db->query("SELECT * FROM docnum Where num_order = '".$json->num_ordre."'");
      if($db->count()){
          if(!$db->delete("docnum",array("num_order","=",$json->num_ordre))){
            $return->error = "لم يتم تحديث سجل ارتباط الملف";
          }else{
            if(!$db->update("register_bureaudordre",array("num_ordre"=>$json->num_ordre),array("dossierAssocier"=>null))){
              $return->error = "لم يتم تحيين سجل الضبط";
            }else{
              $return->stat = true;
              $return->icon = false;
            }
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
