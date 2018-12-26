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
      "name" => array(
        "required" => true,
        "min" => 4,
        "max" => 50
      )
    ));

    if($validation->passed()){
      try {
        $user->update(array(
          "name" => Input::get("name")
        ));
        Session::flash("home","Your details has been updated");
        Redirect::to("index.php");
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
        <label for="name">Full Name : </label>
        <input type="text" name="name" value="<?php echo escape($user->data()->name);?>">
        <input type="submit" name="" value="update">
        <input type="hidden" name="" value="<?php echo Token::generate();?>">
      </div>
    </form>
  </body>
</html>
