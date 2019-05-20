<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      $db->query("SELECT * FROM jugement where dossierAssocier = '".$json->docNum."' ORDER BY date_Reg DESC");
      $return->count = $db->count();
      $return->rows = $db->results();
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }
?>
