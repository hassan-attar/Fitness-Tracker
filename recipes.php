 <?php 
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
require_once 'vendor/autoload.php';
require_once './util/recipes/recipes_func.php';
require_once './util/review/reviews_func.php';
require_once './component/review_form.php';
require_once './component/header.php';
require_once './component/head.php';
require_once './component/footer.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load(); 
function get_filtered_recipes(){
  $recipes = get_all_recipes();
  if(isset($_GET["search"]) && !empty($_GET["search"])){
    $recipes = filter_recipes_by_name($recipes,$_GET["search"]);
  }

  if(isset($_GET["ingredients"]) && !empty($_GET["ingredients"])){
    $ingredientList = explode(",", $_GET["ingredients"]);
    $ingToHave = array();
    $ingNotToHave = array();
    foreach($ingredientList as $ing){
      if(empty($ing)){
        continue;
      }
      if(str_starts_with($ing, "-")){
        array_push($ingNotToHave, substr($ing, strpos($ing, "-") + 1));
      } else if (str_starts_with($ing, "+")){
        array_push($ingToHave, substr($ing, strpos($ing, "+") + 1));
      } else {
        array_push($ingToHave, $ing);
      }
    }
    if(count($ingToHave) > 0){
      $recipes = filter_recipes_by_ingredients($recipes, $ingToHave, true);
    }
    if(count($ingNotToHave) > 0){
      $recipes = filter_recipes_by_ingredients($recipes, $ingNotToHave, false);
    }
  }
  
  if(isset($_GET["maxCal"]) && !empty($_GET["maxCal"]) || isset($_GET["minCal"]) && !empty($_GET["minCal"])){

    $minCal = empty($_GET["minCal"])? 0 : $_GET["minCal"];
    $maxCal =  empty($_GET["maxCal"])? 0 : $_GET["maxCal"];
    $recipes = filter_recipes_by_calories($recipes,$minCal, $maxCal);
  }

  if(isset($_GET["minRating"]) && !empty($_GET["minRating"])){
    $recipes = filter_recipes_by_rating($recipes,$_GET["minRating"]);
  }
  if(isset($_GET["maxCookTime"]) && !empty($_GET["maxCookTime"])){
    $recipes = filter_recipes_by_cookTime($recipes,$_GET["maxCookTime"]);
  }
  
  return $recipes;
}

?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
   <?= render_head("FT - Recipes");?>
 </head>



 <body>


   <!-- Wrapper -->
   <div class="a-index-wrapper">
     <?= render_header($userName); ?>

     <main>
       <div id="search-bar">
         <form method="GET">

           <label for="search" hidden>
             Search recipes
           </label>
           <input type="text" name="search" id="search" placeholder="Recipe Search"
             <?= isset($_GET["search"]) && !empty($_GET["search"])? 'value="'.$_GET["search"].'"': "" ?>>
           <button type="submit">
             <ion-icon name="search-outline"></ion-icon>
           </button>

         </form>
       </div>
       <div class="recipes-container">
         <?php render_recipes(get_filtered_recipes()); ?>
       </div>
       <aside class="recipe-filter">
         <h3>Filter Results</h3>
         <form method="GET">
           <div class="by-ing">
             <h4>Filter by Ingredients:</h4>
             <div class="ing-to-have">
               <label for="ing-to-have">To Have:</label>
               <input type="text" list="ingredientList" placeholder="Select and press Enter" id="ing-to-have">
               <ul class="selectedList">
                 <?= isset($_GET["ingredients"]) && !empty($_GET["ingredients"]) ? print_ingredients_list_item($_GET["ingredients"], true): "" ?>
               </ul>

               <label for="ing-not-to-have">Not to Have:</label>
               <input type="text" list="ingredientList" placeholder="Select and press Enter" id="ing-not-to-have">
               <ul class="unselectedList">
                 <?= isset($_GET["ingredients"]) && !empty($_GET["ingredients"]) ? print_ingredients_list_item($_GET["ingredients"], false): "" ?>
               </ul>
               <?php render_ingredients_datalist(get_all_unique_ingredients()) ?>
               <input type="hidden" id="ingredients" name="ingredients"
                 <?= isset($_GET["ingredients"]) && !empty($_GET["ingredients"]) ?'value="'.$_GET["ingredients"].'"': 'value=""' ?>>
             </div>
             <h4>Filter by Calorie:</h4>
             <div class="by-cal">
               <label for="minCal">from</label><label for="maxCal">to</label>
               <input type="range" step="1" name="minCal" id="minCal" min="0" max="1000"
                 <?= isset($_GET["minCal"]) && !empty($_GET["minCal"])? 'value="'.$_GET["minCal"].'"': 'value="0"' ?>>
               <input type="range" step="1" name="maxCal" id="maxCal" min="0" max="1000"
                 <?= isset($_GET["maxCal"]) && !empty($_GET["maxCal"])? 'value="'.$_GET["maxCal"].'"': 'value="1000"' ?>>
               <span class="from"><?= isset($_GET["minCal"]) && !empty($_GET["minCal"])? $_GET["minCal"]: '0' ?>
                 Cal</span><span
                 class="to"><?= isset($_GET["maxCal"]) && !empty($_GET["maxCal"])? $_GET["maxCal"]: '1000' ?> Cal</span>
             </div>
           </div>
           <h4>Filter by Rating:</h4>
           <div class="by-rating"
             <?= isset($_GET["minRating"]) && !empty($_GET["minRating"])? 'data-previous-rate="'.$_GET["minRating"].'"': '' ?>>
             <label for="star-1f">
               <ion-icon name="star-outline" class="star" data-star-val=1></ion-icon>
             </label>
             <label for="star-2f">
               <ion-icon name="star-outline" class="star" data-star-val=2></ion-icon>
             </label>
             <label for="star-3f">
               <ion-icon name="star-outline" class="star" data-star-val=3></ion-icon>
             </label>
             <label for="star-4f">
               <ion-icon name="star-outline" class="star" data-star-val=4></ion-icon>
             </label>
             <label for="star-5f">
               <ion-icon name="star-outline" class="star" data-star-val=5></ion-icon>
             </label>
             <input type="radio" name="minRating" id="star-1f" value="1"
               <?= isset($_GET["minRating"]) && !empty($_GET["minRating"])? (intval($_GET["minRating"]) == 1 ? "checked": ""): '' ?> />
             <input type="radio" name="minRating" id="star-2f" value="2"
               <?= isset($_GET["minRating"]) && !empty($_GET["minRating"])? (intval($_GET["minRating"]) == 2 ? "checked": ""): '' ?> />
             <input type="radio" name="minRating" id="star-3f" value="3"
               <?= isset($_GET["minRating"]) && !empty($_GET["minRating"])? (intval($_GET["minRating"]) == 3 ? "checked": ""): '' ?> />
             <input type="radio" name="minRating" id="star-4f" value="4"
               <?= isset($_GET["minRating"]) && !empty($_GET["minRating"])? (intval($_GET["minRating"]) == 4 ? "checked": ""): '' ?> />
             <input type="radio" name="minRating" id="star-5f" value="5"
               <?= isset($_GET["minRating"]) && !empty($_GET["minRating"])? (intval($_GET["minRating"]) == 5 ? "checked": ""): '' ?> />
           </div>
           <h4>Filter by Cook Time:</h4>
           <div class="by-cook-time">
             <label for="maxCookTime">at most:
               <span><?= isset($_GET["maxCookTime"]) && !empty($_GET["maxCookTime"])? $_GET["maxCookTime"]: '90' ?>
                 Minutes</span></label>
             <input type="range" step="1" name="maxCookTime" id="maxCookTime" min="0" max="90"
               <?= isset($_GET["maxCookTime"]) && !empty($_GET["maxCookTime"])? 'value="'.$_GET["maxCookTime"].'"': 'value="90"' ?>>
           </div>
           <button type="submit">Submit</button>
           <button type="reset">Reset</button>
         </form>
       </aside>
     </main>
     <?= render_footer(); ?>
   </div>
   <!-- MODAL -->
   <div id="overlay"></div>
   <div id="modal">
     <div class="header">
       <button class="close-modal">
         <ion-icon name="close-outline" class="close-icon"></ion-icon>
       </button>
     </div>
     <div class="main">
       <div class="reviews-content">
         <?= $userId ? review_form() : '<p class="alert-message">To share your thoughts, please login!</p><a class="login" href="'.$_ENV["PUBLIC_URL"].'/login.php">Login</a>' ?>
         <h4>Reviews</h4>
         <div class="user-reviews">
         </div>
       </div>
     </div>
   </div>
   <!-- MODAL END -->
 </body>

 </html>