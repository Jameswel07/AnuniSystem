<?php
include 'db_connect.php';

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    $query = $conn->prepare("SELECT name, course, year FROM students WHERE student_id = ?");
    $query->bind_param("s", $student_id);
    $query->execute();
    $result = $query->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(["exists" => true, "name" => $row['name'], "course" => $row['course'] . " - " . $row['year']]);
    } else {
        echo json_encode(["exists" => false]);
    }
}
?>
