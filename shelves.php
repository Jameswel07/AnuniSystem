<?php
include 'db_connect.php';
session_start();

// Fetch shelves
$query = "SELECT * FROM shelves ORDER BY shelf_name ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>📚 Manage Shelves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>📚 Shelves List</h2>
    <a href="add_shelf.php" class="btn btn-success mb-3">➕ Add Shelf</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>📌 Shelf Name</th>
                <th>📍 Location</th>
                <th>🛠 Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['shelf_name']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td>
                        <a href="edit_shelf.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="shelves.php?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this shelf?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
