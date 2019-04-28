<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
      $json = json_decode($_POST['json']);
      $id = $json->id;
      $db->get('register_bureaudordre',array("num_ordre","=",$id));
      if($db->count()>0){
        $num_order = substr($id,1,4).'/'.substr($id,10,5);
        $unm_order2 = substr($id,1,4).'-'.substr($id,10,5);

        $text = $json->text;
        $remarque = $json->remarque;
        $nb_copy = $json->nb_copy;
        $order = $json->nb_order;
        $ar=array("date"=>date('Y-m-d'),"num_order"=>$num_order,"expediteur"=>$db->first()->expediteur,"destinataire"=>$db->first()->destinataire,"order"=>$order,"text"=>$text,"nb_copy"=>$nb_copy,"remarque"=>$remarque);
        $ww = new WordWriter();
        $filename = "نموذج_الارسالية.docx";
        $filename2 = "‫ارسالية.docx";
        $src = "./template/".$filename;
        $ww->update($src,$ar,$filename2);
        $file = "./template/".$filename2;
        $file2='./tmp/'.$user->data()->id.'/ارسالية_'.$unm_order2.'.docx';
        $folder = './tmp/'.$user->data()->id;
        Tools::fmkdir($folder);
        if(file_exists($file)){
          if(!rename($file,$file2)){
            echo "not moved !";
          }
        }
        $return->filename = $filename2;
        $return->file = $file2;
      }
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }
?>
