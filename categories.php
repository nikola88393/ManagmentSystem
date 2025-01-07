<?php
session_start();
require_once 'db/Database.php';

$database = new Database();
$db = $database->getConnection();

$error_message = '';

// Handle form submission for adding a new category
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $categoryName = htmlspecialchars(strip_tags($_POST['category_name']));

    // Check if the category name already exists
    $query = "SELECT COUNT(*) as count FROM categories WHERE Name = :Name";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':Name', $categoryName);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        $error_message = "Категорията вече съществува.";
    } else {
        $query = "INSERT INTO categories (Name) VALUES (:Name)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':Name', $categoryName);
        if ($stmt->execute()) {
            header("Location: categories.php");
            exit;
        } else {
            $error_message = "Unable to add category.";
        }
    }
}

// Handle category deletion
if (isset($_GET['delete_id'])) {
    $categoryID = $_GET['delete_id'];

    // Check if there are items associated with this category
    $query = "SELECT COUNT(*) as item_count FROM items WHERE CategoryID = :CategoryID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':CategoryID', $categoryID);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['item_count'] > 0) {
        $error_message = "Категорията не може да бъде изтрита, защото има асоциирани продукти.";
    } else {
        $query = "DELETE FROM categories WHERE CategoryID = :CategoryID";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':CategoryID', $categoryID);
        if ($stmt->execute()) {
            header("Location: categories.php");
            exit;
        } else {
            $error_message = "Unable to delete category.";
        }
    }
}

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
    <title>Управление на категории</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <?php if ($error_message): ?>
            <div style="text-align: center" class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form class="form" method="post">
            <div>
                <label for="category_name">Име на категория:</label>
                <input type="text" id="category_name" name="category_name" required>
            </div>
            <button type="submit" name="action" value="add">Добави категория</button>
        </form>
        <div class="categories-list">
            <h2>Съществуващи категории</h2>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <?php echo $category['Name']; ?>
                        <a class="delete" href="categories.php?delete_id=<?php echo $category['CategoryID']; ?>" onclick="return confirm('Сигурни ли сте, че искате да изтриете тази категория?')">Изтрий</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>