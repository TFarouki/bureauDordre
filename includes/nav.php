<nav class="navbar navbar-expand-md navbar-dark bg-dark" style="padding:0;">
    <a class="navbar-brand" href="#">
      <img src="./media/bd2.png" width="40" height="40" class="d-inline-block align-center" alt="">
        برنامج المحكمة الادارية
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'])=='index.php')?'active" style="background-color:#ddd;':''; ?>">
                <a class="nav-link" href="<?php echo (basename($_SERVER['PHP_SELF'])=='index.php')?'#" style="color:#222;':'index.php';?>">الصفحة الرئيسية<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF'])=='order.php')?'active" style="background-color:#ddd;':'';?>">
                <a class="nav-link" href="<?php echo (basename($_SERVER['PHP_SELF'])=='order.php')?'#" style="color:#222;':'order.php';?>">مكتب الضبط</a>

            </li>
        </ul>
        <ul class="navbar-nav mr-auto">
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo escape($user->data()->name); ?> <i class="fa fa-user-circle"></i>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <li><a class="dropdown-item text-right" href="update.php"><i class="fa fa-folder-open" aria-hidden="true"></i> الملف الشخصي</a></li>
                  <li><a class="dropdown-item text-right" href="changePassword.php"><i class="fa fa-lock" aria-hidden="true"></i> تغيير كلمة المرور</a></li>
                  <div class="dropdown-divider"></div>
                  <li><a class="dropdown-item text-right" href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> تسجيل الخروج</a></li>
              </ul>
          </li>
        </ul>
    </div>
</nav>
