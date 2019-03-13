<?php
  require_once 'core/init.php';
  if(isset($_POST['hidden']) && $_POST['hidden']!=""){
    $location = "./FileUpload/tmp/".$_POST['hidden'];
    unlink($location);
  }
  if(isset($_FILES['file']['name'])){
    $test = explode(".", $_FILES['file']['name']);
  	$ext = end($test);
  	$name=Hash::salt(30).".".$ext;
    $location = "./FileUpload/tmp/".$name;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
  		echo $name;
  	}else{
  		echo 0;
  	}
  }
?>
