<?php
  require_once 'core/init.php';
  $user = new User();
  $response = new stdClass();
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
    $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
    if(isset($_POST['json'])){
      $id = json_decode($_POST['json']);
      $db = Db::getInstance();
      $fullId = $memeberOfId . date("Y") . "0000000000";
      $fullId += $id;
      $db->get("register_bureaudordre",array("num_ordre","=",$fullId));
      if($db->count()){
        $fileId = $db->first()->fileID;
        $db->get("upfile_register",array("idFile","=",$db->first()->fileID));
        if($db->count()){
          $fullpath = $db->first()->path . $db->first()->name;
          $path = './tmp/'.$user->data()->id.'/'.$db->first()->name;
          if(!is_dir('./tmp/'.$user->data()->id.'/')){
            mkdir('./tmp/'.$user->data()->id.'/');
          }
          if(file_exists($fullpath)){
            if (copy($fullpath, $path)){
              $response->path = $path;
              //echo json_encode($path);
            }
          }else{
            $response->error = "لا يوجد ملف مقابل لهدا التسجيل";
          }
        }else{
          $response->error = "لا يوجد ملف مقابل لهدا العنصر رغم وجود تسجيل له";
          //echo "لا يوجد ملف مقابل لهدا العنصر رغم وجود تسجيل له";
        }
      }else{
        $response->error = "لا يوجد ملف مقابل لهدا التسجيل";
        //echo "لا يوجد ملف مقابل لهدا التسجيل";
      }
      echo json_encode($response);
    }
  }else{
      Redirect::to("login.php");
  }
?>
