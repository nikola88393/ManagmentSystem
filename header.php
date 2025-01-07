<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление на инвентара</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="index.php"><h1>Инвентар</h1></a>
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="user-info">
                    <h3>Здравей, <?php echo $_SESSION['username']; ?>!</h3>
                    <a class="logout" href="auth/logout.php">Изход</a>
                </div>
            <?php endif; ?>         
        </div>
    </header>
</body>
</html>