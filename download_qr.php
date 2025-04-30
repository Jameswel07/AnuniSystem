<?php
include 'db_connect.php';
include 'phpqrcode/qrlib.php'; // Adjust this if your path is different
session_start();

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Fetch student information
    $stmt = $conn->prepare("SELECT firstname, lastname FROM student WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($student) {
        $fullname = $student['firstname'] . ' ' . $student['lastname'];

        // Generate QR Code
        $qr_dir = 'qr_codes/';
        if (!file_exists($qr_dir)) {
            mkdir($qr_dir, 0777, true);
        }

        $filename = $qr_dir . 'student_' . $student_id . '.png';
        QRcode::png($fullname, $filename, 'L', 6, 2);

        // Send QR code file to browser for download
        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
        } else {
            echo "❗ QR code file could not be generated.";
        }
    } else {
        echo "❗ Student not found.";
    }
} else {
    echo "❗ No student ID specified.";
}
?>
