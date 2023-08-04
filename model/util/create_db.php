<?php
require './config.php';
function create_db() {
  try {
    $conn = new PDO("mysql:host=".$_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS ".$_ENV["DB_NAME"];
    $conn->exec($sql);
  } catch(PDOException $e) { 
    
  }
}

?>