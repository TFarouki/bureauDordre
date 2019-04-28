<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $idFile = null;
  $db = Db::getInstance();
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db->get('content_foruser',array("id_order","=",$json->id));
      if($db->count()>0){
        echo json_encode($db->first());
      }else{
        $sql='SELECT * FROM content_foruser WHERE user_id = '.$user->data()->id.' ORDER BY date_Reg DESC';
        $db->query($sql);
        if($db->count()>0){
          echo json_encode($db->first());
        }else{
          $default = '{"id":"1",
                      "nb_order":"01",
                      "text":"شهادة التسليم تتعلق  بلوائح الخبرات الغير منجزة برسم سنتي 2017 و 2018",
                      "nb_copy":"02",
                      "remarque":"  نرجعها  لكم بعد القيام بالمطلوب، تبعا لإرسالكم عدد 424/2019 بتاريخ 01/04/2019، ",
                      "user_id":"5",
                      "date_Reg":"2019-04-28",
                      "id_order":""
                    }';
          //$default = json_encode($default);
          echo $default;
        }
      }
    }
  }else{
    Redirect::to('login.php');
  }
?>
