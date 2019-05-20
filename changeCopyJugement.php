<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      //get id file
      $db->get("jugement",array("id","=",$json->id));
      print_r($db->first());
      //get old file and delete it
      //gelete row with same id file
      //add new file uploaded
      //copy file to folder
      //update idfile in jugement

    }
    echo json_encode($response);
  }else{
      Redirect::to("login.php");
  }
?>
