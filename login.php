
<?php
  require_once 'core/init.php';
  $html_code = '';
  $checklogout = new User();
  if ($checklogout->exists()) {
    $checklogout->logout();
  }


  if (Input::exists()) {
    if(Token::check(Input::get("token"))){
      $validate = new Validate();
      $validation = $validate->check($_POST,array(
        "username" => array('name_ar' => 'اسم المستخدم','required' => true,'max' => 20),
        "password" => array('name_ar' => 'كلمة السر','required' => true, 'max' => 30)
      ));
      if($validation->passed()){
        $user = new User();
        $remember = (Input::get("remember")==="on")?true:false;
        if($user->login(Input::get("username"),Input::get("password"),$remember)){
          Redirect::to("index.php");
        }else{
          $html_code = '<div class="alert alert-warning alert-dismissible fade show text-center">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>تحدير!</strong>  اسم المستخدم او كلمة السر غير صحيحة!المرجوا المحاولة من جديد.
            </div>
          ';
        }
      }else{
        $html_code = '<div class="alert alert-warning alert-dismissible fade show text-center">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>تحدير!</strong> حقل '. $validation->error(0) .'.
          </div>
        ';
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <?php include 'includes/include_head.php';?>
    <title>تسجيل الدخول / برنامج مكتب الضبط</title>
  </head>
  <body id="LoginForm">
    <?php echo $html_code;?>
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
