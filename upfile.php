<?php
  require_once 'core/init.php';
  $file = new stdClass();
  if(isset($_POST['hidden']) && $_POST['hidden']!=""){
    $location = "./FileUpload/tmp/".$_POST['hidden'];
    if(file_exists($location)){
      unlink($location);
    }
  }
  if(isset($_FILES['file']['name'])){
    $type = $_FILES['file']['type'];
    $size = $_FILES['file']['size'];
    $test = explode(".", $_FILES['file']['name']);
  	$ext = end($test);
  	$name=Hash::salt(30).".".$ext;
    $location = "./FileUpload/tmp/".$name;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $location)){
      $file->name = $name;
      $file->type = $type;
      $file->size = $size;
      echo json_encode($file);
  	}else{
  		echo 0;
  	}
  }
?>
