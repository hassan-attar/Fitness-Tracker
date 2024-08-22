<?php
require_once __DIR__ . '/../../model/util/connect_db.php';
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

function get_all_unique_ingredients(){
  $conn = connect_db();
  $sql = "SELECT DISTINCT ingredientName
          FROM Ingredients";
  
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  
  $result = $stmt->get_result();
  $ingredients = array();
  while($row = $result->fetch_assoc()){
    array_push($ingredients, $row["ingredientName"]);
  }
  return $ingredients;
}

function render_ingredients_datalist($ingredients){
  echo '<datalist id="ingredientList">';
  foreach($ingredients as $ing){
  echo    '<option value="'.$ing.'">';
  }
  echo '</datalist>';
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
function filter_recipes_by_calories($recipes, $minCal, $maxCal){
  return array_filter($recipes, function($recipe) use ($minCal, $maxCal){
    return $recipe["calories"] >= intval($minCal) && $recipe["calories"] <= intval($maxCal);
  });
}
function filter_recipes_by_rating($recipes, $minRate){
  return array_filter($recipes, function($recipe) use ($minRate){
    return doubleval($recipe["averageRating"]) >= $minRate;
  });
}
function filter_recipes_by_cookTime($recipes, $maxCookTime){
  return array_filter($recipes, function($recipe) use ($maxCookTime){
    return intval($recipe["cookTime"]) <= $maxCookTime;
  });
}

function print_ingredients_list_item($ingredientsStr ,$selected){
  
  $listItems = "";
  foreach(explode(",", $ingredientsStr) as $value){
    if(empty($value)){
      continue;
    }
    if($selected){
      
      if(str_starts_with($value, "+")){
        $listItems = $listItems . '<li>'.substr($value,1).'</li>';
      }
    }else {
      
      if(str_starts_with($value, "-")){
        $listItems = $listItems . '<li>'.substr($value,1).'</li>';
      }
    }
  }

  return $listItems;
  
}


function render_recipes($recipes){
  foreach($recipes as $recipe){
    $averageRating = empty($recipe["averageRating"])? "4.0" : $recipe["averageRating"];
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
            <ion-icon name="star" class="star"></ion-icon><span>'.$averageRating.'</span>
          </span>
          <button class="action" data-recipe-id="'.$recipe["recipeID"].'">Find out more</button>
        </div>
      </div>
    </div>
    ';
  }
}