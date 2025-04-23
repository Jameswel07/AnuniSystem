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
<html>
<head>
    <title>Borrow Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>📚 Borrow Book</h3>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Scan Student QR Code (Firstname Lastname):</label>
            <input type="text" name="student_name" id="student_name" class="form-control" required read only>
        </div>
        <div class="mb-3">
            <label>Scan Book QR Code (Book ID):</label>
            <input type="text" name="book_id" id="book_id" class="form-control" required read only>
        </div>
        <button class="btn btn-primary w-100" type="submit">📖 Borrow</button>
    </form>
</div>

<script>
    document.addEventListener("keydown", function(event) {
        let activeInput = document.activeElement;
        if (activeInput && activeInput.tagName === "INPUT") return;

        const studentInput = document.getElementById('student_name');
        const bookInput = document.getElementById('book_id');

        let buffer = '';
        let lastTime = 0;

        document.addEventListener('keypress', function (e) {
            const now = new Date().getTime();
            if (now - lastTime > 100) buffer = '';
            buffer += e.key;
            lastTime = now;

            if (e.key === 'Enter') {
                if (!studentInput.value) {
                    studentInput.value = buffer.trim();
                } else if (!bookInput.value) {
                    bookInput.value = buffer.trim();
                }
                buffer = '';
            }
        });
    });
</script>
</body>
</html>
