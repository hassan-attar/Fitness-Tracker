<?php
date_default_timezone_set('America/Vancouver');
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
require_once './component/header.php';
require_once './component/head.php';
require_once './component/footer.php';



if($_SERVER["REQUEST_METHOD"] == "POST"){
  global $validation_error;
  if(isset($_POST["key"]) && !empty($_POST["key"] && isset($_POST["selector"]) && !empty($_POST["selector"]))){

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
          $_SESSION["email"] = $row["email"];
          header("Location: index.php");
          exit();
        }
      }
    }
  }else {
    $validation_error["general"] = "Please request a forgot password token!";
  }
  
  
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?= render_head("FT - Reset Password"); ?>
</head>

<body>
  <div class="a-index-wrapper">
    <?= render_header($userName); ?>
    <main>
      <form method="POST" class="reset-password"
        <?= "action=reset-password.php?key=".$_GET["key"]."&selector=".$_GET["selector"] ?>>
        <h2>Reset Password</h2>
        <label for="password">New Password</label>
        <input type="hidden" name="key"
          <?= (isset($_GET["key"]) && !empty($_GET["key"]))? 'value="'.$_GET["key"].'"' : "" ?> />
        <input type="hidden" name="selector"
          <?= (isset($_GET["selector"]) && !empty($_GET["selector"]))? 'value="'.$_GET["selector"].'"' : "" ?> />
        <input type="password" name="password" id="password"
          <?=isset($validation_error["password"] ) && !empty($validation_error["password"]) ? 'class="invalid"': "" ?> />
        <?= get_validation_message("password") ?>
        <label for="passwordConfirm">Confirm Password</label>
        <input type="password" name="passwordConfirm" id="passwordConfirm"
          <?=isset($validation_error["passwordConfirm"] ) && !empty($validation_error["passwordConfirm"]) ? 'class="invalid"': "" ?> />
        <?= get_validation_message("passwordConfirm") ?>
        <p><?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>
        <button type="submit">Submit</button>

      </form>

    </main>
    <?= render_footer(); ?>
</body>
</div>

</html>