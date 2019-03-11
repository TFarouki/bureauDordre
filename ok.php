<?php
  require_once 'core/init.php';


  if (Session::exists("success")) {
    echo Session::flash("success");
  }


  $user = new User();
  if($user->isLoggedIn()){
    if(isset($_POST["q"])){
      $q = $_POST["q"];
      $db = Db::getInstance();
      $db->query("SELECT DISTINCT expediteur FROM register_bureaudordre WHERE expediteur like N'".$q."%' ORDER BY expediteur ASC;");
      for ($i=0; $i < $db->count(); $i++) {
        ?>
          <option dir="ltf" value="<?php echo $db->results()[$i]->expediteur;?>">
        <?php
      }
    }
  }else{
    Redirect::to("login.php");
  }
?>
