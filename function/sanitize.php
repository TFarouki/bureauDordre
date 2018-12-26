<?php
  function escape($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
  }

  function echolog($string)
  {
    echo "<script>console.log('" . $string . "');</script>";
  }
?>
