<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['student_name']);
    $book_id = trim($_POST['book_id']);

    if (empty($full_name) || empty($book_id)) {
        $_SESSION['error'] = "Please scan both Student Name and Book ID!";
        header('location: return_book.php');
        exit();
    }

    $name_parts = explode(' ', $full_name, 2);
    $firstname = trim($name_parts[0] ?? '');
    $lastname = trim($name_parts[1] ?? '');

    if (empty($firstname) || empty($lastname)) {
        $_SESSION['error'] = "Invalid student name format!";
        header('location: return_book.php');
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM student WHERE firstname = ? AND lastname = ?");
    $stmt->bind_param("ss", $firstname, $lastname);
    $stmt->execute();
    $student_result = $stmt->get_result();

    if ($student_result->num_rows == 0) {
        $_SESSION['error'] = "Student not found!";
        header('location: return_book.php');
        exit();
    }

    $student = $student_result->fetch_assoc();
    $student_id = $student['student_id'];

    $stmt = $conn->prepare("SELECT * FROM borrow WHERE student_id = ? AND book_id = ? AND status = 'borrowed'");
    $stmt->bind_param("ss", $student_id, $book_id);
    $stmt->execute();
    $borrow_result = $stmt->get_result();

    if ($borrow_result->num_rows == 0) {
        $_SESSION['error'] = "No matching borrowed record found!";
        header('location: return_book.php');
        exit();
    }

    $stmt = $conn->prepare("UPDATE borrow SET status = 'returned', return_date = NOW() WHERE student_id = ? AND book_id = ?");
    $stmt->bind_param("ss", $student_id, $book_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Book returned successfully!";
    } else {
        $_SESSION['error'] = "Failed to return book.";
    }

    header('location: return_list.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light" tabindex="0">

<div class="container mt-5">
    <h3>📦 Return Book</h3>
    <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" id="returnForm">
        <div class="mb-3">
            <label>Scan Student QR Code (Firstname Lastname):</label>
            <input type="text" name="student_name" id="student_name" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label>Scan Book QR Code (Book ID):</label>
            <input type="text" name="book_id" id="book_id" class="form-control" readonly>
        </div>
        <button class="btn btn-danger w-100" type="submit">🔄 Return</button>
    </form>
</div>

<script>
    let buffer = '';
    let lastTime = 0;
    let scanningStep = 1;

    document.addEventListener('keypress', function (e) {
        const now = new Date().getTime();
        const threshold = 100; // ms between characters

        if (now - lastTime > threshold) {
            buffer = '';
        }
        lastTime = now;

        // If Enter is pressed, process the buffer
        if (e.key === 'Enter') {
            buffer = buffer.trim();
            const studentInput = document.getElementById('student_name');
            const bookInput = document.getElementById('book_id');

            if (scanningStep === 1 && buffer && !studentInput.value) {
                studentInput.value = buffer;
                scanningStep = 2;
            } else if (scanningStep === 2 && buffer && !bookInput.value) {
                bookInput.value = buffer;

                // If both fields are filled, submit the form
                if (studentInput.value && bookInput.value) {
                    document.getElementById('returnForm').submit();
                }

                scanningStep = 1; // Reset step for next scan
            }

            buffer = ''; // Clear buffer after Enter
        } else {
            buffer += e.key;
        }
    });

    // Ensure the body can capture input even when not focused
    window.onload = () => {
        document.body.setAttribute('tabindex', '-1');
        document.body.focus();

        document.body.addEventListener('click', () => {
            document.body.focus();
        });
    };
</script>


</body>
</html>
