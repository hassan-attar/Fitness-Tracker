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
    <form action="POST">
      <label for="firstName">First Name</label>
      <input type="text" name="firstName" id="firstName" />
      <label for="lastName">Last Name</label>
      <input type="text" name="lastName" id="lastName" />
      <label for="birthday">Birth Date</label>
      <input type="date" name="birthday" id="birthday" />
      <label for="email">Email</label>
      <input type="email" name="email" id="email" />
      <label for="gender">Gender</label>
      <select name="gender" id="gender">
        <option value="F">Female</option>
        <option value="M">Male</option>
        <option value="O">Others</option>
      </select>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" />
      <label for="passwordConfirm">Confirm Password</label>
      <input type="password" name="passwordConfirm" id="passwordConfirm" />
    </form>
  </main>
  <footer></footer>
</body>

</html>