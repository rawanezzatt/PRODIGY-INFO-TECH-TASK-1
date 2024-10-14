<?php
include 'connection.php';
$error = '';

if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_pass = $_POST['confirm_password']; // Added for password confirmation

    // Password validation
    $passwordhashing = password_hash($password, PASSWORD_DEFAULT);
    $lowercase = preg_match('@[a-z]@', $password);
    $uppercase = preg_match('@[A-Z]@', $password);
    $numbers = preg_match('@[0-9]@', $password);

    // Check if email already exists
    $select = "SELECT `email` FROM `user` WHERE `email` ='$email'";
    $run_select = mysqli_query($connect, $select);
    $rows = mysqli_num_rows($run_select);

    // Check if phone number already exists
    $selectPN = "SELECT `phone` FROM `user` WHERE `phone` ='$phone'";
    $run_selectPN = mysqli_query($connect, $selectPN);
    $rowsPN = mysqli_num_rows($run_selectPN);

    if ($rows > 0) {
        $error = "This email is already taken";
    } elseif (strlen($phone) != 11) {
        $error = "Please enter a valid phone number";
    } elseif ($rowsPN > 0) {
        $error = "This phone number is already in use";
    } elseif (!$lowercase || !$uppercase || !$numbers) {
        $error = "Password must contain at least 1 uppercase, 1 lowercase, and 1 number";
    } elseif ($password !== $confirm_pass) {
        $error = "Password doesn't match confirmed password";
    } else {
        $insert = "INSERT INTO `user` VALUES(NULL, '$name', '$email', '$passwordhashing', '$phone')";
        $run_insert = mysqli_query($connect, $insert);
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup Form</title>
  <style>
    body {
      background-image: url('background.jpg.jpg');
      background-size: cover;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .signup-form {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      width: 300px;
    }
    .signup-form h2 {
      margin-bottom: 20px;
      color: #333;
    }
    .signup-form input[type="text"],
    .signup-form input[type="email"],
    .signup-form input[type="password"],
    .signup-form input[type="tel"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .signup-form button {
      width: 100%;
      padding: 10px;
      background-color: #5eb612;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    .signup-form button:hover {
      background-color: #45a049;
    }
    .error {
      color: red;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="signup-form">
    <h2>Signup</h2>
    <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
    <form method="POST" action="">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your name" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" required>

      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>

      <label for="phone">Phone</label>
      <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

      <button type="submit" name="signup">Signup</button>
    </form>
  </div>
</body>
</html>
