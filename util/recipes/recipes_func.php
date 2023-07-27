<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/model/util/connect_db.php";
function get_all_recipes() {
  $conn = connect_db();
  $sql = "SELECT r.*, i.ingredientsList 
          FROM Recipes r 
          JOIN (SELECT recipeID, GROUP_CONCAT(ingredientName) AS ingredientsList
          FROM Ingredients 
          GROUP BY recipeID) i ON r.recipeID = i.recipeID";
  
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $recipes = array();
  while($row = $result->fetch_assoc()){
    array_push($recipes, $row);
  }
  return $recipes;
}
function get_recipe_by_id($id) {
  $conn = connect_db();
  $sql = "SELECT r.*, i.ingredientsList 
          FROM Recipes r
          JOIN (SELECT recipeID, GROUP_CONCAT(ingredientName) AS ingredientsList
          FROM Ingredients 
          GROUP BY recipeID) i ON r.recipeID = i.recipeID
          WHERE r.recipeID=?";
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  
  $result = $stmt->get_result();

  $row = $result->fetch_assoc();
  
  return $row;
}

function filter_recipes_by_name($recipes, $filterName) {
  return array_filter($recipes, function($recipe) use ($filterName){
    return str_contains(strtolower($recipe["title"]),strtolower($filterName));
  });
}
function filter_recipes_by_ingredients($recipes, $selectedIngredients, $isHaving) {
  return array_filter($recipes, function($recipe) use ($selectedIngredients, $isHaving){
      $flag = true;
      foreach($selectedIngredients as $ing){
        if($isHaving){
          $flag = $flag && (in_array($ing, explode("," ,$recipe["ingredientsList"]), false));
        }else {
          $flag = $flag && !(in_array($ing, explode("," ,$recipe["ingredientsList"]), false));
        }
      }
      return $flag;
    });
}
function filter_by_calories($recipes, $minCal, $maxCal){
  return array_filter($recipes, function($recipe) use ($minCal, $maxCal){
    return intval($recipe["calories"]) >= $minCal && intval($recipe["calories"]) <= $maxCal;
  });
}
function filter_by_rating($recipes, $minRate){
  return array_filter($recipes, function($recipe) use ($minRate){
    return doubleval($recipe["averageRating"]) >= $minRate;
  });
}
function filter_by_cookTime($recipes, $maxCookTime){
  return array_filter($recipes, function($recipe) use ($maxCookTime){
    return intval($recipe["cookTime"]) <= $maxCookTime;
  });
}

function get_reviews_for_recipe($id) {
  $conn = connect_db();
  $sql = "SELECT 
          r.ratingID, u.firstName, r.rate, c.comment, c.date
          FROM Users u
          LEFT JOIN Ratings r ON u.userID = r.userID
          LEFT JOIN Comments c ON u.userID = c.userID AND r.recipeID = c.recipeID
          WHERE r.recipeID =?";
  
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

function render_recipes($recipes){
  foreach($recipes as $recipe){
    echo '
    <div class="recipe-card">
      <div class="cook-time">
        <ion-icon name="timer-outline"></ion-icon><span>'.$recipe["cookTime"].' Minutes</span>
      </div>
      <div class="calories">
        <ion-icon name="flame-outline"></ion-icon> <span>'.$recipe["calories"].' kcal</span>
      </div>
      <div class="recipe-image">
        <img src="./public/img/'.$recipe["imageURL"].'" alt="'.$recipe["title"].'" />
      </div>
      <div class="content">
        <div>
          <h3 class="title">'.$recipe["title"].'</h3>
          <div class="ingredients">';
          foreach(explode("," ,$recipe["ingredientsList"]) as $ing){
            echo '<span class="ingredient">'.trim($ing).'</span>';
          }
          echo '
          </div>
          <p class="description">
            '.$recipe["description"].'
          </p>
        </div>
    
        <div class="card-footer">
          <span class="rating">
            <ion-icon name="star" class="star"></ion-icon><span>'.$recipe["averageRating"].'</span>
          </span>
          <button class="action" data-recipe-id="'.$recipe["recipeID"].'">Find out more</button>
        </div>
      </div>
    </div>
    ';
  }
}
?>