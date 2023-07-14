<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/config.php";
require_once "../../config.php";
function connect_db() {
  try {
    $conn = new PDO("mysql:host=".$_ENV["DB_HOST"].";dbname=".$_ENV["DB_NAME"].";", $_ENV["DB_USER"], $_ENV["DB_PASS"]);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connection successful";
    // return $conn;
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}
connect_db();
?>