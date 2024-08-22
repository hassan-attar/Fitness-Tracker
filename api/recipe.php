<?php
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
require_once __DIR__ . "/../util/recipes/recipes_func.php";
if($_SERVER["REQUEST_METHOD"] == "GET"){
  if(isset($_GET['id']) && !empty($_GET['id'])){
    $recipeId = (int)$_GET['id'];
    $recipe = get_recipe_by_id($recipeId);
    header("HTTP/1.1 200 OK");
    header('Content-Type: application/json');
    echo json_encode($recipe);
  }else {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["status"=> "fail", "message" => "recipe id must be provided."]);
  }
}
?>