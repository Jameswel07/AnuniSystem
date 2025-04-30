<?php
include 'db_connect.php';

// Check if book_id is passed instead
if (!isset($_GET['book_id'])) {
    echo "Book ID not provided!";
    exit;
}

$book_id = (int) $_GET['book_id'];

// Fetch book by ID
$book = $conn->query("SELECT * FROM book WHERE book_id = $book_id")->fetch_assoc();

if (!$book) {
    echo "Book not found!";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Book QR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 40px;
        }

        h2 {
            margin-bottom: 10px;
        }

        .qr-container {
            margin-top: 20px;
        }

        img.qr {
            width: 300px;
            height: 300px;
            border: 1px solid #ccc;
        }

        .details {
            font-size: 18px;
            margin-top: 10px;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <h2><?= htmlspecialchars($book['title']) ?></h2>
    <div class="qr-container">
        <img class="qr" src="<?= htmlspecialchars($book['qr_code']) ?>" alt="Book QR Code">
        <div class="details">
            <strong>Author:</strong> <?= htmlspecialchars($book['author']) ?><br>
            <strong>Book ID:</strong> <?= htmlspecialchars($book['book_id']) ?>
        </div>
    </div>

</body>
</html>
