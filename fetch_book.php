<?php
include 'db_connect.php';

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $query = $conn->prepare("SELECT title, author, status FROM books WHERE book_id = ?");
    $query->bind_param("s", $book_id);
    $query->execute();
    $result = $query->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(["exists" => true, "title" => $row['title'], "author" => $row['author'], "status" => $row['status']]);
    } else {
        echo json_encode(["exists" => false]);
    }
}
?>
