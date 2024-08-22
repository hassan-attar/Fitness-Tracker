<?php
// require_once '../../vendor/autoload.php';
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();
require_once __DIR__ . "/../../config.php";
mysqli_report(MYSQLI_REPORT_OFF);
function connect_db() {

  $conn = new mysqli($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"], $_ENV["DB_NAME"]);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
  // $conn = new PDO("mysql:host=".$_ENV["DB_HOST"].";dbname=".$_ENV["DB_NAME"].";", $_ENV["DB_USER"], $_ENV["DB_PASS"]);
  // set the PDO error mode to exception
  // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // return $conn;
  
}
connect_db();