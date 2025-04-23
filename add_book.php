<?php
include 'db_connect.php';
require 'phpqrcode/qrlib.php'; // QR Code Library
session_start();

$shelves = mysqli_query($conn, "SELECT * FROM shelves");

echo '<select name="shelf_id">';
while ($row = mysqli_fetch_assoc($shelves)) {
    echo "<option value='{$row['id']}'>{$row['shelf_name']}</option>";
}
echo '</select>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $status = "Available";
    
    if (!empty($title) && !empty($author) && !empty($category)) {
        $book_data =    "Title: $title | Author: $author | Category: $category";
        $qr_file = "qrcodes/" . uniqid() . ".png";

        QRcode::png($book_data, $qr_file, QR_ECLEVEL_L, 5);

        $stmt = $conn->prepare("INSERT INTO book (title, author, category, qr_code) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $author, $category, $qr_file);

        if ($stmt->execute()) {
            $_SESSION['success'] = "📚 Book added successfully!";
        } else {
            $_SESSION['error'] = "⚠️ Failed to add book.";
        }
    } else {
        $_SESSION['error'] = "⚠️ All fields are required!";
    }

    header("Location: book.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>📖 Add Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
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
        .card {
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center">📚 KNHS LS<</h4>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="book.php">📖 Manage Books</a>
    <a href="student_list.php">🎓 Manage Students</a>
    <a href="borrow_book.php">📥 Borrow Books</a>
    <a href="return_book.php">📤 Return Books</a>
    <a href="about.php">ℹ️ About Us</a>
    <a href="logout.php" class="text-danger">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="container">
        <div class="card p-4">
            <h2 class="text-center">📖 Add New Book</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">📖 Book Title:</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">✍ Author:</label>
                    <input type="text" name="author" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">📂 Category:</label>
                    <select name="category" class="form-control" required>
                        <option value="Filipino">Filipino</option>
                        <option value="English">English</option>
                        <option value="Science">Science</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="History">History</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">➕ Add Book</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
