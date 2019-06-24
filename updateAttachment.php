<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $user = new User();
  $db = Db::getInstance();
  $return->stat = false;
  $return->icon = false;
  if($user->isLoggedIn()){
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $db->query("SELECT * FROM docnum Where num_ordre = ".$json->num_ordre);
      if($db->count()){
        if(!$db->update("docnum",array("num_order"=>$json->num_ordre),array("dossierAssocier"=>$json->dossierAssocier,"demandeur"=>$json->demandeur,"remarque"=>$json->remarque))){
          $return->error1 = "لم يتم تحديث سجل ارتباط الملف";
        }else{
          if(!$db->update("register_bureaudordre",array("num_ordre"=>$json->num_ordre),array("dossierAssocier"=>$json->dossierAssocier))){
            $return->error1 = "لم يتم تحيين سجل الضبط";
          }else{
            $return->icon = true;
            $return->stat = true;
          }
        }
      }else{
        $db->query("SELECT type,objet,fileID FROM register_bureaudordre Where num_ordre = ".$json->num_ordre);
        if($db->count()){
          if(!$db->insert("docnum",array("type"=>$db->first()->type,
                                          "object"=>$db->first()->objet,
                                          "idFile"=>$db->first()->fileID,
                                          "num_ordre"=>$json->num_ordre,
                                          "dossierAssocier"=>$json->dossierAssocier,
                                          "demandeur"=>$json->demandeur,
                                          "remarque"=>$json->remarque))){
            $return->error2 = "لم يتم تحديث سجل ارتباط الملف";
          }else{
            if(!$db->update("register_bureaudordre",array("num_ordre"=>$json->num_ordre),array("dossierAssocier"=>$json->dossierAssocier))){
              $return->error2 = "لم يتم تحيين سجل الضبط";
            }else{
              $return->stat = true;
              $return->icon = true;
            }
          }
        }else{
          $return->error = "لم يتم العثور على اصل التسجيل بسجل مكتب الضبط";
        }
      }
    }else{
      $return->post = "لم يتم ارسال اي استعلام";
    }
    echo json_encode($return);
  }else{
    Redirect::to('login.php');
  }

 ?>
