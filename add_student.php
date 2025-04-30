<?php
include 'db_connect.php';
require 'phpqrcode/qrlib.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $student_id = trim($_POST['student_id']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!empty($firstname) && !empty($lastname) && !empty($student_id) && !empty($course) && !empty($year_level) && !empty($email) && !empty($phone)) {
        // Data to be encoded in QR
        $student_data = "ID: $student_id | Name: $firstname $lastname | Course: $course | Year: $year_level | Email: $email | Phone: $phone";
        $qr_file = "qrcodes/students_" . uniqid() . ".png";

        QRcode::png($student_data, $qr_file, QR_ECLEVEL_L, 5);

        // ✅ Updated query with firstname and lastname
        $stmt = $conn->prepare("INSERT INTO student (student_id, firstname, lastname, course, year_level, email, phone, qr_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $student_id, $firstname, $lastname, $course, $year_level, $email, $phone, $qr_file);

        if ($stmt->execute()) {
            $_SESSION['success'] = "🎓 Student added successfully!";
        } else {
            $_SESSION['error'] = "⚠️ Failed to add student.";
        }
    } else {
        $_SESSION['error'] = "⚠️ All fields are required!";
    }

    header("Location: student_list.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>🎓 Add Student</title>
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
        <h2 class="text-center">🎓 Add New Student</h2>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php elseif (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">🆔 Student ID:</label>
        <input type="text" name="student_id" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">👤 Fisrt Name:</label>
        <input type="text" name="firstname" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">👤 Last Name:</label>
        <input type="text" name="lastname" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">🎓 Section:</label>
        <input type="text" name="course" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">📅 Year Level:</label>
        <select name="year_level" class="form-control" required>
            <option value="Grade 7">Grade 7</option>
            <option value="Grade 8">Grade 8</option>
            <option value="Grade 9">Grade 9</option>
            <option value="Grade 10">Grade 10</option>
            <option value="Grade 11">Grade 11</option>
            <option value="Grade 12">Grade 12</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">📧 Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">📞 Phone:</label>
        <input type="text" name="phone" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">➕ Add Student</button>
</form>
</div>
</div>

</body>
</html>