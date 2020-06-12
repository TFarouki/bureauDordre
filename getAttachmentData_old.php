<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  $return->stat = false;
  if($user->isLoggedIn()){
    if (isset($_POST["json"])) {
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      $db->query("SELECT * FROM register_bureaudordre where num_ordre = ".$json->id);
      $num_ordre = null;
      if($db->count()){
        if($db->first()->stat != null && $db->first()->stat != "" ){
          $num_ordre = explode("-",$db->first()->stat)[1];
        }else {
          $num_ordre = $db->first()->num_ordre;
        }
        $db->query("SELECT * FROM docnum where num_order = ".$num_ordre);
        if($db->count()){
          $return->data = $db->first();
          $return->stat = true;
        }
      }
    }else {
      $return->post = "لم يتم ارسال اي استعلام";
    }
    echo json_encode($return);
  }else{
    Redirect::to("login.php");
  }
?>
