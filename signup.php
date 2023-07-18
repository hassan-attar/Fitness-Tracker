<?php
$validation_error;
require './validation/validate_date.php';
require './validation/validate_gender.php';
require './validation/validate_email.php';
require './validation/validate_password.php';
require './validation/validate_string.php';

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
    //TODO
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
    <form method="POST" action="signup.php">
      <p><?php echo $validation_error["firstName"]?></p>
      <label for="firstName">First Name</label>
      <input type="text" name="firstName" id="firstName"
        <?php echo isset($_POST["firstName"]) ? "value='".$_POST["firstName"]."'" : "value=''" ?> />
      <p><?php echo $validation_error["lastName"]?></p>
      <label for="lastName">Last Name</label>
      <input type="text" name="lastName" id="lastName"
        <?php echo isset($_POST["lastName"]) ? "value='".$_POST["lastName"]."'" : "value=''"?> />
      <p><?php echo $validation_error["birthday"]?></p>
      <label for="birthday">Birth Date</label>
      <input type="date" name="birthday" id="birthday"
        <?php echo isset($_POST["birthday"]) ? "value='".$_POST["birthday"]."'" : "value=''" ?> />
      <p><?php echo $validation_error["email"]?></p>
      <label for="email">Email</label>
      <input type="email" name="email" id="email"
        <?php echo isset($_POST["email"]) ? "value='".$_POST["email"]."'" :"value=''" ?> />
      <p><?php echo $validation_error["gender"]?></p>
      <label for="gender">Gender</label>
      <select name="gender" id="gender">
        <option value="F" <?php echo $_POST["gender"]=="F" ? "selected": ""; ?>>Female</option>
        <option value="M" <?php echo $_POST["gender"]=="M"? "selected": ""; ?>>Male</option>
        <option value="O" <?php echo $_POST["gender"]=="O"? "selected": ""; ?>>Others</option>
      </select>
      <p><?php echo $validation_error["password"]?></p>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" />
      <p><?php echo $validation_error["passwordConfirm"]?></p>
      <label for="passwordConfirm">Confirm Password</label>
      <input type="password" name="passwordConfirm" id="passwordConfirm" />
      <button type="submit">Submit</button>
    </form>
    </div>
  </main>
  <footer></footer>
</body>

</html>