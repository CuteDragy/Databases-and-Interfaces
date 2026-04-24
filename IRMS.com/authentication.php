<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once("config.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: LoginMenu.php");
    exit();
}

$username = trim($_POST["user_id"]);
$password = trim($_POST["password"]);

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$destination = "LoginMenu.php";
$success = false;

if ($user && password_verify($password, $user["password"])) {
    $hashOptions = ['cost' => 12];
    if (password_needs_rehash($user["password"], PASSWORD_DEFAULT, $hashOptions)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT, $hashOptions);
        $updateStmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($updateStmt, 'ss', $newHash, $username);
        mysqli_stmt_execute($updateStmt);
    }
    session_regenerate_id(true);

    $_SESSION["user"]   = $user["user_id"];
    $_SESSION["role"]   = $user["role"];
    $success = true;

    switch ($user["role"]) {
        case "Student":
            $destination = "Result.php";
            break;
        case "Admin":
            $destination = "admin-index.php";
            break;
        case "Assessor":
            $destination = "markentry.php";
            break;
        default:
            $_SESSION["error"] = "Unknown role. Please contact the administration department.";
            $destination = "LoginMenu.php";
            $success = false;
            break;
    }
} else {
    $_SESSION["error"] = "Invalid username or password.";
    $destination = "LoginMenu.php";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="2;url=<?php echo htmlspecialchars($destination); ?>">
    <title>Verifying...</title>
    <link rel="stylesheet" href="css/LoginMenu.css">
</head>
<body>
    <div class="loginContainer">
        <?php if ($success): ?>
            <div class="loader"></div>
            <h2>Connection has been initiated successfully!</h2>
            <br>
            <p>Verifying credentials and redirecting you now...</p>
        <?php else: ?>
            <h2>Login Failed</h2>
            <p><?php echo htmlspecialchars($_SESSION["error"]); ?></p>
            <p>Redirecting you back to login...</p>
        <?php endif; ?>
    </div>
</body>
</html>