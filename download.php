<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
      $template = $user->memeberOf("مكتب الضبط")["file"];
      $json = json_decode($_POST['json']);
      $id = $json->id;
      $db->get('register_bureaudordre',array("num_ordre","=",$id));
      if($db->count()>0){
        $num_order = substr($id,1,4).'/'.substr($id,10,5);
        $unm_order2 = substr($id,1,4).'-'.substr($id,10,5);

        $text = implode("<w:br/>",explode("{br}",$json->text));
        $remarque = implode("<w:br/>",explode("{br}",$json->remarque));
        $nb_copy = implode("<w:br/>",explode("{br}",$json->nb_copy));
        $order = implode("<w:br/>",explode("{br}",$json->nb_order));
        $copy = explode("{br}",$json->nb_copy);
        $total = 0;
        foreach ($copy as $key => $value) {
          $total += (int) $value;
        }
        $ar=array(
          "date"=>date('Y-m-d'),
          "num_order"=>$num_order,
          "expediteur"=>$db->first()->expediteur,
          "destinataire"=>$db->first()->destinataire,
          "order"=>$order,
          "text"=>$text,
          "nb_copy"=>$nb_copy,
          "remarque"=>$remarque,
          "total"=>$total
        );
        $ins = array(
                "text"=>$json->text,
                "nb_copy"=>$json->nb_copy,
                "nb_order"=>$json->nb_order,
                "remarque"=>$json->remarque,
                "user_id"=>$user->data()->id,
                "date_Reg"=>date('Y-m-d'),
                "id_order"=>$id
              );
        if($json->id_origin!=$id){
          if(!$db->insert('content_foruser',$ins)){
            echo "insertion dosn't work";
          }
        }
        $ww = new WordWriter();
        $filename = "نموذج_الارسالية.docx";
        $filename2 = "‫ارسالية.docx";
        $src = "./template/".$filename;
        $ww->update($template,$ar,$filename2);
        $file = "./template/".$filename2;
        $file2='./tmp/'.$user->data()->id.'/ارسالية_'.$unm_order2.'.docx';
        $folder = './tmp/'.$user->data()->id;
        $filename2 = 'ارسالية_'.$unm_order2.'.docx';
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
