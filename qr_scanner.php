<?php
session_start();
include 'db_connect.php';

if (isset($_POST['qr_code'])) {
    $qr_code = $_POST['qr_code'];

    // Fetch book details based on QR code
    $stmt = $conn->prepare("SELECT book.*, shelves.name AS shelf_name FROM book 
                            LEFT JOIN shelves ON book.shelf_id = shelves.id 
                            WHERE book.book_id = ?");
    $stmt->bind_param("s", $qr_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        echo json_encode($book);
    } else {
        echo json_encode(["error" => "Book not found"]);
    }
}
?>

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>QR Code Scanner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>📷 QR Code Scanner (GOOJPRT)</h2>

        <input type="text" id="scanned_data" class="form-control mt-3" placeholder="Scan QR Code here..." autofocus>
        
        <button id="borrow" class="btn btn-primary mt-3">📖 Borrow Book</button>
        <button id="return" class="btn btn-success mt-3">📚 Return Book</button>

        <div id="scanResult" class="alert alert-info mt-3" style="display: none;"></div>
    </div>

    <script>
document.getElementById('qr_input').addEventListener('change', function() {
    let qrCode = this.value;

    fetch('scan_qr.php', {
        method: 'POST',
        body: new URLSearchParams({ qr_code: qrCode }),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            document.getElementById('book_title').value = data.title;
            document.getElementById('book_author').value = data.author;
            document.getElementById('book_category').value = data.category;
            document.getElementById('book_shelf').value = data.shelf_name;
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>

</body>
</html>
