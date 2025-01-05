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
        $error_message = "Паролите не съвпадат.";
    } else {
        $user->username = $_POST['username'];
        $user->password = $_POST['password'];

        $result = $user->register();
        if ($result === true) {
            $success_message = "Потребителят е регистриран успешно.";
            header("Location: login.php");
            exit;
        } elseif ($result === "Username already exists.") {
            $error_message = "Потребителското име вече съществува. Моля, изберете друго потребителско име.";
        } else {
            $error_message = "Регистрацията на потребителя не бе успешна.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <form class="form" method="post" onsubmit="return validateForm()">
        <h2>Регистрация</h2>
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <label for="username">Потребителско име:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Парола:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="confirm_password">Потвърдете паролата:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <button type="submit">Регистрация</button>
        <a href="login.php">Вход</a>
    </form>
</body>
</html>