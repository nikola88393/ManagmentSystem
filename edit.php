<?php
require_once 'db/Database.php';
require_once 'Item.php';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Промени продукт</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1 class="title">Промени продукт</h1>
    <form class="form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ItemID" value="<?php echo $itemData['ItemID']; ?>">
        <input type="text" name="Name" value="<?php echo $itemData['Name']; ?>" required><br>
        <input type="number" name="Price" value="<?php echo $itemData['Price']; ?>" required><br>
        <select name="Gender" required>
            <option value="Men" <?php echo ($itemData['Gender'] == 'Men') ? 'selected' : ''; ?>>Мъж</option>
            <option value="Women" <?php echo ($itemData['Gender'] == 'Women') ? 'selected' : ''; ?>>Жена</option>
        </select><br>
        <input type="number" name="Quantity" value="<?php echo $itemData['Quantity']; ?>" required><br>
        <select name="Size" required>
            <option value="XS" <?php echo ($itemData['Size'] == 'XS') ? 'selected' : ''; ?>>XS</option>
            <option value="S" <?php echo ($itemData['Size'] == 'S') ? 'selected' : ''; ?>>S</option>
            <option value="M" <?php echo ($itemData['Size'] == 'M') ? 'selected' : ''; ?>>M</option>
            <option value="L" <?php echo ($itemData['Size'] == 'L') ? 'selected' : ''; ?>>L</option>
            <option value="XL" <?php echo ($itemData['Size'] == 'XL') ? 'selected' : ''; ?>>XL</option>
        </select><br>
        <input type="file" name="Image"><br>
        <button type="submit" name="action" value="update">Запази</button>
    </form>
</body>
</html>