<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = $_POST['category_name'];
    
    $stmt = $conn->prepare("INSERT INTO category (name) VALUES (?)");
    $stmt->bind_param("s", $category_name);
    if ($stmt->execute()) {
        header("Location: book.php"); // Redirect back to the books page after adding category
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
