<?php
function remember_input_value($fieldName){
  if(isset($_POST[$fieldName])){
    echo "value=\"".$_POST[$fieldName]."\""; // keep entered value
  } else {
    echo "value=\"\""; // empty
  }
}
?>