<?php
// Include your database connection file
include("connection.php");

$error = ""; // Variable to hold error messages

if (isset($_POST['login'])) {
    // Get and sanitize input data
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);

    // Check for empty fields
    if (empty($email)) {
        $error .= "Email can't be left empty. ";
    }
    if (empty($password)) {
        $error .= "Password can't be left empty.";
    }

    if (empty($error)) { // Only proceed if no errors
        // Query to select user by email
        $selectemail = "SELECT * FROM `user` WHERE `email` = '$email'";
        $runselect = mysqli_query($connect, $selectemail);

        if ($runselect) {
            if (mysqli_num_rows($runselect) > 0) {
                $data = mysqli_fetch_assoc($runselect);
                $hashedPass = $data['password'];
                if (password_verify($password, $hashedPass)) {
                    header("Location: signup.php"); // Replace with your homepage
                    exit(); // Stop further script execution after redirect
                } else {
                    $error = "Incorrect Password.";
                }
            } else {
                $error = "Email isn't registered.";
            }
        } else {
            $error = "Database query error.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
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

    .login-form {
      background-color: rgba(255, 255, 255, 0.8);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    .login-form h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .login-form input[type="email"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .login-form button {
      width: 100%;
      padding: 10px;
      background-color: #5eb612;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .login-form button:hover {
      background-color: #45a049;
    }

    .error {
      color: red;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="login-form">
    <h2>Login</h2>
    <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
    <form method="POST" action="">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Enter your email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter your password" required>

      <button type="submit" name="login">Login</button>
    </form>
  </div>
</body>
</html>
