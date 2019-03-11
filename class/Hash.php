<?php
  class Hash{
    public static function make($string, $salt =''){
      return hash('sha256' , $string . $salt);
    }

    public static function salt($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function unique(){
      return self::make(uniqid());
    }
  }
?>
