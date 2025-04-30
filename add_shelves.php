<?php
include 'db_connect.php'; // Database connection

if(isset($_POST['shelf_name'])){
    $shelf_name = $_POST['shelf_name'];
    $shelf_location = $_POST['shelf_location'];

    $sql = "INSERT INTO shelves (shelf_name, shelf_location) VALUES ('$shelf_name', '$shelf_location')";
    if(mysqli_query($conn, $sql)){
        echo "Shelf added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
