<?php
// Include database connection
include 'db_connect.php';

// Check if book_id is set
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM book WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the book list with a success message
        header("Location: book.php?message=Book+deleted+successfully");
        exit();
    } else {
        // Handle errors
        echo "Error deleting record: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // If no book_id is provided, redirect to the book list
    header("Location: book.php");
    exit();
}

// Close the database connection
$conn->close();
?>
