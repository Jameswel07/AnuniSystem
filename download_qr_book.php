<?php
include 'db_connect.php';
require_once 'phpqrcode/qrlib.php'; // Make sure this path is correct

if (!isset($_GET['book_id'])) {
    die("Book ID is required.");
}

$book_id = $_GET['book_id'];

// Fetch book details
$stmt = $conn->prepare("SELECT * FROM book WHERE book_id = ?");
$stmt->bind_param("s", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Book not found.");
}

$book = $result->fetch_assoc();

// QR content can be just book_id or book title
$qrText = $book['book_id'];

// Create temp file for QR
$tempDir = sys_get_temp_dir();
$filename = $tempDir . '/book_qr_' . $book_id . '.png';

// Generate QR
QRcode::png($qrText, $filename, QR_ECLEVEL_H, 6);

// Force download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="book_qr_' . $book_id . '.png"');
readfile($filename);
unlink($filename); // clean up temp file
exit();
?>
