<?php
  require_once 'core/init.php';
  include_once('./library/phpExcel/PHPExcel/IOFactory.php');
  $user = new User();
  $response = new stdClass();
  if($user->isLoggedIn()){
    $memeberOfLabel = $user->memeberOf("مكتب الضبط")["label"];
    $memeberOfId = $user->memeberOf("مكتب الضبط")["id"];
    if(isset($_POST['json'])){
      $json = json_decode($_POST['json']);
      $fileName = 'سجل مكتب الضبط'.$json->year;
      $db = Db::getInstance();
      $db->query('SELECT num_ordre,dateEnrg,direction,dateArriver,expediteur,destinataire,type,objet,dossierAssocier FROM register_bureaudordre WHERE YEAR(dateEnrg) = "'.$json->year.'" AND group_reg ="'.$memeberOfLabel.'" ORDER BY num_ordre');
      if($db->count()>0){
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");
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
        $objPHPExcel->getActiveSheet()->getStyle("A1:I1")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $excelLastRow =(int) $db->count() +1;
        $border_style= array(
                            'borders' => array(
                                                "right" => array(
                                                                    'style' =>PHPExcel_Style_Border::BORDER_THIN,
                                                                    'color' => array('argb' => '766f6e')
                                                              ),
                                                "left" => array(
                                                                    'style' =>PHPExcel_Style_Border::BORDER_THIN,
                                                                    'color' => array('argb' => '766f6e')
                                                              ),
                                                "top" => array(
                                                                    'style' =>PHPExcel_Style_Border::BORDER_THIN,
                                                                    'color' => array('argb' => '766f6e')
                                                              ),
                                                "bottom" => array(
                                                                    'style' =>PHPExcel_Style_Border::BORDER_THIN,
                                                                    'color' => array('argb' => '766f6e')
                                                              )
                                              )
                          );
        for ($i=1; $i <= 9; $i++) {
          $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i+64))->setAutoSize(true);
          if(!fmod($i , 2) || $i==9){
            $objPHPExcel->getActiveSheet()->getStyle(chr($i+64)."1:".chr($i+64).$excelLastRow)->applyFromArray($border_style);
          }
        }
        for($i=0; $i<$db->count(); $i++){
          if(!fmod($i+1 , 2) || $i+1 ==$db->count()){
            $ii=$i+1;
            $objPHPExcel->getActiveSheet()->getStyle("A".$ii.":I".$ii)->applyFromArray($border_style);
          }
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
        $objPHPExcel->getActiveSheet()->getStyle("A".$excelLastRow.":I".$excelLastRow)->applyFromArray($border_style);
        $objPHPExcel->getActiveSheet()->getStyle('A1:I'.$excelLastRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
