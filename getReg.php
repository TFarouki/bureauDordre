<?php
  require_once 'core/init.php';
  include_once('./library/phpExcel/PHPExcel/IOFactory.php');

  //set the desired name of the excel file


  $user = new User();
  $response = new stdClass();
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
    $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      //$json = new stdClass();
      //$json->year = 2019;
      $fileName = 'سجل مكتب الضبط'.$json->year;
      $db = Db::getInstance();
      $db->query('SELECT num_ordre,dateEnrg,direction,dateArriver,expediteur,destinataire,type,objet,dossierAssocier FROM register_bureaudordre WHERE YEAR(dateEnrg) = "'.$json->year.'" AND group_reg ="'.$memeberOfLabel.'" ORDER BY num_ordre');
      if($db->count()>0){
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()
        			->setCellValue('A1', 'الرقم الترتيبي')
        			->setCellValue('B1', 'تاريخ التسجيل بالحاسوب')
        			->setCellValue('C1', 'صادر / وارد')
        			->setCellValue('D1', 'تاريخ التوصل')
              ->setCellValue('E1', 'المرسل')
              ->setCellValue('F1', 'المرسل اليه')
              ->setCellValue('G1', 'النوع')
              ->setCellValue('H1', 'الموضوع')
              ->setCellValue('I1', 'الملف المرتبط')
        			;

        for($i=0; $i<$db->count(); $i++){
        	$ii = $i+2;
          $objPHPExcel->getActiveSheet()->setCellValue('A'.$ii, (int) substr($db->results()[$i]->num_ordre,strlen($db->results()[$i]->num_ordre)-10));
          $objPHPExcel->getActiveSheet()->setCellValue('B'.$ii, $db->results()[$i]->dateEnrg);
          $objPHPExcel->getActiveSheet()->setCellValue('C'.$ii, $db->results()[$i]->direction);
          $objPHPExcel->getActiveSheet()->setCellValue('D'.$ii, $db->results()[$i]->dateArriver);
          $objPHPExcel->getActiveSheet()->setCellValue('E'.$ii, $db->results()[$i]->expediteur);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.$ii, $db->results()[$i]->destinataire);
          $objPHPExcel->getActiveSheet()->setCellValue('G'.$ii, $db->results()[$i]->type);
          $objPHPExcel->getActiveSheet()->setCellValue('H'.$ii, $db->results()[$i]->objet);
          $objPHPExcel->getActiveSheet()->setCellValue('I'.$ii, $db->results()[$i]->dossierAssocier);

        }
        $objPHPExcel->getActiveSheet()->setTitle($fileName);

        Tools::fmkdir('./tmp/'.$user->data()->id.'/');
        $fullName = './tmp/'.$user->data()->id.'/'.$fileName . '.xlsx';

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($fullName);

        if(file_exists($fullName)){
          $response->file = $fullName;
          $response->filename = $fileName . '.xlsx';
        }else{
          $response->exist = "file Not exist";
        }
      }
    }
    echo json_encode($response);
  }else{
      Redirect::to("login.php");
  }
?>
