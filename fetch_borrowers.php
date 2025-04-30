<?php
include 'db_connect.php';

$book_id = $_GET['book_id'] ?? 0;
$borrowers = [];

$query = $conn->query("
    SELECT b.borrow_date, b.status, s.firstname, s.lastname 
    FROM borrow b
    JOIN student s ON b.student_id = s.student_id
    WHERE b.book_id = '$book_id'
    ORDER BY b.borrow_date DESC
");

while ($row = $query->fetch_assoc()) {
    $borrowers[] = $row;
}

echo json_encode($borrowers);
?>
