<?php
session_start();
include 'db_connect.php';

// Penalty rate per day
$penalty_rate = 10; 

// Fetch overdue books
$sql = "SELECT s.firstname, s.lastname, bk.title, b.borrow_date, b.due_date, b.return_date,
        DATEDIFF(CURDATE(), b.due_date) AS days_overdue,
        (CASE 
            WHEN b.return_date IS NULL AND CURDATE() > b.due_date 
            THEN (DATEDIFF(CURDATE(), b.due_date) * ?) 
            ELSE 0 
        END) AS penalty
        FROM borrow b
        JOIN student s ON b.student_id = s.student_id
        JOIN book bk ON b.book_id = bk.book_id
        WHERE b.return_date IS NULL AND CURDATE() > b.due_date
        ORDER BY b.due_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $penalty_rate);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>📌 Overdue Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            padding: 15px;
            position: fixed;
            color: white;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">📚 KNHS LS</h4>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="book.php">📖 Manage Books</a>
    <a href="student_list.php">🎓 Manage Students</a>
    <a href="borrow_book.php">📥 Borrow Books</a>
    <a href="return_book.php">📤 Return Books</a>
    <a href="about.php">ℹ️ About Us</a>
    <a href="overdue_books.php" class="active">⏳ Overdue Books</a>
    <a href="logout.php" class="text-danger">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="container">
        <h2>📌 Overdue Borrowed Books</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>👤 Student Name</th>
                    <th>📖 Book Title</th>
                    <th>📅 Borrowed Date</th>
                    <th>📅 Due Date</th>
                    <th>⏳ Days Overdue</th>
                    <th>💰 Penalty (PHP)</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                    <td><?= htmlspecialchars($row['title']); ?></td>
                    <td><?= date("F j, Y", strtotime($row['borrow_date'])); ?></td>
                    <td><?= date("F j, Y", strtotime($row['due_date'])); ?></td>
                    <td class="text-danger"><strong><?= $row['days_overdue']; ?> days</strong></td>
                    <td class="text-danger"><strong>₱<?= number_format($row['penalty'], 2); ?></strong></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
