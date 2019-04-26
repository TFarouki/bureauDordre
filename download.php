<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
      $id = json_decode($_POST['json'])->id;
      $db->get('register_bureaudordre',array("num_ordre","=",$id));
      if($db->count()>0){
        $num_order = substr($id,1,4).'/'.substr($id,10,5);
        $text = "شهادة التسليم تتعلق  بلوائح الخبرات الغير منجزة برسم سنتي 2017 و 2018";
        $remarque = "  نرجعها  لكم بعد القيام بالمطلوب، تبعا لإرسالكم عدد 424/2019 بتاريخ 01/04/2019، ";
        $nb_copy = "02";
        $order = "01";
        $ar=array("date"=>date('Y-m-d'),"num_order"=>$num_order,"expediteur"=>$db->first()->expediteur,"destinataire"=>$db->first()->destinataire,"order"=>$order,"text"=>$text,"nb_copy"=>$nb_copy,"remarque"=>$remarque);
        $ww = new WordWriter();
        $filename = "نموذج_الارسالية.docx";
        $filename2 = "‫ارسالية.docx";
        $src = "./template/".$filename;
        $ww->update($src,$ar,$filename2);
        $file = "./template/".$filename2;
        $return->filename = $filename2;
        $return->file = $file;
        $return->id = $num_order;
      }
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }
?>
