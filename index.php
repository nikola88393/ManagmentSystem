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
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch all items for the logged-in user with optional gender filter, category filter, and search query
$items = $item->readAllByUser($_SESSION['user_id'], $gender_filter, $category_filter, $search_query);

// Fetch all categories
$query = "SELECT * FROM categories";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="search-form-container">
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Търсене на продукт" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Търсене</button>
        </form>
    </div>
    
    <div class="index-actions">
        <div class="item-options"> 
            <div>
            <a class="create" href="create.php">Добави</a>
            <a href="categories.php" class="create">Категории</a>
            </div>
            <div>
                
            <form method="get" class="filter-form">
                <label for="gender">Филтрирай по пол:</label>
                <select name="gender" id="gender" onchange="this.form.submit()">
                    <option value="">Всички</option>
                    <option value="Men" <?php if ($gender_filter == 'Men') echo 'selected'; ?>>Мъж</option>
                    <option value="Women" <?php if ($gender_filter == 'Women') echo 'selected'; ?>>Жена</option>
                </select>
            </form>
            <form method="get" class="filter-form">
                <label for="category">Филтрирай по категория:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="">Всички</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CategoryID']; ?>" <?php if ($category_filter == $category['CategoryID']) echo 'selected'; ?>><?php echo $category['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            </div>

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
                        <p>Категория: <?php echo $itemData['CategoryName']; ?></p>
                        <div class="item-actions">
                            <a class="view" href="view.php?id=<?php echo $itemData['ItemID']; ?>">Преглед</a>
                            <a class="edit" href="edit.php?id=<?php echo $itemData['ItemID']; ?>">Промени</a>
                            <a class="delete" href="delete.php?id=<?php echo $itemData['ItemID']; ?>" onclick="return confirm('Сигурни ли сте, че искате да изтриете този продукт?')">Изтрий</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>