<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['student_name']);
    $book_id = trim($_POST['book_id']);

    if (empty($full_name) || empty($book_id)) {
        $_SESSION['error'] = "Please scan both Student Name and Book ID!";
        header('location: borrow_book.php');
        exit();
    }

    // Split full name into first and last name
    $name_parts = explode(' ', $full_name, 2);
    $firstname = $name_parts[0] ?? '';
    $lastname = $name_parts[1] ?? '';

    if (empty($firstname) || empty($lastname)) {
        $_SESSION['error'] = "Invalid student name format!";
        header('location: borrow_book.php');
        exit();
    }

    // Validate student by firstname and lastname
    $stmt = $conn->prepare("SELECT * FROM student WHERE firstname = ? AND lastname = ?");
    $stmt->bind_param("ss", $firstname, $lastname);
    $stmt->execute();
    $student_result = $stmt->get_result();

    if ($student_result->num_rows == 0) {
        $_SESSION['error'] = "Student not found!";
        header('location: borrow_book.php');
        exit();
    }

    $student = $student_result->fetch_assoc();
    $student_id = $student['student_id'];

    // Validate book
    $stmt = $conn->prepare("SELECT * FROM book WHERE book_id = ?");
    $stmt->bind_param("s", $book_id);
    $stmt->execute();
    $book_result = $stmt->get_result();
    if ($book_result->num_rows == 0) {
        $_SESSION['error'] = "Book not found!";
        header('location: borrow_book.php');
        exit();
    }

    // Check if book is already borrowed
    $stmt = $conn->prepare("SELECT * FROM borrow WHERE book_id = ? AND status = 'borrowed'");
    $stmt->bind_param("s", $book_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Book already borrowed!";
        header('location: borrow_book.php');
        exit();
    }

    // Insert borrow record
    $due_date = date('Y-m-d H:i:s', strtotime('+7 days'));
    $stmt = $conn->prepare("INSERT INTO borrow (student_id, book_id, borrow_date, due_date, status) VALUES (?, ?, NOW(), ?, 'borrowed')");
    $stmt->bind_param("sss", $student_id, $book_id, $due_date);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Book borrowed successfully!";
    } else {
        $_SESSION['error'] = "Failed to borrow book.";
    }

    header('location: borrow_list.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrow Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .form-label {
            font-weight: 600;
        }
        input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 bg-white">
                <h4 class="mb-3 text-primary"><i class="bi bi-box-arrow-in-down"></i> Borrow Book</h4>
                <a href="dashboard.php" class="btn btn-outline-secondary mb-3">⬅ Back to Dashboard</a>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php elseif (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label for="student_name" class="form-label">🎓 Scan Student QR Code:</label>
                        <input type="text" name="student_name" id="student_name" class="form-control" placeholder="Firstname Lastname" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="book_id" class="form-label">📘 Scan Book QR Code:</label>
                        <input type="text" name="book_id" id="book_id" class="form-control" placeholder="Book ID" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-circle"></i> Borrow Book</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
let buffer = '';
let lastTime = 0;

document.addEventListener('keypress', function (e) {
    const now = new Date().getTime();
    if (now - lastTime > 100) buffer = '';
    buffer += e.key;
    lastTime = now;

    if (e.key === 'Enter') {
        const studentInput = document.getElementById('student_name');
        const bookInput = document.getElementById('book_id');

        if (!studentInput.value) {
            studentInput.value = buffer.trim();
            bookInput.focus();
        } else if (!bookInput.value) {
            bookInput.value = buffer.trim();
        }
        buffer = '';
        e.preventDefault();
    }
});
</script>
</body>
</html>
