 <?php
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
require_once 'vendor/autoload.php';
require_once './util/recipes/recipes_func.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load(); ?>

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

 <script defer>
$(document).ready(function() {
  const splitToLi = (instruction) => {
    return instruction.split(/\d+\./g).filter((el) => el.length > 0);
  };
  $("#addBtn").on("click", function() {
    let toDoText = $("#formInput").val();
    $("#formInput").val("");
    addItem(toDoText);
  });

  $("#formInput").keypress(function(event) {
    if (event.keyCode == 13) {
      let toDoText = $(this).val();
      $(this).val("");
      addItem(toDoText);
    }
  });

  function addItem(toDoText) {
    $("ul").append(`<li>${toDoText}<span><i class="fas fa-trash"></i></li>`);
  }

  $("body").on("click", "li", function() {
    $(this).toggleClass("done");
  });

  $("body").on("click", ".fa-trash", function() {
    $(this)
      .parent()
      .parent()
      .fadeOut(500, function() {
        $(this).remove();
      });
  });

  function closeModal(e) {
    $("#modal").fadeOut(200);
    $("#overlay").fadeOut(300);
  }

  function openModal(e) {
    $("#modal").fadeIn(300);
    $("#overlay").fadeIn(200);
  }

  // MODAL
  //close
  $("#modal .close-modal").on("click", closeModal);
  $("#overlay").on("click", closeModal);
  //open

  $("#modal .close-modal").on("click", closeModal);
  $("#overlay").on("click", closeModal);
  //open

  $(".recipe-card .action").on("click", function(e) {
    openModal();
    const id = e.target.dataset["recipeId"];
    $.ajax({
      url: "get-one-recipe.php?id=" + id,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      statusCode: {
        200: function(data) {
          const instructions = splitToLi(data.instruction).map(instruction => (`<li>${instruction}</li>`))
            .join("");

          $("#modal .main .instruction-content").remove();
          $("#modal > .header .title").remove();
          $("#modal > .header").prepend(`<h3 class="title">${data.title}</h3>`);
          $("#modal .main").prepend(`<div class="instruction-content">
         <h4>Cooking steps:</h4>
         <ol class="instruction">
           ${instructions}
         </ol>
       </div>`);
        },
      },
    });
    $.ajax({
      url: "get-review-for-recipe.php?id=" + id,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      statusCode: {
        200: function(data) {
          console.log(data);
        }
      }
    });
  });
});
 </script>

 <body>
   <header>
     <!-- TODO Nav -->
     <!-- TODO brand -->
   </header>
   <main>
     <div class="recipes-container">
       <?php render_recipes(get_all_recipes()); ?>
     </div>
   </main>
   <footer>
     <!-- TODO footer -->
   </footer>
   <div id="overlay"></div>
   <div id="modal">
     <div class="header">

       <button class="close-modal">
         <ion-icon name="close-outline" class="close-icon"></ion-icon>
       </button>
     </div>

     <div class="main">


       <div class="reviews-content">
         <?= $userId ? '<form class="review-form">
          <label for="review">Share your thoughts:</label>
          <textarea name="review" id="review" class="review-text" placeholder="Tell us about the taste!"></textarea>

          <button type="submit">Submit</button>
        </form>' : "" ?>
         <h4>Reviews</h4>
         <div class="user-reviews">
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
           <div class="review">
             <div class="header">
               <h5 class="user-name">Nesa</h5>
               <span class="date">2 days ago</span>
             </div>
             <div class="comment">
               This is like no food, that's my everyday food!
             </div>
             <div class="footer">
               <span>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star" class="star"></ion-icon>
                 <ion-icon name="star-outline" class="star"></ion-icon>
               </span>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </body>

 </html>