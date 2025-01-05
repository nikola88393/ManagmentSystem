<?php
require_once 'db/Database.php';
require_once 'models/Item.php';

$database = new Database();
$db = $database->getConnection();
$item = new Item($db);

// Fetch item data to edit
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $itemID = $_GET['id'];
    $stmt = $item->readSingle($itemID);
    $itemData = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Item not found!";
    exit;
}

// Handle form submission for update
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $item->Name = $_POST['Name'];
    $item->Price = $_POST['Price'];
    $item->Gender = $_POST['Gender'];
    $item->Quantity = $_POST['Quantity'];
    $item->Size = $_POST['Size'];
    $item->ItemID = $_POST['ItemID'];

    if (isset($_FILES['Image']) && $_FILES['Image']['error'] == 0) {
        $targetFile = basename($_FILES['Image']['name']);
        if (move_uploaded_file($_FILES['Image']['tmp_name'], $targetFile)) {
            $item->ImageURL = $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $item->ImageURL = $itemData['ImageURL'];
    }

    if ($item->update()) {
        // Redirect back to index.php after successful update
        header("Location: index.php");
        exit; // Stop further execution
    } else {
        echo "Unable to update item.";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирай продукт</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1 class="title">Редактирай продукт</h1>
    <form class="form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ItemID" value="<?php echo $itemData['ItemID']; ?>">
        <div>
            <label for="Name">Име:</label>
            <input type="text" id="Name" name="Name" value="<?php echo $itemData['Name']; ?>" required>
        </div>
        <div>
            <label for="Price">Цена:</label>
            <input type="number" id="Price" name="Price" value="<?php echo $itemData['Price']; ?>" required>
        </div>
        <div>
            <label for="Gender">Пол:</label>
            <select id="Gender" name="Gender" required>
                <option value="Men" <?php if ($itemData['Gender'] == 'Men') echo 'selected'; ?>>Мъж</option>
                <option value="Women" <?php if ($itemData['Gender'] == 'Women') echo 'selected'; ?>>Жена</option>
            </select>
        </div>
        <div>
            <label for="Quantity">Наличност:</label>
            <input type="number" id="Quantity" name="Quantity" value="<?php echo $itemData['Quantity']; ?>" required>
        </div>
        <div>
            <label for="Size">Размер:</label>
            <select id="Size" name="Size" required>
                <option value="XS" <?php if ($itemData['Size'] == 'XS') echo 'selected'; ?>>XS</option>
                <option value="S" <?php if ($itemData['Size'] == 'S') echo 'selected'; ?>>S</option>
                <option value="M" <?php if ($itemData['Size'] == 'M') echo 'selected'; ?>>M</option>
                <option value="L" <?php if ($itemData['Size'] == 'L') echo 'selected'; ?>>L</option>
                <option value="XL" <?php if ($itemData['Size'] == 'XL') echo 'selected'; ?>>XL</option>
            </select>
        </div>
        <div>
            <label for="Image">Снимка:</label>
            <input type="file" id="Image" name="Image">
            <?php if (!empty($itemData['ImageURL'])): ?>
                <img src="<?php echo $itemData['ImageURL']; ?>" alt="Image" width="100">
            <?php endif; ?>
        </div>
        <button type="submit" name="action" value="update">Актуализирай продукт</button>
    </form>
</body>
</html>