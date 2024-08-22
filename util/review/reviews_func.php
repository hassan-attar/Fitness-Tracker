<?php

require_once __DIR__ ."/../../model/util/connect_db.php";
function create_review($id, $comment, $rate) {
  global $userId;
  $conn = connect_db();
  $sql = "INSERT INTO Comments (userID, recipeID, `comment`, rate)
          VALUES (?,?,?,?);";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("iisi", $userId, $id,$comment, $rate);
  
  if($stmt->execute()){
    return true;
  }else {
    $error = mysqli_error($conn);
    return false;
  }
}
function get_reviews_for_recipe($id) {
  $conn = connect_db();
  $sql = "SELECT 
          c.*, u.firstName
          FROM Comments c
          LEFT JOIN Users u ON u.userID = c.userID
          WHERE c.recipeID =?";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $reviews = array();
  while($row = $result->fetch_assoc()){
    array_push($reviews, $row);
  }
  return $reviews;
}

function update_averageRating($recipeId){
  $conn = connect_db();
  $sql = "SELECT 
          recipeID, AVG(rate) AS averageRating
          FROM Comments
          WHERE recipeID =?
          GROUP BY recipeID";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $recipeId);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $resultRow = $result->fetch_assoc();

  $sql = "UPDATE Recipes 
          SET averageRating=?
          WHERE recipeID =?";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("di",$resultRow["averageRating"], $recipeId);
}