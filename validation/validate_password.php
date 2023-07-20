<?php

  /**
   * returns true if string email is valid ; otherwise false;
   * @fieldName: the name of the field for the data.
   * @errorName: the name of the field to show in validation error. DEFAULT to fieldname;
   */
  function validate_password(){
    global $validation_error;

    if (isset($_POST['password'])) {
        $password = $_POST['password'];

        $sanitizedPass = filter_var($password, FILTER_SANITIZE_STRING);

        if (preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", trim($sanitizedPass))) {
 
          $validation_error['password'] = "";
          return true;
      } else {
          $validation_error['password'] = "Enter a valid password address. It must contain 8 characters, at least one number and 1 letter.";
          return false;
      }
    } else {
        $validation_error['password'] = "password is required.";
        return false;
    }
  }


?>