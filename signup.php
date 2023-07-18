<?php
$validation_error;
require './validation/validate_date.php';
require './validation/validate_gender.php';
require './validation/validate_email.php';
require './validation/validate_password.php';
require './validation/validate_string.php';
require('./model/util/connect_db.php');
require "./util/mail/send_mail.php";

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

    // //check if email is already exist: if yes return error, otherwise proceed

    // if ($row) {
    //   // The email exists in the database
    //   $validation_error["general"] = "email already exists! please use another email or login.";
    // } else {
      // create the new user
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
      send_email($_POST["email"], "Welcome to Fitness Tracker", "Dear $firstName,\n\nWe are thrilled to welcome you to our Fitness Tracker community! We're delighted that you've taken the first step towards a healthier and more active lifestyle.\n\nBest regards,\nYour Fitness Tracker");
      
      session_start();
      $_SESSION["userId"] = $row["userID"];
      $_SESSION["firstName"] = $row["firstName"];
      header("Location: index.php");
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fitness Tracker - Sign up</title>
</head>

<body>
  <header></header>
  <main>
    <form method="POST" action="signup.php">
      <p><?php echo isset($validation_error["firstName"])? $validation_error["firstName"]: ""?></p>
      <label for="firstName">First Name</label>
      <input type="text" name="firstName" id="firstName"
        <?php echo isset($_POST["firstName"]) ? "value='".$_POST["firstName"]."'" : "value=''" ?> />
      <p><?php echo isset($validation_error["lastName"])? $validation_error["lastName"]: ""?></p>
      <label for="lastName">Last Name</label>
      <input type="text" name="lastName" id="lastName"
        <?php echo isset($_POST["lastName"]) ? "value='".$_POST["lastName"]."'" : "value=''"?> />
      <p><?php echo isset($validation_error["birthday"])? $validation_error["birthday"]: ""?></p>
      <label for="birthday">Birth Date</label>
      <input type="date" name="birthday" id="birthday"
        <?php echo isset($_POST["birthday"]) ? "value='".$_POST["birthday"]."'" : "value=''" ?> />
      <p><?php echo isset($validation_error["email"])? $validation_error["email"]: ""?></p>
      <label for="email">Email</label>
      <input type="email" name="email" id="email"
        <?php echo isset($_POST["email"]) ? "value='".$_POST["email"]."'" :"value=''" ?> />
      <p><?php echo isset($validation_error["gender"])? $validation_error["gender"]: ""?></p>
      <label for="gender">Gender</label>
      <select name="gender" id="gender">
        <option value="F" <?php echo isset($_POST["gender"]) && $_POST["gender"]=="F" ? "selected": ""; ?>>Female
        </option>
        <option value="M" <?php echo isset($_POST["gender"]) && $_POST["gender"]=="M"? "selected": ""; ?>>Male</option>
        <option value="O" <?php echo isset($_POST["gender"]) && $_POST["gender"]=="O"? "selected": ""; ?>>Others
        </option>
      </select>
      <p><?php echo isset($validation_error["password"])? $validation_error["password"]: ""?></p>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" />
      <p><?php echo isset($validation_error["passwordConfirm"])? $validation_error["passwordConfirm"]: ""?></p>
      <label for="passwordConfirm">Confirm Password</label>
      <input type="password" name="passwordConfirm" id="passwordConfirm" />
      <button type="submit">Submit</button>
      <p><?php echo isset($validation_error["general"])? $validation_error["general"]: ""?></p>
    </form>
    </div>
  </main>
  <footer></footer>
</body>

</html>