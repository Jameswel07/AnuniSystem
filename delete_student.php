<?php
include 'db_connect.php'; // Ensure this file contains your database connection details

// Check if 'id' is present in the URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM student WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the student list page after successful deletion
        header("Location: student_list.php");
        exit;
    } else {
        // Handle errors if deletion fails
        echo "Error deleting record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the student list page if 'id' is not present in the URL
    header("Location: student_list.php");
    exit;
}

// Close the database connection
$conn->close();
?>
