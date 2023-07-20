<?php
function get_validation_message($fieldName){
  global $validation_error;
  if(isset($validation_error[$fieldName] ) && !empty($validation_error[$fieldName])){
    echo '<p>
      '.$validation_error[$fieldName].'
    </p>';
  }
}
?>