<?php
  require_once 'core/init.php';
  $user = new User();
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
    $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
    $rmd = new stdClass();
    if(isset($_POST['json'])){
      $json=json_decode($_POST['json']);
      if($json->op=="get"){
        $db = Db::getInstance();
        $fullId = $memeberOfId . date("Y") . "0000000000";
        $fullId += $json->id;
        $db->get("register_bureaudordre",array("num_ordre","=",$fullId));
        if($db->count()){
          $rmd->dateRemaind = $db->first()->dateRemaind;
          $rmd->textRemaind = $db->first()->textRemaind;
          echo json_encode($rmd);
        }
      }elseif ($json->op=="set") {
        $db = Db::getInstance();
        $fullId = $memeberOfId . date("Y") . "0000000000";
        $fullId += $json->id;
        if($db->update('register_bureaudordre',$fullId,array('dateRemaind'=>$json->dRemaind,'textRemaind'=>$json->tRemaind))){
          if($json->dRemaind != "" || $json->dRemaind != null){
            echo "fa-bell";
          }else{
            echo "fa-bell-O";
          }
        }else{
          echo "not ok";
        }
      }
    }
  }else{
    Redirect::to('login.php');
  }
