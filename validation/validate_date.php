<?php
  $validation_error;
  function validate_birthday() {
    global $validation_error;
    $dateString = "";
    if(isset($_POST["birthday"])){
      $dateString = trim($_POST["birthday"]);
    } else {
      $validation_error["birthday"] = "Birthday is required!";
      return false;
    }
    
    $sanitizedDate = filter_var($dateString, FILTER_SANITIZE_STRING);
    $dateTime = DateTime::createFromFormat('Y-m-d', $sanitizedDate);
    
    if ($dateTime instanceof DateTime) {
      $now = new DateTime();
      $minDate = $now->sub(new DateInterval('P14Y'));
      if ($dateTime < $minDate) {
        $validation_error["birthday"] = "";
        return true;
      } else {
        $validation_error["birthday"] = "You must have at least 14 years old.";
        return false;
      }
    } else {
        $validation_error["birthday"] = "Enter a valid birthday.";
        return false;
    }
  }
?>