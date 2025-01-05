<?php
session_start();
require_once 'db/Database.php';
require_once 'models/Item.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$item = new Item($db);

// Handle form submission
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $item->Name = $_POST['Name'];
    $item->Price = $_POST['Price'];
    $item->Gender = $_POST['Gender'];
    $item->Quantity = $_POST['Quantity'];
    $item->Size = $_POST['Size'];
    $item->user_id = $_SESSION['user_id'];

    // Handle image upload
    if (isset($_FILES['Image']) && $_FILES['Image']['error'] == 0) {
        $targetFile = basename($_FILES['Image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES['Image']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['Image']['tmp_name'], $targetFile)) {
                $item->ImageURL = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "The file is not an image.";
        }
    } else {
        $item->ImageURL = ''; // If no image is uploaded
    }

    if ($item->create()) {
        // Redirect to the index page after successful creation
        header("Location: index.php");
        exit; // Stop further execution
    } else {
        echo "Unable to create item.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Създай продукт</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1 class="title">Създай продукт</h1>
    <form class="form" method="post" enctype="multipart/form-data">
        <input type="text" name="Name" placeholder="Име" required><br>
        <input type="number" name="Price" placeholder="Цена" required><br>
        <select name="Gender" required>
        <option value="">Избери пол</option>
            <option value="Men">Мъж</option>
            <option value="Women">Жена</option>
        </select><br>
        <input type="number" name="Quantity" placeholder="Наличност" required><br>
        <select name="Size" required>
            <option value="">Размер</option>
            <option value="XS">XS</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
        </select><br>
        <input type="file" name="Image"><br>
        <button type="submit" name="action" value="create">Създай продукт</button>
    </form>
</body>
</html>