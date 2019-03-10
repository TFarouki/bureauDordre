<?php
  require_once 'core/init.php';
  $salt =   Hash::salt(32);
  $hash =   Hash::make('123456',$salt);


?>
<input type="text" value="<?php echo $salt;?>" />
<input type="text" value="<?php echo $hash;?>" />*/
<?php
  /*$user = new User();
  $user->create(array('username' => 'taoufik',
                      'password'=> $hash,
                      'salt' => $salt,
                      'name' => 'taoufik',
                      'joined' => date('Y-m-d H:i:s'),
                      'group' => 1
                ));
  $ar = array('username' => 'taoufik',
              'password'=> $hash,
              'salt' => $salt,
              'name' => 'taoufik',
              'joined' => date('Y-m-d H:i:s'),
              'group' => 1
            );*/
  $db = Db::getInstance();
  $db->insert('users', array('username' => 'taoufik',
              'password'=> ,
              'salt' => $salt,
              'name' => 'taoufik',
              'joined' => date('Y-m-d H:i:s'),
              'group' => 1
            ));
?>
