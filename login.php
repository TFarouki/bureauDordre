
<?php
  require_once 'core/init.php';

  $checklogout = new User();
  if ($checklogout->exists()) {
    $checklogout->logout();
  }


  if (Input::exists()) {
    if(Token::check(Input::get("token"))){
      $validate = new Validate();
      $validation = $validate->check($_POST,array(
        "username" => array('required' => true,'max' => 20),
        "password" => array('required' => true, 'max' => 30)
      ));
      if($validation->passed()){
        $user = new User();
        $remember = (Input::get("remember")==="on")?true:false;
        if($user->login(Input::get("username"),Input::get("password"),$remember)){
          Redirect::to("index.php");
        }else{
          echo "Sorry, username and password are incorrect ,please try again.";
        }
      }else{
        foreach ($validation->errors() as $error) {
          echo "<p>". $error . "</p>";
        }
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <?php include 'includes/include_head.php';?>
    <title>تسجيل الدخول / برنامج مكتب الضبط</title>
    <!------ Include the above in your HEAD tag ---------->
  </head>
  <body id="LoginForm">
    <div class="container">
      <h1 class="form-heading">برنامج مكتب الضبط</h1>
      <div class="login-form">
          <div class="main-div">
            <div class="panel">
              <h2>تسجيل الدخول</h2>
              <p>المرجوا ادخال اسم المستخدم و كلمة السر</p>
            </div>
              <form id="Login" action="" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" id="username" name="username" placeholder="اسم المستخدم">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="كلمة السر">
                </div>
                <div class="remember">
                  <label for="remember">
                    <input type="checkbox" name="remember" id="remember"><span class="remspan">تذكرني.</span>
                  </label>
                </div>
                <div class="forgot">
                  <a href="reset.html">هل نسيت كلمة السر ؟</a>
                </div>
                <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
                <button type="submit" class="btn btn-primary font-weight-bold">تأكيد</button>
              </form>
            </div>
            <p class="botto-text"> @المحكمة الادارية باكادير</p>
          </div>
        </div>
      </div>
    </body>
</html>
<!--
  <head>
    <meta charset="utf-8">
    <title>OOPLR</title>
  </head>
  <body>
    <form class="" action="" method="post">
      <div class="field">
        <label for="username">username</label>
        <input type="text" name="username" id="username" value="">
      </div>
      <div class="field">
        <label for="passwprd">password</label>
        <input type="password" name="password" id="password" value="">
      </div>
      <div class="field">
        <label for="remember">
        <input type="checkbox" name="remember" id="remember">Remember me</label>
      </div>
      <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
      <button type="submit" name="submit">submit</button>
    </form>
  </body>
</html-->
