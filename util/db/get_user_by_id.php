<?php
require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/model/util/connect_db.php";
function get_user_by_id($id){
  $conn = connect_db();
      
  $sql = "SELECT * FROM Users WHERE userID=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  return $row;
}

?>