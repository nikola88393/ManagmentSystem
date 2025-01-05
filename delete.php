<?php
require_once 'db/Database.php';
require_once 'Item.php';

$database = new Database();
$db = $database->getConnection();
$parfum = new Item($db);

// Check if ID is set in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $parfumID = $_GET['id'];
    if ($parfum->delete($parfumID)) {
        // Redirect back to the index page after successful deletion
        header("Location: index.php");
        exit; // Make sure to stop further execution
    } else {
        echo "Unable to delete parfum.";
    }
}
?>
