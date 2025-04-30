<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Fetch data counts
$books = $conn->query("SELECT COUNT(*) as total FROM books")->fetch_assoc()['total'];
$students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
$borrow = $conn->query("SELECT COUNT(*) as total FROM borrow WHERE return_date IS NULL")->fetch_assoc()['total'];
$overdue = $conn->query("SELECT COUNT(*) as total FROM borrow WHERE return_date IS NULL AND borrow_date < NOW() - INTERVAL 7 DAY")->fetch_assoc()['total'];

// Calculate total penalty (assuming a penalty of ₱5 per day late)
$penaltyQuery = $conn->query("
    SELECT SUM(GREATEST(DATEDIFF(NOW(), due_date), 0) * 5) AS total_penalty 
    FROM borrow
    WHERE return_date IS NULL AND due_date < NOW()
");
$penalty = $penaltyQuery->fetch_assoc()['total_penalty'] ?? 0;
if ($penalty < 0) $penalty = 0;

// Fetch overdue books with return date
$overdueBooksQuery = $conn->query("
    SELECT bb.book_id, b.title, s.firstname, s.lastname, bb.due_date, bb.return_date, bb.borrow_date
    FROM borrow bb
    JOIN books b ON bb.book_id = b.book_id
    JOIN students s ON bb.student_id = s.student_id
    WHERE bb.return_date IS NULL AND bb.due_date < NOW()
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar a {
            padding: 10px;
            display: block;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover { background: #495057; }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
        .table th, .table td { text-align: center; }
    </style>
</head>
<body>

    <!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">📚 Library Admin</h4>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="book.php">📖 Manage Books</a>
    <a href="student_list.php">🎓 Manage Students</a>
    <a href="borrow_book.php">📥 Borrow Books</a>
    <a href="return_book.php">📤 Return Books</a>
    <a href="logout.php" class="text-danger">🚪 Logout</a>
</div>


    <!-- Main Content -->
    <div class="content">
        <h2>📊 Library Dashboard</h2>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">📚 Total Books</h5>
                        <p class="card-text"><?= $books ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">🎓 Total Students</h5>
                        <p class="card-text"><?= $students ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">🔄 Borrowed Books</h5>
                        <p class="card-text"><?= $borrow?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">⏳ Overdue Books</h5>
                        <p class="card-text"><?= $overdue ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title">💰 Total Penalty</h5>
                        <p class="card-text">₱<?= number_format($penalty, 2) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Books Table -->
        <h3 class="mt-4">⚠ Overdue Books</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-danger">
                <tr>
                    <th>📚 Book ID</th>
                    <th>📖 Title</th>
                    <th>👨‍🎓 Student</th>
                    <th>📅 Borrow Date</th>
                    <th>⏳ Due Date</th>
                    <th>✅ Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $overdueBooksQuery->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['book_id']; ?></td>
                    <td><?= $row['title']; ?></td>
                    <td><?= $row['firstname'] . " " . $row['lastname']; ?></td>
                    <td><?= date("Y-m-d", strtotime($row['borrow_date'])); ?></td>
                    <td><strong class="text-danger"><?= date("Y-m-d", strtotime($row['due_date'])); ?></strong></td>
                    <td><?= $row['return_date'] ? date("Y-m-d", strtotime($row['return_date'])) : "Not Returned"; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

</body>
</html>
