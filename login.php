<?php
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
$validation_error;

require './validation/validate_email.php';
require './validation/validate_password.php';
require('./model/util/connect_db.php');
require('./util/mail/send_mail.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
  global $validation_error;
  $isValidEmail = validate_email();
  $isValidPassword = validate_password();

  if ($isValidEmail && $isValidPassword) {
    // TODO
    // connect to DB
    $conn = connect_db();

      
    $sql = "SELECT * FROM Users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if(!$row || !password_verify($_POST["password"], $row["password"])){
      $validation_error["general"] = "email or password is incorrect.";
    }else {
      session_start();
      $_SESSION["userId"] = $row["userID"];
      $_SESSION["firstName"] = $row["firstName"];
      header("Location: index.php");
      exit();
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
    <form method="POST" action="login.php">
      <p><?php echo isset($validation_error["email"])? $validation_error["email"]: ""?></p>
      <label for="email">Email</label>
      <input type="email" name="email" id="email"
        <?php echo isset($_POST["email"]) ? "value='".$_POST["email"]."'" :"value=''" ?> />
      <p><?php echo isset($validation_error["password"])? $validation_error["password"]: ""?></p>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" />

      <button type="submit">Submit</button>
      <p><?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>
    </form>
    </div>
  </main>
  <footer></footer>
</body>

</html>