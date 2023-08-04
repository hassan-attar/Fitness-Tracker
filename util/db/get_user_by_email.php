<?php
require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/model/util/connect_db.php";
function get_user_by_email($email){
  $conn = connect_db();
      
  $sql = "SELECT * FROM Users WHERE email=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  return $row;
}

?>