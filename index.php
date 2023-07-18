<?php
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Fitness Tracker</title>
</head>

<body>
  <h1>Hello world</h1>
  <h3> <?php var_dump($_SESSION)?></h3>
  <h2>Welcome <?php echo $userName?></h2>
  <a href="logout.php">Logout</a>
  <header>
    <!-- TODO Nav -->
    <!-- TODO brand -->
  </header>
  <main>
    <!-- TODO hero -->
    <!-- TODO cards -->
  </main>
  <footer>
    <!-- TODO footer -->
  </footer>
</body>

</html>