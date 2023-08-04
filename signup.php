<?php
date_default_timezone_set('America/Vancouver');
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
$userEmail = $_SESSION["email"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$validation_error;
$global_values;
require './validation/validate_date.php';
require './validation/validate_gender.php';
require './validation/validate_email.php';
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
  $isValidName = validate_str("firstName", "first name");
  $isValidLast = validate_str("lastName", "last name");
  $isValidBirth = validate_birthday();
  $isValidEmail = validate_email();
  $isValidGender = validate_gender();
  $isValidPassword = validate_password();
  $isValidPassConf = isset($_POST["passwordConfirm"]) && $_POST["passwordConfirm"] == $_POST["password"]? true: false;
  if(!$isValidPassConf){
    $validation_error["passwordConfirm"] = "passwords does not match!";
  }
  if ($isValidName &&
  $isValidLast &&
  $isValidBirth &&
  $isValidEmail &&
  $isValidGender &&
  $isValidPassword &&
  $isValidPassConf) {
    // TODO
    // connect to DB
    $conn = connect_db();

    $hashedPass = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO USERS (firstName, lastName, email, birthday, gender, password )
    VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $_POST["firstName"],$_POST["lastName"],$_POST["email"],$_POST["birthday"],$_POST["gender"], $hashedPass);
    
    if ($stmt->execute()) {
      // Insertion was successful
 
      
      $sql = "SELECT * FROM Users WHERE email=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $_POST["email"]);
      $stmt->execute();
      
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $firstName = $row["firstName"];
      
      
      session_start();
      $_SESSION["userId"] = $row["userID"];
      $_SESSION["firstName"] = $row["firstName"];
      $_SESSION["email"] = $row["email"];
      $sql = "UPDATE Users SET authConfirmKey=?, authKeyExpiresAt=? WHERE userID =?";
      $emailConfirmKey = (string)rand(111111,999999);
      $keyHashed = password_hash($emailConfirmKey, PASSWORD_DEFAULT);
      $currentDateTime = new DateTime();
      $currentDateTime->add(new DateInterval('PT15M'));
      $expiresIn = $currentDateTime->format('Y-m-d H:i:s');
      
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssi", $keyHashed ,$expiresIn, $row["userID"]);
      $stmt->execute();

      $publicURL = $_ENV["PUBLIC_URL"];
      send_welcome_email($firstName, $row["email"], $publicURL."/confirm-email.php?key=".$emailConfirmKey, $emailConfirmKey);
      $_SESSION["auth-code-expires-at"] = $expiresIn;
      header("Location: confirm-email.php");
      exit();
      
    } else {
        if($conn->errno == 1062){
          $validation_error["general"] = "email already exists! please use another email or login.";
        } else {
          $validation_error["general"] = "Something went wrong! Please try again later.";
        }
    }
    

    

    // log user in

    // redirect to home page
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?= render_head("FT - Sign up"); ?>
</head>

<body>
  <div class="a-index-wrapper">
    <?= render_header($userName); ?>
    <main>
      <form method="POST" action="signup.php" class="sign-up">
        <h2>Sign up</h2>
        <div>
          <label for="firstName">First Name</label>
          <input type="text" name="firstName" id="firstName" <?= remember_input_value("firstName")?> />
          <?= get_validation_message("firstName") ?>

          <label for="lastName">Last Name</label>
          <input type="text" name="lastName" id="lastName" <?= remember_input_value("lastName")?> />
          <?= get_validation_message("lastName") ?>
          <label for="birthday">Birth Date</label>
          <input type="date" name="birthday" id="birthday" <?= remember_input_value("birthday")?> />
          <?= get_validation_message("birthday") ?>
          <label for="gender">Gender</label>
          <select name="gender" id="gender">
            <option value="F" <?= isset($_POST["gender"]) && $_POST["gender"]=="F" ? "selected": ""; ?>>Female
            </option>
            <option value="M" <?= isset($_POST["gender"]) && $_POST["gender"]=="M"? "selected": ""; ?>>Male</option>
            <option value="O" <?= isset($_POST["gender"]) && $_POST["gender"]=="O"? "selected": ""; ?>>Others
            </option>
          </select>
          <?= get_validation_message("gender") ?>
        </div>
        <div>
          <label for="email">Email</label>
          <input type="email" name="email" id="email" <?= remember_input_value("email")?> />
          <?= get_validation_message("email") ?>



          <label for="password">Password</label>
          <input type="password" name="password" id="password" />
          <?= get_validation_message("password") ?>
          <label for="passwordConfirm">Confirm Password</label>
          <input type="password" name="passwordConfirm" id="passwordConfirm" />
          <?= get_validation_message("passwordConfirm") ?>

        </div>
        <button type="submit">Submit</button>
        <p><?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>


      </form>

    </main>
    <?= render_footer(); ?>
  </div>
</body>

</html>