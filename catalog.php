<?php
session_start();
include 'db_connect.php';

// Fetch all unique shelves
$shelf_query = "SELECT DISTINCT shelf_name FROM shelves ORDER BY shelf_name ASC";
$shelf_result = $conn->query($shelf_query);
$shelves = [];
while ($row = $shelf_result->fetch_assoc()) {
    $shelves[] = $row['shelf_name'];
}

// Fetch book details if QR code is scanned
$scanned_book = null;
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $book_query = $conn->prepare("SELECT book.*, shelves.shelf_name 
                                  FROM book 
                                  LEFT JOIN shelves ON book.shelf_id = shelves.shelf_id 
                                  WHERE book.book_id = ?");
    $book_query->bind_param("s", $book_id);
    $book_query->execute();
    $result = $book_query->get_result();
    $scanned_book = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>📚 Library Catalog with Shelves</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>📚 Library Catalog</h2>
    <a href="book.php" class="btn btn-success mb-3">⬅ Back</a>

    <!-- QR Code Scanner Input -->
    <div class="mb-4">
        <label class="form-label">📷 Scan QR Code:</label>
        <input type="text" id="qr_code" class="form-control" placeholder="Scan book QR code..." autofocus>
    </div>

    <!-- Auto-Filled Book Details -->
    <div id="book-details" class="card p-3 <?= $scanned_book ? '' : 'd-none' ?>">
        <h4>📖 Book Details</h4>
        <p><strong>Title:</strong> <span id="book-title"><?= $scanned_book['title'] ?? '' ?></span></p>
        <p><strong>Author:</strong> <span id="book-author"><?= $scanned_book['author'] ?? '' ?></span></p>
        <p><strong>Category:</strong> <span id="book-category"><?= $scanned_book['category'] ?? '' ?></span></p>
        <p><strong>Shelf:</strong> <span id="book-shelf"><?= $scanned_book['shelf_name'] ?? 'Not Assigned' ?></span></p>
        <p><strong>Status:</strong> 
            <span id="book-status" class="badge bg-<?= ($scanned_book && $scanned_book['status'] == 'Borrowed') ? 'danger' : 'success' ?>">
                <?= $scanned_book['status'] ?? '' ?>
            </span>
        </p>
    </div>

    <!-- Filter by Shelf -->
    <div class="mb-3">
        <label for="shelfFilter" class="form-label">📚 Filter by Shelf:</label>
        <select id="shelfFilter" class="form-select">
            <option value="">All Shelves</option>
            <?php foreach ($shelves as $shelf): ?>
                <option value="<?= htmlspecialchars($shelf) ?>"><?= htmlspecialchars($shelf) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Book List by Shelf -->
    <?php foreach ($shelves as $shelf): ?>
        <h3 class="bg-primary text-white p-2"><?= htmlspecialchars($shelf) ?></h3>
        <table class="table table-bordered book-table" data-shelf="<?= htmlspecialchars($shelf) ?>">
            <thead>
                <tr>
                    <th>📖 Title</th>
                    <th>✍️ Author</th>
                    <th>📂 Category</th>
                    <th>📦 Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT book.*, 
                                 (SELECT COUNT(*) FROM borrow WHERE borrow.book_id = book.book_id AND status = 'borrowed') AS borrowed,
                                 shelves.shelf_name
                          FROM book
                          LEFT JOIN shelves ON book.shelf_id = shelves.shelf_id
                          WHERE shelves.shelf_name = ?
                          LIMIT 10";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $shelf);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['author']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td>
                            <span class="badge bg-<?= ($row['borrowed'] > 0) ? 'danger' : 'success' ?>">
                                <?= ($row['borrowed'] > 0) ? 'Borrowed' : 'Available' ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>

<script>
document.getElementById('qr_code').addEventListener('input', function () {
    let bookId = this.value.trim();
    if (bookId) {
        window.location.href = "?book_id=" + bookId;
    }
});

// Filter books by shelf
document.getElementById('shelfFilter').addEventListener('change', function () {
    let selectedShelf = this.value;
    document.querySelectorAll('.book-table').forEach(table => {
        if (selectedShelf === "" || table.getAttribute('data-shelf') === selectedShelf) {
            table.style.display = "table";
        } else {
            table.style.display = "none";
        }
    });
});
</script>

</body>
</html>
