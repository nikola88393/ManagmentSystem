<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /ManagmentSystem/auth/login.php");
    exit;
}

require_once 'db/Database.php';
require_once 'Item.php';

$database = new Database();
$db = $database->getConnection();
$item = new Item($db);

// Fetch all items for the logged-in user
$items = $item->readAllByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Инвентар</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1 class="title">Инвентар</h1>
    <a class="create" href="create.php">Добави</a>
    <a class="logout" href="auth/logout.php">Logout</a>
    
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
                    <a class="delete" href="delete.php?id=<?php echo $itemData['ItemID']; ?>" onclick="return confirm('Are you sure you want to delete this item?')">Изтрий</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>