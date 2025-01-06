<?php
session_start();
require_once '../db/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        header("Location: ../index.php");
    } else {
        $error_message = "Входът не бе успешен. Моля, проверете вашето потребителско име и парола.";
    }
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <form class="form" method="post">
        <h2>Вход</h2>
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <label for="username">Потребителско име:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Парола:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Вход</button>
        <a href="register.php">Регистрация</a>
    </form>
    <?php include '../footer.php'; ?>
</body>
</html>