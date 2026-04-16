<?php
require "config.php";
$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>IRMS Login</title>
    <link rel="stylesheet" href="LoginMenu.css" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"/>
  </head>

  <body>
    <div class="loginContainer">
      <form action="authentication.php" method="POST">
        <h1>Login</h1>

        <div class="inputBox">
          <input type="text" name="user_id" placeholder="User ID" required />
          <i class="bx bxs-user"></i>
        </div>
        <div class="inputBox">
          <input type="password" name="password" placeholder="Enter your password" required />
          <i class="bx bxs-lock-alt"></i>
        </div>

        <?php if (!empty($error)): ?>
          <p class="error">
            <i class="bx bxs-error-circle"></i>
            <?php echo htmlspecialchars($error); ?>
          </p>
        <?php endif; ?>

        <div class="rememberForget">
          <label class="checkmark">
            <input type="checkbox" name="remember">
            <span class="box"></span>Remember Me
          </label>
          <a href="#">Forgot Password?</a>
        </div>
        <button type="submit" class="btn">Log in</button>
        <div class="extraInformation">
          <p>Don't have an account? Kindly contact your Administration Department for assistance.</p>
        </div>
      </form>
    </div>
  </body>
</html>