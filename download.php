<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $id = json_decode($_POST['json'])->id;
      $db->get('register_bureaudordre',array("num_ordre","=",$id));
      if($db->count()>0){
        $num_order = substr($id,1,4).'/'.substr($id,10,5);
        $ar=array("date"=>date('Y-m-d'),"num_order"=>$num_order,"expediteur"=>"رئيس المحكمة الادارية بأكادير","destinataire"=>"رئيس المحكمة الابتدائية بتارودانت","order"=>"01","text"=>"شهادة التسليم تتعلق  بلوائح الخبرات الغير منجزة برسم سنتي 2017 و 2018","nb_copy"=>"02","remarque"=>"  نرجعها  لكم بعد القيام بالمطلوب، تبعا لإرسالكم عدد 424/2019 بتاريخ 01/04/2019، ");
        $ww = new WordWriter();
        $src = "./template/‫ارسالية.docx";
        $ww->update($src,$ar,"1.docx");
        //Tools::downloadFile("./template/1.docx");
      }
    }
  }else{
    Redirect::to('login.php');
  }
?>
