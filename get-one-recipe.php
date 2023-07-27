<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
require_once './util/recipes/recipes_func.php';
require './validation/validate_string.php';
if(isset($_GET['id']) && !empty($_GET['id'])){
  $recipeId = (int)$_GET['id'];
  $recipe = get_recipe_by_id($recipeId);
  header('Content-Type: application/json');
  echo json_encode($recipe);
}else {
  echo json_encode(["message" => "not found"]);
}

?>