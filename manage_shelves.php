<?php
include 'db_connect.php';

$sql = "SELECT * FROM shelves";
$result = mysqli_query($conn, $sql);

echo "<table border='1'>";
echo "<tr><th>Shelf Name</th><th>Location</th><th>Actions</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['shelf_name']}</td>
            <td>{$row['shelf_location']}</td>
            <td><a href='edit_shelf.php?id={$row['id']}'>Edit</a> | 
                <a href='delete_shelf.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
          </tr>";
}
echo "</table>";
?>
