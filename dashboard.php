<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Helper function to safely fetch total
function getTotal($query, $conn) {
    $res = $conn->query($query);
    if ($res && $row = $res->fetch_assoc()) {
        return (int)$row['total'];
    }
    return 0;
}

// Fetch total counts safely
$book = getTotal("SELECT COUNT(*) AS total FROM book", $conn);
$student = getTotal("SELECT COUNT(*) AS total FROM student", $conn);
$borrow = getTotal("SELECT COUNT(*) AS total FROM borrow WHERE return_date IS NULL", $conn);
$overdue = getTotal("SELECT COUNT(*) AS total FROM borrow WHERE return_date IS NULL AND due_date < NOW()", $conn);
$return = getTotal("SELECT COUNT(*) AS total FROM borrow WHERE return_date IS NOT NULL", $conn);

// Penalty calculation (₱5 per day, min ₱10)
$penalty = 0;
$penaltyQuery = $conn->query("
    SELECT SUM(GREATEST(DATEDIFF(NOW(), due_date), 0) * 5) AS total_penalty 
    FROM borrow
    WHERE return_date IS NULL AND due_date < NOW()
");

if ($penaltyQuery && $row = $penaltyQuery->fetch_assoc()) {
    $penalty = (int) $row['total_penalty'];
}
if ($overdue > 0 && $penalty < 10) {
    $penalty = 10;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>📊 Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
        <h4 class="text-center">📚 KNHS LS</h4>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="book.php">📖 Manage Books</a>
        <a href="student_list.php">🎓 Manage Students</a>
        <a href="borrow_book.php">📥 Borrow Books</a>
        <a href="return_book.php">📤 Return Books</a>
        <a href="inventory_reports.php">📊 Inventory Reports</a>
        <a href="about.php">ℹ️ About Us</a>
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
                        <p class="card-text"><?= $book ?></p>
                        <a href="book.php" class="btn btn-light btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">🎓 Total Students</h5>
                        <p class="card-text"><?= $student ?></p>
                        <a href="student_list.php" class="btn btn-light btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">🔄 Borrowed Books</h5>
                        <p class="card-text"><?= $borrow ?></p>
                        <a href="borrow_list.php" class="btn btn-light btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">⏳ Overdue Books</h5>
                        <p class="card-text"><?= $overdue ?></p>
                        <a href="overdue_books.php" class="btn btn-light btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">✅ Returned Books</h5>
                        <p class="card-text"><?= $return ?></p>
                        <a href="return_list.php" class="btn btn-light btn-sm">View Details</a>
                    </div>
                </div>
            </div>
            

        </div>
    </div>

</body>
</html>
