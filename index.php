<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

require_once 'db/Database.php';
require_once 'Item.php';

$database = new Database();
$db = $database->getConnection();
$item = new Item($db);

$gender_filter = isset($_GET['gender']) ? $_GET['gender'] : '';

// Fetch all items for the logged-in user with optional gender filter
$items = $item->readAllByUser($_SESSION['user_id'], $gender_filter);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инвентар</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1 class="title">Инвентар</h1>
    <div class="index-actions">
        <div class="item-options"> 
        <a class="create" href="create.php">Добави</a>
        <form method="get" class="filter-form">
        <label for="gender">Филтрирай по пол:</label>
        <select name="gender" id="gender" onchange="this.form.submit()">
            <option value="">Всички</option>
            <option value="Men" <?php if ($gender_filter == 'Men') echo 'selected'; ?>>Мъж</option>
            <option value="Women" <?php if ($gender_filter == 'Women') echo 'selected'; ?>>Жена</option>
        </select>
        </form>
        </div>
        <div class="user-info">
            <h3>Здравей, <?php echo $_SESSION['username']; ?>!</h3>
            <a class="logout" href="auth/logout.php">Изход</a>
        </div>
    </div>

     

    <table border="1">
        <thead>
            <tr>
                <th>Снимка</th>
                <th>Име</th>
                <th>Цена</th>
                <th>Пол</th>
                <th>Наличност</th>
                <th>Размер</th>
                <th>Опции</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
            <tr>
                <td colspan="7">Няма продукти</td>
            </tr>
            <?php else: ?>
            <?php foreach ($items as $itemData): ?>
            <tr>
                <td><?php echo !empty($itemData['ImageURL']) ? '<img src="' . $itemData['ImageURL'] . '" alt="Image" width="50">' : 'Няма снимка'; ?></td>
                <td><?php echo $itemData['Name']; ?></td>
                <td><?php echo $itemData['Price']." лв."; ?></td>
                <td><?php echo $itemData['Gender']; ?></td>
                <td><?php echo $itemData['Quantity'].' бр.'; ?></td>
                <td><?php echo $itemData['Size']; ?></td>
                <td>
                    <a class="edit" href="edit.php?id=<?php echo $itemData['ItemID']; ?>">Промени</a>
                    <a class="delete" href="delete.php?id=<?php echo $itemData['ItemID']; ?>" onclick="return confirm('Сигурни ли сте, че искате да изтриете този продукт?')">Изтрий</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>