<?php
  /*for ($i=1; $i < 11; $i++) {
    echo $_POST['form'.$i]."<br />";
  }*/
  require_once 'core/init.php';
  $return = new stdClass();
  $idFile = null;
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

        if(isset($json->fileTmpName) && !empty($json->fileTmpName)){
          $file = json_decode($json->fileTmpName);
          $src = "./FileUpload/tmp/".$user->data()->id."/".$file->name;
          $location = "./FileUpload/uploadFile/".date("Y")."/".date("m")."/".date("d")."/";
          if(!is_dir($location)){
            mkdir($location, 0777, true);
          }
          if (rename($src, $location.$file->name)){
            $tokken = Hash::salt(50);
            if(!$db->insert("upfile_register",array("name"=>$file->name,"type"=>$file->type,"size"=>$file->size,"path"=>$location,"statuts"=>"","tokken"=>$tokken))){
              $return->uploadReg = "لم يتم تسجيل معلومات الملف بالسجل..!";
            }else{
              $db->query("SELECT idFile FROM upfile_register where tokken = '".$tokken."'");
              if($db->count()){
                $idFile = $db->first()->idFile;
              }
            }
          }else{
            $return->move = "لقد وقع خطأ اثناء تحميل الملف ..!";
          }
        }

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
                        "fileID" => $idFile,
                        "redacteur" => escape($user->data()->name),
                        "group_reg" => $memeberOfLabel
                      );
        if($db->insert("register_bureaudordre",$values)){
          $lastId = $memeberOfId ."".date("Y").""."0000000000";
          $lastId += (isset($json->lastId))?$json->lastId:0;
          $c = $newID - $lastId;
          $db->query("SELECT * FROM register_bureaudordre where group_reg = '$memeberOfLabel' AND dateEnrg > '".(date("Y")-1)."/12/31' AND num_ordre <= {$newID} ORDER bY num_ordre DESC LIMIT 0,{$c}");
          $return->json = $db->results();
        }else{
          $return->insert = "لقد حدث خطأ عند محاولة اضافة التسجيل  !";
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
