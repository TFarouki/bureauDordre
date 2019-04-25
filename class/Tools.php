<?php
  class Tools{
    public static function deleteDirectory($dir) {
      if (!file_exists($dir)) {
          return true;
      }
      if (!is_dir($dir)) {
          return unlink($dir);
      }
      foreach (scandir($dir) as $item) {
          if ($item == '.' || $item == '..') {
              continue;
          }
          if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
              return false;
          }
      }
      return rmdir($dir);
    }
    public static function unzipe($sourceFile,$DestinationFolder){
      if(!is_dir($DestinationFolder)){
        mkdir($DestinationFolder);
      }else{
        self::deleteDirectory($DestinationFolder);
        mkdir($DestinationFolder);
      }
      $zip = new ZipArchive;
      if ($zip->open($sourceFile) === TRUE) {
          $zip->extractTo($DestinationFolder);
          $zip->close();
      }
    }
    public static function getListFile($dir,&$list){
      if ($handle = opendir($dir)){
          while (false !== ($entry = readdir($handle))){
              if ($entry != "." && $entry != ".."){
                if(!is_dir($dir ."/".$entry)){
                  array_push($list , $dir ."/".$entry);
                }else{
                  self::getListFile($dir ."/". $entry,$list);
                }
              }
          }
          closedir($handle);
      }
    }
    public static function zip($zipFilePath,$source){
      $list = [];
      $source = trim($source,"/");
      self::getListFile($source,$list);
      $zip = new ZipArchive;
      if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE){
        foreach ($list as $key => $value){
          $zip->addFile($value,str_replace($source."/","",$value));
        }
      }
      $zip->close();
    }
    public static function downloadFile($file){
      if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        unlink($file);
      }
    }
  }
?>
