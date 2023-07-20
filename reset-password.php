<?php
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$validation_error;
require './validation/validate_password.php';
require './validation/validate_string.php';
require('./model/util/connect_db.php');
require "./util/mail/send_welcome_mail.php";
require("./validation/get_validation_message.php");
require('./util/form/remember_input_value.php');
require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/config.php";
if($userId){

}


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["key"]) && !empty($_POST["key"] && isset($_POST["selector"]) && !empty($_POST["selector"]))){
  global $validation_error;
  $isValidPassword = validate_password();
  $isValidPassConf = isset($_POST["passwordConfirm"]) && $_POST["passwordConfirm"] == $_POST["password"]? true: false;
  if(!$isValidPassConf){
    $validation_error["passwordConfirm"] = "passwords does not match!";
  }
  if ($isValidPassword && $isValidPassConf && validate_str("key", "") && validate_str("selector", "")) {
    // TODO
    // connect to DB
    $conn = connect_db();

    $selector = $_POST["selector"];
    $currentDateTime = new DateTime();
    $now = $currentDateTime->format('Y-m-d H:i:s');
    $sql = "SELECT * FROM Users WHERE forgetPasswordSelector=? AND forgetTokenExpiresAt>?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $selector, $now);
    $stmt->execute();
    

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if(!$row){
      $validation_error["general"] = "Invalid or expired token! please request your token again.";
    } else if( password_verify($_POST["key"], $row["forgetPasswordToken"])){
      $newPass = password_hash($_POST["password"], PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE Users SET forgetTokenExpiresAt=NULL, forgetPasswordToken=NULL, forgetPasswordSelector=NULL, password=?, passwordChangedAt=? WHERE userID = ?");
      $stmt->bind_param("ssi", $newPass, $now, $row["userID"]);
      if($stmt->execute()){
        session_start();
        $_SESSION["userId"] = $row["userID"];
        $_SESSION["firstName"] = $row["firstName"];
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
    <form method="POST" <?= "action=reset-password.php?key=".$_GET["key"]."&selector=".$_GET["selector"] ?>>
      <label for="password">Password</label>
      <input type="hidden" name="key"
        <?= (isset($_GET["key"]) && !empty($_GET["key"]))? 'value="'.$_GET["key"].'"' : "" ?> />
      <input type="hidden" name="selector"
        <?= (isset($_GET["selector"]) && !empty($_GET["selector"]))? 'value="'.$_GET["selector"].'"' : "" ?> />
      <input type="password" name="password" id="password" />
      <?= get_validation_message("password") ?>
      <label for="passwordConfirm">Confirm Password</label>
      <input type="password" name="passwordConfirm" id="passwordConfirm" />
      <?= get_validation_message("passwordConfirm") ?>
      <button type="submit">Submit</button>
      <p><?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>
    </form>
    </div>
  </main>
  <footer></footer>
</body>

</html>