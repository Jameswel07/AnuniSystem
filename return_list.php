<?php
include 'db_connect.php';

$result = $conn->query("
    SELECT b.borrow_id, s.firstname, s.lastname, bk.title, b.return_date
    FROM borrow b
    JOIN student s ON b.student_id = s.student_id
    JOIN book bk ON b.book_id = bk.book_id
    WHERE b.status = 'returned'
    ORDER BY b.return_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Returned Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>📄 Returned Book List</h3>
    <a href="book.php" class="btn btn-secondary mb-3">⬅ Back</a>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Book Title</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): $i = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['return_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No returned books yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
