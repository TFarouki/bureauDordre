<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $json->fileTmpName = json_decode($json->fileTmpName);
      echo $json->fileTmpName->name;
      //fix send dossierAssocier
      //move file to upload folder
      //get page number
      //add row to upfile_register
      //add row to table jugement
      //return list of jusfemet for same dossierAssocier
    }else{
      $return->post = "لم يتم ارسال اي استعلام";
    }
  }else{
    Redirect::to('login.php');
  }
?>
