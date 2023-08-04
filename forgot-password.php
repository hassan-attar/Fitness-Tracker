<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
$validation_error;

require './validation/validate_email.php';
require './validation/validate_password.php';
require('./model/util/connect_db.php');
require "./util/mail/send_forgot_password_email.php";
require_once './component/header.php';
require_once './component/head.php';
require_once './component/footer.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
  global $validation_error;
  $isValidEmail = validate_email();

  if ($isValidEmail) {
    // TODO
    // connect to DB
    $conn = connect_db();

      
    $sql = "SELECT * FROM Users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if($row){
      $sql = "UPDATE Users SET forgetPasswordToken=?, forgetTokenExpiresAt=?, forgetPasswordSelector=? WHERE userID =?";
      $forgetPasswordToken = (string)bin2hex(random_bytes(32));
      $selector =substr(bin2hex(random_bytes(16)),0, 16);
      $tokenHashed = password_hash($forgetPasswordToken, PASSWORD_DEFAULT);
      $currentDateTime = new DateTime();
      $currentDateTime->add(new DateInterval('PT10M'));
      $expiresIn = $currentDateTime->format('Y-m-d H:i:s');
      var_dump($selector);
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssi", $tokenHashed ,$expiresIn, $selector , $row["userID"]);
      $stmt->execute();

      $publicURL = $_ENV["PUBLIC_URL"];
      send_forgot_password_email($row["email"], $publicURL."/reset-password.php?key=".$forgetPasswordToken."&selector=".$selector);
    }
    $validation_error["general"] = "If you had an account with us, you will get the reset email shortly!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?= render_head("FT - Forgot Password"); ?>
</head>

<body>
  <div class="a-index-wrapper">
    <?= render_header($userName); ?>
    <main>

      <form method="POST" action="forgot-password.php" class="forgot-password">
        <h2>Forgot Password</h2>
        <label for="email">Email</label>
        <input type="email" name="email" id="email"
          <?php echo isset($_POST["email"]) ? "value='".$_POST["email"]."'" :"value=''" ?>
          <?php echo isset($validation_error["email"]) && !empty($validation_error["email"])? 'class="invalid"': ""?> />
        <p>
          <?php echo isset($validation_error["email"])? $validation_error["email"]: ""?></p>
        <button type="submit">Submit</button>
        <p
          <?php echo isset($validation_error["general"]) && !empty($validation_error["general"])? 'class="invalid"': ""?>>
          <?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>
      </form>
    </main>
    <?= render_footer(); ?>
  </div>
</body>

</html>