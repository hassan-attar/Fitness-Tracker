<?php
  $validation_error;
  /**
   * returns true if string email is valid ; otherwise false;
   * @fieldName: the name of the field for the data.
   * @errorName: the name of the field to show in validation error. DEFAULT to fieldname;
   */
  function validate_str($fieldName, $errorName){
    if(isset($_POST[$fieldName])){
      $str = trim($_POST[$fieldName]);
      
      $errorName = $errorName || $fieldName;
      if(! filter_var($str, FILTER_SANITIZE_STRING)) {
        $validation_error[$fieldName] = "Invalid ".$errorName.".";
        return false;
      } else {
        $validation_error[$fieldName] = "";
        return true;
      }
    } else {
      $validation_error[$fieldName] = $errorName." is required!";
      return false;
    }
  }


?>