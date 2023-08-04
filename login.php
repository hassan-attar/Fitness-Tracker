<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL); 
session_start();
$userName = $_SESSION["firstName"];
$userId = $_SESSION["userId"];
$validation_error;

require_once './validation/validate_email.php';
require_once './validation/validate_password.php';
require_once ('./model/util/connect_db.php');
require_once './component/header.php';
require_once './component/head.php';
require_once './component/footer.php';

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
      $_SESSION["email"] = $row["email"];
      header("Location: index.php");
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?= render_head("FT - login"); ?>


</head>

<body>
  <div class="a-index-wrapper">
    <?= render_header($userName); ?>
    <main>
      <form method="POST" action="login.php" class="login">
        <h2>Login Here</h2>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="You@example.com"
          <?php echo isset($validation_error["email"]) && !empty($validation_error["email"])? 'class="invalid"': ""?>
          <?php echo isset($_POST["email"]) && !empty($_POST["email"]) ? "value='".$_POST["email"]."'" :"value=''" ?> />
        <p><?php echo isset($validation_error["email"]) && !empty($_POST["email"])? $validation_error["email"]: ""?></p>

        <label for="password">Password</label>
        <input type="password" name="password" id="password"
          <?php echo isset($validation_error["password"])  && !empty($validation_error["password"])? 'class="invalid"': ""?>
          placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" />
        <p><?php echo isset($validation_error["password"])? $validation_error["password"]: ""?></p>

        <p>
          <?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>
        <button type="submit">Submit</button>
        <a href=<?= $_ENV["PUBLIC_URL"].'/signup.php'?>>Sign up</a>
        <a href=<?= $_ENV["PUBLIC_URL"].'/forgot-password.php'?>>Forgot password?</a>

      </form>

    </main>
    <?= render_footer(); ?>
  </div>
</body>

</html>