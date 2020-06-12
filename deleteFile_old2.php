<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $return->statut = false;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $id = json_decode($_POST['json']);
      $db->query("SELECT fileID,stat FROM register_bureaudordre where num_ordre = '".$id."'");
      if($db->count()>0){
        $idFile = $db->first()->fileID;
        $stat = $db->first()->stat;
        $db->query("SELECT * FROM upfile_register where idFile = '".$idFile."'");
        if($db->count()>0){
          $test = false;
          $ar = array("fileID" => NULL);
          if($stat == NULL){
            $test = true;
          }else if(explode("-",$stat)[0] == 'copy of'){
            $test = true;
          }else{
            $ar = array("fileID" => NULL,"stat" => "copy of-".explode("-",$stat)[1]);
          }
          if($test){
            if(file_exists($db->first()->path.$db->first()->name)){
              unlink($db->first()->path.$db->first()->name);
            }
            $db->delete("upfile_register",array('idFile','=', $idFile));
          }
          if(!$db->update("register_bureaudordre",array("num_ordre"=> $id),$ar)){
            $return->updateReg = "لم يتم تحيين سجل الصادر و الوارد ....!";
          }else{
            $return->statut = true;
          }
        }
      }
    }else{
      $return->post = "لم يتم ارسال اي استعلام";
    }
    if($return->statut){
      $return->del = false;
      $id = json_decode($_POST['json']);
      if($db->delete("docnum",array('num_ordre','=', $id))){
        $return->del = true;
      }
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }

?>
