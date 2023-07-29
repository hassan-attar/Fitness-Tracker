<?php
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 

require_once $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/util/review/reviews_func.php";
if($_SERVER["REQUEST_METHOD"] == "GET"){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $recipeId = (int)$_GET['id'];
    $reviews = get_reviews_for_recipe($recipeId);
    header('Content-Type: application/json');
    echo json_encode($reviews);
  }else {
    echo json_encode(["message" => "You must provide recipe id as query parameter (e.g. ?id=1)"]);
  }
}  
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $recipeId = (int)$_GET['id'];
    $comment;
    $rate;
    if(isset($_POST["comment"]) && !empty($_POST["comment"])){
      $comment = $_POST["comment"];
    }
    if(isset($_POST["rate"]) && !empty($_POST["rate"])){
      $rate = $_POST["rate"];
    }
    if($comment && $rate){
      if(create_review($recipeId, $comment, $rate)){
        $recipeId = (int)$_GET['id'];
        $reviews = get_reviews_for_recipe($recipeId);
        header("HTTP/1.1 201 Created");
        header('Content-Type: application/json');
        echo json_encode($reviews);
        update_averageRating($recipeId);
      } else {
        header("HTTP/1.1 400 Bad Request");
        header('Content-Type: application/json');
        echo json_encode(["status" => "fail" , "message" => "You can write only one review per recipe!"]);
      }
    } else {
      header("HTTP/1.1 400 Bad Request");
      header('Content-Type: application/json');
      echo json_encode(["status" => "fail" , "message" => "comment and rate is required!"]);
    }
  }else {
    header("HTTP/1.1 400 Bad Request");
    header('Content-Type: application/json');
    echo json_encode(["message" => "You must provide recipe id as query parameter (e.g. ?id=1)"]);
  }
}


?>