<?php
  /*for ($i=1; $i < 11; $i++) {
    echo $_POST['form'.$i]."<br />";
  }*/
  require_once 'core/init.php';
  $return = new stdClass();
  if (Session::exists("success")) {
    echo Session::flash("success");
  }
  $user = new User();
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
    $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
    if(isset($_POST["json"])){
      $json = json_decode($_POST["json"]);
      $ajson = (array) $json;
      $validate = new Validate();
      $validation = $validate->check($ajson, array(
        "expediteur" => array(
          'name_ar' => 'المرسل',
          'required' => true
        ),
        "destinataire" => array(
          'name_ar' => 'المرسل اليه',
          'required' => true
        )
        ,"type" => array(
          'name_ar' =>  'النوع',
          'required' => true
        ),
        "dateArriver" => array(
          'name_ar' => 'تاريخ التحرير',
          'required' => true
        )
      ));
      if($validation->passed()){
        $db = Db::getInstance();
        $db->query("SELECT * FROM register_bureaudordre where group_reg = '$memeberOfLabel' AND dateEnrg > '".(date("Y")-1)."/12/31'");
        $newID= $memeberOfId . "" . date("Y") . "" . "0000000000";
        $newID += $db->count() + 1;
        $values = Array(
                        "num_ordre" => $newID,
                        "dateEnrg" => date("Y-m-d H:i:s"),
                        "direction" => ($json->sendorinbox)?"وارد":"صادر",
                        "dateArriver" => $json->dateArriver,
                        "expediteur" => $json->expediteur,
                        "destinataire" => $json->destinataire,
                        "type" => $json->type,
                        "objet" => ($json->object!="")?$json->object:null,
                        "dossierAssocier" => ($json->dossierAssocier!="")?$json->dossierAssocier:null,
                        "dateRemaind" => ($json->remaindDate!="")?$json->remaindDate:null,
                        "textRemaind" => ($json->remaindText!="")?$json->remaindText:null,
                        "redacteur" => escape($user->data()->name),
                        "group_reg" => $memeberOfLabel
                      );
        if(!$db->insert("register_bureaudordre",$values)){
          $return->insert = "لقد حدث خطأ عند محاولة اضافة التسجيل  !";
        }else{
          $lastId = $memeberOfId ."".date("Y").""."0000000000";
          $lastId += $json->lastId;
          $c = $newID - $lastId;
          $db->query("SELECT * FROM register_bureaudordre where group_reg = '$memeberOfLabel' AND dateEnrg > '".(date("Y")-1)."/12/31' ORDER bY num_ordre DESC LIMIT 0,{$c}");
          $return->json = $db->results();
        }
      }else{
        $return->validation = $validation->error(0);
      }
    }else{
      $return->error = "لا يوجد اي معلومات مرسلة من المصدر";
    }
    echo json_encode($return);
  }else{
    Redirect::to("login.php");
  }
?>
