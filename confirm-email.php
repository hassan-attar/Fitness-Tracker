<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
require('./model/util/connect_db.php');
require("./validation/get_validation_message.php");
require './validation/validate_string.php';
require "./util/mail/send_confirm_mail.php";
$validation_error;

if(!$userId){
  header("Location: index.php");
  exit();
}


if($_SERVER["REQUEST_METHOD"] == "POST" || ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["key"]) && !empty($_GET["key"]))){
  
  if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["key"]) && !empty($_GET["key"])){
    $_POST["emailKey"] = $_GET["key"];
  }
  global $validation_error;
  $isValidKey = validate_str("emailKey", "Code");

  if ($isValidKey) {
    // TODO
    // connect to DB
    $conn = connect_db();

      
    $sql = "SELECT * FROM Users WHERE userID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if(!$row || !password_verify($_POST["emailKey"], $row["authConfirmKey"])){
      $validation_error["general"] = "Code is incorrect.";
    }else if (time() > strtotime($row["authKeyExpiresAt"])) {
      $validation_error["general"] = "Code is Expired! Please request a new one.";
    }else {
      $sql = "UPDATE Users SET authConfirmKey=NULL, authKeyExpiresAt=NULL, isEmailConfirmed=TRUE WHERE userID = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $userId);
      if($stmt->execute()){
        // TODO
        send_confirm_email($userName, $row["email"]);
        header("Location: index.php");
        exit();
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fitness Tracker - Sign up</title>
</head>

<body>
  <header></header>
  <main>
    <form method="POST" action="confirm-email.php">
      <?= get_validation_message("emailKey") ?>
      <label for="emailKey">Enter Confirm Code</label>
      <input type="number" id="emailKey" name="emailKey" min="111111" max="999999" step="1" required>
      <button type="submit">Submit</button>
    </form>
    <p><?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>
    </div>
  </main>
  <footer></footer>
</body>

</html>