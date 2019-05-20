<?php
  require_once 'core/init.php';
  $user = new User();
  $return = new stdClass();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db = Db::getInstance();
      $db->query("SELECT * FROM upfile_register where idFile = ".$json->id);
      if($db->count()>0){
        $fullpath = $db->first()->path . $db->first()->name;
        $path = './tmp/'.$user->data()->id.'/'.$db->first()->name;
        Tools::fmkdir('./tmp/'.$user->data()->id.'/');
        if(file_exists($fullpath)){
          if (copy($fullpath, $path)){
            $return->path = $path;
            //echo json_encode($path);
          }
        }else{
          $return->error = "لا يوجد ملف مقابل لهدا التسجيل";
        }
      }else{
        $return->error = "لا يوجد ملف مقابل لهدا العنصر رغم وجود تسجيل له";
        //echo "لا يوجد ملف مقابل لهدا العنصر رغم وجود تسجيل له";
      }
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }
?>
