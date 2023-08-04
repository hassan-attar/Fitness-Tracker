<?php
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];

require_once 'vendor/autoload.php';
require_once './component/header.php';
require_once './component/head.php';
require_once './component/footer.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?= render_head("FT - Home");?>
</head>


<body>
  <div class="a-index-wrapper main-page">
    <?= render_header($userName); ?>
    <div class="hero-bg">
      <section class="main-hero">
        <h1>Health Goals,<br> Tracked & Thrived</h1>
      </section>

    </div>
    <main class="main-page">
      <section class="main-cards">
        <div class="main-card">
          <img src="./public/img/recipe-hero.jpg" alt="">
          <div class="main-card-content">
            <h2>
              Recipes
            </h2>
            <p>
              Fuel your body with goodness while savoring the flavors &mdash; because eating healthy doesn't mean
              sacrificing
              taste!
            </p>
            <a href=<?= $_ENV["PUBLIC_URL"].'/recipes.php'?> class="button">
              Find out more

            </a>
          </div>
        </div>
        <div class="main-card">
          <img src="./public/img/todo-hero.jpg" alt="">
          <div class="main-card-content">
            <h2>
              Todo
            </h2>
            <p>
              Enhance your lifestyle with our interactive todo page! Add exercise tasks, embrace physical activity, and
              elevate your well-being for a healthier, happier you!
            </p>
            <a href="#" class="button">
              Find out more

            </a>
          </div>
        </div>
        <div class="main-card">
          <img src="./public/img/fitness-hero.jpg" alt="">
          <div class="main-card-content">
            <h2>
              Fitness Tracker
            </h2>
            <p>
              Unlock the path to success and witness your progress as you strive towards a healthier and more fulfilling
              life.
            </p>
            <a href="#" class="button">
              Coming soon...

            </a>

          </div>
        </div>
      </section>
    </main>
    <?= render_footer(); ?>
  </div>
</body>

</html>