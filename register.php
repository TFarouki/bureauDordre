
<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <?php include  'includes/include_head.php';?>
    <title>فتح حساب جديد / برنامج مكتب الضبط</title>
    <!------ Include the above in your HEAD tag ---------->
  </head>
    <?php
      require_once 'core/init.php';
      $html_code = '';
      if(Input::exists() && Token::check(Input::get("token"))){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
          'username' => array(
            'name_ar' => 'اسم المستخدم',
            'required' => true,
            'min' => 4,
            'max' => 20,
            'unique' => 'users'
          ),
          'password' => array(
            'name_ar' => 'كلمة السر',
            'required' => true,
            'min' => 6,
          ),
          'password_again' => array(
            'name_ar' => 'تأكيد كلمة السر',
            'required' => true,
            'min' => 6,
            'matches' => 'password'
          ),
          'name' => array(
            'name_ar' => 'الاسم الكامل للسمتخدم',
            'required' => true,
            'min' => 4,
            'max' => 50
          )
        ));

        if ($validation->passed()){
          $user = new User();
          $salt = Hash::salt(32);
          try{
            $user->create(array(
              'username' => Input::get('username'),
              'password' => Hash::make(Input::get('password'),$salt),
              'salt' => $salt,
              'name' => Input::get('name'),
              'joined' => date('Y-m-d H:i:s'),
              'group' => 1,
              "actif" => 0
            ));
          }catch(Exception $e){
            die($e->getMessage());
          }
          Session::flash("success" , "you have been registred successfuly!..");
          Redirect::to("index.php");
        }else{
          $html_code = '<div class="alert alert-warning alert-dismissible fade show text-center">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>تحدير!</strong> '. $validation->error(0) .'.
            </div>
          ';
        }
      }
    ?>
    <body>
      <?php echo $html_code;?>
      <div class="container">
        <h1 class="form-heading">برنامج مكتب الضبط</h1>
        <div class="login-form">
            <div class="main-div">
              <div class="panel">
                <h2>فتح حساب جديد</h2>
                <p>المرجوا ملأ استمارة التسجيل التالية</p>
              </div>
                <form id="Login" action="" method="post">
                  <div class="form-group">
                    <input type="text" class="form-control" name="username" id="Username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off" placeholder="اسم المستخدم">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="كلمة السر">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" name="password_again" id="password_again" placeholder="تأكيد كلمة السر">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" value="<?php echo escape(Input::get('name'));?>" placeholder="الاسم الكامل للسمتخدم">
                  </div>
                  <input type="hidden" name="token" value="<?php echo Token::generate();?>">

                  <button type="submit" class="btn btn-primary font-weight-bold">تسجيل</button>
                </form>
            </div>
        </div>
      </div>
    </body>
</html>
