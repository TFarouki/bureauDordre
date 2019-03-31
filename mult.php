<?php
  require_once 'core/init.php';
  $return = new stdClass();
  $inserted = true;
  $user = new User();
  $newID = 0;
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
    $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
    if(isset($_POST["json"])){
      $json = json_decode($_POST["json"]);
      if(isset($json->nbCopy) && !empty($json->nbCopy)){
        $lastid = ($memeberOfId . date("Y") ."0000000000") + $json->fristRow;
        $db = Db::getInstance();
        $db->query("SELECT * FROM register_bureaudordre where group_reg = '$memeberOfLabel' AND dateEnrg > '".(date("Y")-1)."/12/31'");
        $sufex = $memeberOfId . date("Y") . "0000000000";
        $newID = $sufex + $db->count();
        $json->id +=$sufex;
        $db->query("SELECT * FROM register_bureaudordre WHERE num_ordre = {$json->id}");
        $originDateR = (empty($db->first()->dateRemaind))?'NULL':'\''.$db->first()->dateRemaind.'\'';
        $originFileID = (empty($db->first()->fileID))?'NULL':'\''.$db->first()->fileID.'\'';

        $sql="INSERT INTO register_bureaudordre
              (`num_ordre`,`dateEnrg`,`direction`,`dateArriver`,`expediteur`,`destinataire`,`type`,`objet`,`dossierAssocier`,`dateRemaind`,`textRemaind`,`fileID`,`redacteur`,`group_reg`,`stat`) values";
        for ($i=1; $i <= $json->nbCopy; $i++){
          $num_ordre=$newID + $i;
          $sql .= "(".$num_ordre.",'".$db->first()->dateEnrg."','".$db->first()->direction."','".$db->first()->dateArriver."','".$db->first()->expediteur."','".$db->first()->destinataire."','".$db->first()->type."','";
          $sql .= $db->first()->objet."','".$db->first()->dossierAssocier."',$originDateR,'".$db->first()->textRemaind."',$originFileID,'".$user->data()->name."','".$memeberOfLabel."','copy of ".$lastid."')";
          if($i<$json->nbCopy){
            $sql .=",";
          }
        }
        if(!$db->query($sql)){
          echo "not work";
        }else{

          $sql = "SELECT * FROM register_bureaudordre where group_reg = '$memeberOfLabel' AND dateEnrg > '".(date("Y")-1)."/12/31' AND num_ordre <= {$num_ordre} AND num_ordre > {$lastid} ORDER bY num_ordre DESC";
          $db->query($sql);
          echo json_encode($db->results());
        }

      }
    }else{
      echo "no data was sended";
    }
  }else{
    Redirect::to("login.php");
  }



/*insert into register_bureaudordre (`num_ordre`,`dateEnrg`,`direction`,`dateArriver`,`expediteur`,`destinataire`,`type`,`objet`,`dossierAssocier`,`dateRemaind`,`textRemaind`,`fileID`,`redacteur`)
select 120190000000009,`dateEnrg`,`direction`,`dateArriver`,`expediteur`,`destinataire`,`type`,`objet`,`dossierAssocier`,`dateRemaind`,`textRemaind`,`fileID`,`redacteur` from register_bureaudordre where num_ordre = 120190000000002
*/
?>
