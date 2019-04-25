<?php
  class WordWriter{
    private $list= [];
    public function update($templatePath,$tokken = array(),$newName){
      $templatePath = trim($templatePath,"/");
      $dir = dirname($templatePath);
      Tools::unzipe($templatePath,$dir."/temp");
      $file = file_get_contents($dir."/temp/word/document.xml");
      foreach ($tokken as $key => $value) {
        $file = str_replace("{".$key."}",$value,$file);
      }
      unlink($dir."/temp/word/document.xml");
      file_put_contents($dir."/temp/word/document.xml",$file);
      if(is_file($dir.$newName.".docx")){
        unlink($dir.$newName.".docx");
      }
      Tools::zip($dir."/".$newName,$dir."/temp");
      Tools::deleteDirectory($dir."/temp");
    }
  }
?>
