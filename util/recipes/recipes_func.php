<?php
require_once $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/model/util/connect_db.php";
function get_all_recipes() {
  $conn = connect_db();
  $sql = "SELECT r.*, i.ingredientsList FROM Recipes r JOIN (SELECT recipeID, GROUP_CONCAT(ingredientName) AS ingredientsList FROM Ingredients GROUP BY recipeID) i ON r.recipeID = i.recipeID";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  
  $result = $stmt->get_result();
  echo '<div class="recipes-container">';
  while($row = $result->fetch_assoc()){
    echo '
    <div class="recipe-card">
      <div class="cook-time">
        <ion-icon name="timer-outline"></ion-icon><span>'.$row["cookTime"].' Minutes</span>
      </div>
      <div class="calories">
        <ion-icon name="flame-outline"></ion-icon> <span>'.$row["calories"].' kcal</span>
      </div>
      <div class="recipe-image">
        <img src="./public/img/'.$row["imageURL"].'" alt="'.$row["title"].'" />
      </div>
      <div class="content">
        <div>
          <h3 class="title">'.$row["title"].'</h3>
          <div class="ingredients">';
          foreach(explode("," ,$row["ingredientsList"]) as $ing){
            echo '<span class="ingredient">'.trim($ing).'</span>';
          }
          echo '
          </div>
          <p class="description">
            '.$row["description"].'
          </p>
        </div>
    
        <div class="card-footer">
          <span class="rating">
            <ion-icon name="star" class="star"></ion-icon><span>'.$row["averageRating"].'</span>
          </span>
          <button class="action">Find out more</button>
        </div>
      </div>
    </div>
    ';
  }
  echo '</div>';
}
?>