<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

require_once 'db/Database.php';
require_once 'models/Item.php';

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
    <?php include 'header.php'; ?>
    
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
    </div>

    <div class="items-container">
        <?php if (empty($items)): ?>
            <p>Няма продукти</p>
        <?php else: ?>
            <?php foreach ($items as $itemData): ?>
                <div class="item-card">
                    <div class="item-image">
                        <?php if (!empty($itemData['ImageURL'])): ?>
                            <img src="<?php echo $itemData['ImageURL']; ?>" alt="Image">
                        <?php else: ?>
                            <p>Няма снимка</p>
                        <?php endif; ?>
                    </div>
                    <div class="item-details">
                        <h3><?php echo $itemData['Name']; ?></h3>
                        <p>Цена: <?php echo $itemData['Price']; ?> лв.</p>
                        <p>Пол: <?php echo ($itemData['Gender'] == 'Men') ? 'Мъж' : 'Жена'; ?></p>
                        <p>Наличност (XS): <?php echo ($itemData['Quantity_XS'] > 0) ? $itemData['Quantity_XS'] . ' бр.' : 'Няма'; ?></p>
                        <p>Наличност (S): <?php echo ($itemData['Quantity_S'] > 0) ? $itemData['Quantity_S'] . ' бр.' : 'Няма'; ?></p>
                        <p>Наличност (M): <?php echo ($itemData['Quantity_M'] > 0) ? $itemData['Quantity_M'] . ' бр.' : 'Няма'; ?></p>
                        <p>Наличност (L): <?php echo ($itemData['Quantity_L'] > 0) ? $itemData['Quantity_L'] . ' бр.' : 'Няма'; ?></p>
                        <p>Наличност (XL): <?php echo ($itemData['Quantity_XL'] > 0) ? $itemData['Quantity_XL'] . ' бр.' : 'Няма'; ?></p>
                        <div class="item-actions">
                            <a class="edit" href="edit.php?id=<?php echo $itemData['ItemID']; ?>">Промени</a>
                            <a class="delete" href="delete.php?id=<?php echo $itemData['ItemID']; ?>" onclick="return confirm('Сигурни ли сте, че искате да изтриете този продукт?')">Изтрий</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>