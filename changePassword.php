<?php
require_once 'core/init.php';

$user = new User;
if(!$user->isLoggedIn()){
  Redirect::to('index.php');
}

if (Input::exists()) {
  if(Token::check(Input::get("token"))){
    $validate = new Validate();
    $validation = $validate->check($_POST,array(
      "current_password" => array(
        'required' => true,
        'min' => 6
      ),
      "new_password" => array(
        'required' => true,
        'min' => 6
      ),
      "again_password" => array(
        'required' => true,
        'min' => 6,
        'matches' => 'new_password'
      )
    ));

    if($validation->passed()){
      try {
        if(Hash::make(Input::get("current_password"),$user->data()->salt) != $user->data()->password){
          echo "Your Current password is incorrect!";
        }else {
          $salt = Hash::salt(32);
          $user->update(array(
            "password" => Hash::make(Input::get("new_password"),$salt),
            "salt" => $salt
          ));
          Session::flash("home","Your password has been updated");
          Redirect::to("index.php");

        }
      } catch (Exception $e) {
        die($e->getMessage());
      }

    }else {
      foreach ($validation->errors() as $error) {
        echo $error ."<br>";
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>OOPLR</title>
  </head>
  <body>
    <form class="" action="" method="post">
      <div class="field">
        <label for="current_password">Current password : </label>
        <input type="password" name="current_password" id="current_password" value="">
      </div>
      <div class="field">
        <label for="new_password">new password : </label>
        <input type="password" name="new_password" id="new_password" value="">
      </div>
      <div class="field">
        <label for="again_password">Cofirme password : </label>
        <input type="password" name="again_password" id="again_password" value="">
      </div>
      <input type="submit" name="" value="changePassword">
      <input type="hidden" name="" value="<?php echo Token::generate();?>">
    </form>
  </body>
</html>
