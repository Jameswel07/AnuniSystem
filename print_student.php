<?php 
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $student = $conn->query("SELECT * FROM student WHERE student_id = $id")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print QR Code</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
        }
        img {
            width: 300px;
            height: 300px;
        }
        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php if (!empty($student)): ?>
        <h4><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h4>
        <img src="<?= htmlspecialchars($student['qr_code']) ?>" alt="Student QR Code">
        <button onclick="window.print()" class="btn btn-primary mt-3">🖨 Print QR Code</button>
    <?php else: ?>
        <p>QR Code not found!</p>
    <?php endif; ?>
</body>
</html>
