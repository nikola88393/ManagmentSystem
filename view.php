<?php
session_start();
require_once 'db/Database.php';
require_once 'models/Item.php';

$database = new Database();
$db = $database->getConnection();
$item = new Item($db);

// Fetch item data to view
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $itemID = $_GET['id'];
    $stmt = $item->readSingle($itemID);
    $itemData = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Item not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Преглед на продукт</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="product-view">
            <div class="product-image">
                <?php if (!empty($itemData['ImageURL'])): ?>
                    <img src="<?php echo $itemData['ImageURL']; ?>" alt="Image">
                <?php else: ?>
                    <p>Няма снимка</p>
                <?php endif; ?>
            </div>
            <div class="product-details">
                <h2><?php echo $itemData['Name']; ?></h2>
                <p>Цена: <?php echo $itemData['Price']; ?> лв.</p>
                <p>Пол: <?php echo ($itemData['Gender'] == 'Men') ? 'Мъж' : 'Жена'; ?></p>
                <p>Категория: <?php echo $itemData['CategoryName']; ?></p>
                <p>Наличност (XS): <?php echo ($itemData['Quantity_XS'] > 0) ? $itemData['Quantity_XS'] . ' бр.' : 'Няма'; ?></p>
                <p>Наличност (S): <?php echo ($itemData['Quantity_S'] > 0) ? $itemData['Quantity_S'] . ' бр.' : 'Няма'; ?></p>
                <p>Наличност (M): <?php echo ($itemData['Quantity_M'] > 0) ? $itemData['Quantity_M'] . ' бр.' : 'Няма'; ?></p>
                <p>Наличност (L): <?php echo ($itemData['Quantity_L'] > 0) ? $itemData['Quantity_L'] . ' бр.' : 'Няма'; ?></p>
                <p>Наличност (XL): <?php echo ($itemData['Quantity_XL'] > 0) ? $itemData['Quantity_XL'] . ' бр.' : 'Няма'; ?></p>
                <div class="product-actions">
                    <a class="edit" href="edit.php?id=<?php echo $itemData['ItemID']; ?>">Промени</a>
                    <a class="delete" href="delete.php?id=<?php echo $itemData['ItemID']; ?>" onclick="return confirm('Сигурни ли сте, че искате да изтриете този продукт?')">Изтрий</a>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>