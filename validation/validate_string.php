<?php

  /**
   * returns true if string email is valid ; otherwise false;
   * @fieldName: the name of the field for the data.
   * @errorName: the name of the field to show in validation error. DEFAULT to fieldname;
   */
  function validate_str($fieldName, $errorName){
    global $validation_error, $here;
    
    if(isset($_POST[(string)$fieldName])){
      // $here=$_POST[(string)$fieldName];
      $input = $_POST[(string)$fieldName];
      if(!$input || $input == "NULL"){
        
        $validation_error[(string)$fieldName] = $errorName." is required!";
        return false;
      }
      $str = trim($input);
      
      if(strlen($str) == 0){
        $validation_error[(string)$fieldName] = $errorName." is required!";
        return false;
      }
      
      $errorName = $errorName ?? $fieldName;
      if(! filter_var($str, FILTER_SANITIZE_STRING)) {
        $validation_error[(string)$fieldName] = "Invalid ".$errorName.".";
        return false;
      } else {
        $validation_error[(string)$fieldName] = "";
        return true;
      }
    } else {
      $validation_error[(string)$fieldName] = $errorName." is required!";
      return false;
    }
  }


?>