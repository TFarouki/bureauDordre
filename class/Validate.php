<?php
class Validate{
  private $_passed = false,
          $_errors = array(),
          $_db = null;

  public function __construct(){
    $this->_db = DB::getInstance();
  }

  public function check($source, $items = array()){
    foreach ($items as $item => $rules) {
      $name_ar = $rules['name_ar'];
      unset($rules['name_ar']);
      foreach ($rules as $rule => $rule_value) {
        $value = trim($source[$item]);
        if($rule === 'required' && empty($value)){
          $this->AddErr("{$name_ar} اجباري");
        }else if(!empty($value)){
          switch ($rule) {
            case 'min':
              if(strlen($value) < $rule_value){
                $this->AddErr("{$name_ar} يجب ان يحتوي {$rule_value} حروف على الاقل");
              }
              break;
            case 'max':
              if(strlen($value) > $rule_value){
                $this->AddErr("{$name_ar} يجب ان يحتوي {$rule_value} حروف على الاكثر");
              }
              break;
            case 'matches':
            if($value != $source[$rule_value]){
              $this->AddErr("{$name_ar} يجب ان يوافق {$items[$rule_value]['name_ar']}");
            }
              break;
            case 'unique':
              $check = $this->_db->get($rule_value,array($item, '=' , $value));

              if($check->count()){
                $this->AddErr("{$name_ar} {$value} غير متوفر");
              }
              break;
          }
        }
      }
    }
    if(empty($this->_errors)){
      $this->_passed = true;
    }
    return $this;
  }

  private function AddErr($error)
  {
    $this->_errors[] = $error;
  }

  public function errors()
  {
    return $this->_errors;
  }

  public function error($key)
  {
    return $this->_errors[$key];
  }

  public function passed()
  {
    return $this->_passed;
  }
}
?>
