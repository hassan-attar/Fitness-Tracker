<?php
date_default_timezone_set('America/Vancouver');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
$userEmail = $_SESSION["email"];
require('./model/util/connect_db.php');
require("./validation/get_validation_message.php");
require './validation/validate_string.php';
require "./util/mail/send_confirm_email.php";
require "./util/mail/send_welcome_mail.php";
require_once './component/header.php';
require_once './component/head.php';
require_once './component/footer.php';
$validation_error;
$global_values;
$validation_error["general"]= "";

if(!$userId){
  header("Location: index.php");
  exit();
}

//TODO

if($_SERVER["REQUEST_METHOD"] == "POST" || ($_SERVER["REQUEST_METHOD"] == "GET")){
  
  if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET["key"]) && !empty($_GET["key"])){
      $_POST["emailKey"] = $_GET["key"];
    }else {
      $_POST["emailKey"] = "1"; // Dumb value
    }
  }
  global $validation_error;
  $isValidKey = validate_str("emailKey", "Code");

  var_dump($isValidKey);
  if ($isValidKey) {
    $conn = connect_db();

    // TODO
    if(!isset($_SESSION["auth-code-expires-at"]) || empty($_SESSION["auth-code-expires-at"]) || time() > strtotime($_SESSION["auth-code-expires-at"])){
      $validation_error["general"] = "Code has Expired! We have sent you a new email with the code.";
      $sql = "UPDATE Users SET authConfirmKey=?, authKeyExpiresAt=? WHERE userID =?";
      $emailConfirmKey = (string)rand(111111,999999);
      $keyHashed = password_hash($emailConfirmKey, PASSWORD_DEFAULT);
      $currentDateTime = new DateTime();
      $currentDateTime->add(new DateInterval('PT15M'));
      $expiresIn = $currentDateTime->format('Y-m-d H:i:s');
      $_SESSION["auth-code-expires-at"] = $expiresIn;
      
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssi", $keyHashed ,$expiresIn, $userId);
      $stmt->execute();

      $publicURL = $_ENV["PUBLIC_URL"];
      send_welcome_email($userName, $userEmail, $publicURL."/confirm-email.php?key=".$emailConfirmKey, $emailConfirmKey);
    }else {
      $sql = "SELECT * FROM Users WHERE userID=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $userId);
      $stmt->execute();
      
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      if(boolval($row["isEmailConfirmed"])){
        $validation_error["general"] = "You are all set!";
        header("Location: index.php");
        exit();
      }else if(!$row || !password_verify($_POST["emailKey"], $row["authConfirmKey"])){
        $validation_error["general"] = "Code is incorrect.";
      }else {
        $sql = "UPDATE Users SET authConfirmKey=NULL, authKeyExpiresAt=NULL, isEmailConfirmed=TRUE WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $userId);
        if($stmt->execute()){
          // TODO
          $validation_error["general"] = "";
          send_confirm_email($userName, $row["email"]);
          header("Location: index.php");
          exit();
        }
      }
    }
    
    
    }
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?= render_head("FT - Confirm Email"); ?>
  <script>
  $(document).ready(function() {
    const expiresAt = new Date(<?='"'.$_SESSION["auth-code-expires-at"].'"'?>).getTime();
    const interval = setInterval(() => {
      const now = Date.now();
      const distance = expiresAt - now;

      if (distance < 0) {
        document.getElementById("timer").parent().appendChild =
          `<p>Code has Expired, Please request a new code.<p>`;
        clearInterval(interval);
        return;
      }
      const mins = Math.trunc(distance / (1000 * 60));
      const secs = Math.trunc((distance - (mins * 60 * 1000)) / 1000);
      document.getElementById("timer").innerHTML =
        `<span>${mins.toString().padStart(2, "0")} : ${secs.toString().padStart(2, "0")}</span>`;
    }, 1000)
  })
  </script>
</head>

<body>
  <div class="a-index-wrapper">
    <?= render_header($userName); ?>
    <main>
      <form method="POST" action="confirm-email.php" class="confirm-email">
        <h2>Confirm Email</h2>
        <?= get_validation_message("emailKey") ?>
        <label for="emailKey">Enter Confirmation Code</label>
        <input type="number" id="emailKey" name="emailKey" maxlength="6" minlength="6" step="1"
          <?=(isset($_GET["key"]) && !empty($_GET["key"]))? 'value="'.$_GET["key"].'"' : ""  ?>>
        <p><?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>
        <?= get_validation_message("emailKey") ?>

        <button type="submit">
          <?=time() > strtotime($_SESSION["auth-code-expires-at"])? "Resend" : "Submit" ?>
        </button>

        <a href="confirm-email.php"
          <?=time() > strtotime($_SESSION["auth-code-expires-at"])? 'href="confirm-email.php" class="resend"' : 'href="#" class="resend disabled"'  ?>>Resend</a>
        <p id="timer"></p>
      </form>



    </main>
    <?= render_footer(); ?>
  </div>
</body>

</html>