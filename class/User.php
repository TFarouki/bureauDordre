<?php
  class User{
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_IsLoggedIn;
    public function __construct($user = null){
      $this->_db = Db::getInstance();
      $this->_sessionName = Config::get("session/session_name");
      $this->_cookieName = Config::get("remember/cookie_name");
      if(!$user){
        if(Session::exists($this->_sessionName)){
          $user = Session::get($this->_sessionName);
          if($this->find($user)){
            $this->_IsLoggedIn = true;
          }else{
            //logout
          }
        }
      }else{
        $this->find($user);
      }
    }

    public function create($fields = array()){
      if(!$this->_db->insert('users',$fields)){
        throw new Exception('there was a problem when try creating your account');
      }
    }

    public function find($user){
      $field = (is_numeric($user))?"id":"username";
      $data = $this->_db->get('users',array($field , "=", $user));

      if ($data->count()) {
        $this->_data = $data->first();
        return true;
      }
      return false;
    }

    public function login($username=null,$password=null,$remember=false){
      if (!$username && !$password & $this->exists()) {
        Session::put(Config::get("session/session_name"),$this->data()->id);
      }else {
        if($this->find($username)){

          if ($this->data()->password === Hash::make($password,$this->data()->salt)){
            Session::put($this->_sessionName,$this->data()->id);

            if($remember){
              $hashcheck = $this->_db->get('user_session',array("user_id","=",$this->data()->id));

              if(!$hashcheck->count()){
                $hash = Hash::unique();
                $this->_db->insert("user_session",array(
                  "user_id" => $this->data()->id,
                  "hash" => $hash
                ));
              }else{
                $hash = $hashcheck->first()->hash;
              }
              Cookie::put($this->_cookieName,$hash,Config::get("remember/cookie_expiry"));
            }
            return true;
          }
        }
        return false;
      }

    }

    public function data($value=''){
      return $this->_data;
    }

    public function update($fields = array(), $id = null){
      if(!$id && $this->isLoggedIn()){
        $id= $this->data()->id;
      }

      if (!$this->_db->update('users',$id,$fields)) {
        throw new Exception("There was a probelm updating");
      }
    }

    public function exists(){
      return (!empty($this->data()))?true:false;
    }

    public function hasPermissions($key){
      $group = $this->_db->get('groups',array('id', '=', $this->data()->group));
      if ($group->count()) {
          $permissions = json_decode($group->first()->permissions,true);
          return $permissions[$key];
      }
      return false;
    }

    public function isLoggedIn(){
      return $this->_IsLoggedIn;
    }

    public function logout(){
      $this->_db->delete('user_session',array('user_id','=',$this->data()->id));

      Session::delete($this->_sessionName);
      Cookie::delete($this->_cookieName);
    }
  }
?>
