<?php
session_start();
require_once '../db/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error_message = "Passwords do not match.";
    } else {
        $user->username = $_POST['username'];
        $user->password = $_POST['password'];

        $result = $user->register();
        if ($result === true) {
            $success_message = "User registered successfully.";
            header("Location: login.php");
            exit;
        } elseif ($result === "Username already exists.") {
            $error_message = "Username already exists. Please choose a different username.";
        } else {
            $error_message = "User registration failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <form class="form" method="post" onsubmit="return validateForm()">
        <h2>Register</h2>
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <a href="login.php">Login</a>
        <button type="submit">Register</button>
    </form>

    <script>
    function validateForm() {
        var password = document.querySelector('input[name="password"]').value;
        var confirmPassword = document.querySelector('input[name="confirm_password"]').value;
        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>