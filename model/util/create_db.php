<?php
require './config.php';
function create_db() {
  try {
    $conn = new PDO("mysql:host=".DB_HOST, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS ".DB_NAME;
    $conn->exec($sql);
  } catch(PDOException $e) { 
  }
}

?>