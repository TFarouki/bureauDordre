<?php
  session_start();
  $GLOBALS['config'] = array(
    'MariaDB' => array(
      'host' => '127.0.0.1:3307',
      'username' => 'root',
      'password' => '',
      'db' => 'office'
    ),
    'remember' =>  array(
      'cookie_name' => 'hash',
      'cookie_expiry' => 604800
    ),
    'session' => array(
      'session_name' => 'user',
      'token_name' => 'token'
    )
  );
  spl_autoload_register(function($class){
    require_once 'class/' . $class . '.php' ;
  });

  require_once 'function/sanitize.php';


  if (Cookie::exists(Config::get("remember/cookie_name")) && !Session::exists(Config::get("session/session_name"))) {
    $hash=Cookie::get(Config::get("remember/cookie_name"));
    $hashcheck = Db::getInstance()->get('user_session' ,array('hash','=',$hash));
    if($hashcheck->count()){
      $user = new User($hashcheck->first()->user_id);
      $user->login();
    }
  }
?>
