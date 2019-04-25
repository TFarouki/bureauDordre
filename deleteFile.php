<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $id = json_decode($_POST['json']);
      $db->query("SELECT fileID FROM register_bureaudordre where num_ordre = '".$id."'");
      if($db->count()>0){
        $idFile = $db->first()->fileID;
        $db->query("SELECT name,path FROM upfile_register where idFile = '".$idFile."'");
        if($db->count()>0){
          if(file_exists($db->first()->path.$db->first()->name)){
            unlink($db->first()->path.$db->first()->name);
          }
          $db->delete("upfile_register",array('idFile','=', $idFile));
          if(!$db->update("register_bureaudordre",array("num_ordre"=> $id),array("fileID" => NULL))){
            $return->updateReg = "لم يتم تحيين سجل الصادر و الوارد ....!";
          }else{
            $return->statut = true;
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
