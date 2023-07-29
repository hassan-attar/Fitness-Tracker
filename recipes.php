 <?php 
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
require_once 'vendor/autoload.php';
require_once './util/recipes/recipes_func.php';
require_once './util/review/reviews_func.php';
require_once './component/review_form.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load(); 
function get_filtered_recipes(){
  $recipes = get_all_recipes();
  if(isset($_GET["search"]) && !empty($_GET["search"])){
    $recipes = filter_recipes_by_name($recipes,$_GET["search"]);
  }

  if(isset($_GET["ingredient"]) && !empty($_GET["ingredient"])){
    $ingredientList = explode(",", $_GET["ingredient"]);
    $ingToHave = array();
    $ingNotToHave = array();
    foreach($ingredientList as $ing){
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
  
  if(isset($_GET["maxCal"]) && !empty($_GET["MaxCal"]) || isset($_GET["minCal"]) && !empty($_GET["minCal"])){
    $minCal = $_GET["minCal"] || 0;
    $maxCal = $_GET["maxCal"] || 2000;
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
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>My Fitness Tracker</title>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
   <script src="script.js" defer></script>
   <link rel="stylesheet" href="style.css" />
 </head>

 <body>
   <header>
     <!-- TODO Nav -->
     <!-- TODO brand -->
     <div class="search-bar">
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
   </header>
   <main>
     <div class="recipes-container">
       <?php render_recipes(get_filtered_recipes()); ?>
     </div>
     <aside class="recipe-filter">
       <h3>Filter Results</h3>
       <form>
         <div class="by-ing">
           <h4>Filter by Ingredients:</h4>
           <div class="ing-to-have">
             <h5>To Have:</h5>
             <label for="+ing1">ing1</label>
             <input type="checkbox" name="+ing1" id="+ing1">
           </div>
           <div class="ing-not-to-have">
             <h5>Not to Have:</h5>
             <label for="-ing1">ing1</label>
             <input type="checkbox" name="-ing1" id="-ing1">
           </div>
         </div>
         <div class="by-cal">
           <label for="minCal">Minimum Calorie</label>
           <input type="range" step="1" name="minCal" id="minCal" min="0" max="2000">
           <label for="maxCal">Maximum Calorie</label>
           <input type="range" step="1" name="maxCal" id="maxCal" min="0" max="2000">
         </div>
         <div class="by-rate">
           <label for="minRating">Rating more than:</label>
           <input type="range" step="0.1" name="minRating" id="minRating" min="0.0" max="5.0">
         </div>
         <div class="by-cook-time">
           <label for="maxCookTime">Cook time less than:</label>
           <input type="range" step="1" name="maxCookTime" id="maxCookTime" min="0" max="180">
         </div>
       </form>
     </aside>
   </main>
   <footer>
     <!-- TODO footer -->
   </footer>
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
         <?= $userId ? review_form() : '<p class="alert-message">To share your thoughts, please login!</p><a class="login" href="/login.php">Login</a>' ?>
         <h4>Reviews</h4>
         <div class="user-reviews">
         </div>
       </div>
     </div>
   </div>
   <!-- MODAL END -->
 </body>

 </html>