<?php
include 'db_connect.php';
session_start();
include 'phpqrcode/qrlib.php'; // Include QR code library

// Directory to store QR codes
$qr_dir = "qrcodes/";
if (!file_exists($qr_dir)) {
    mkdir($qr_dir, 0777, true);
}

// Pagination settings
$limit = 5; // Number of books per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch all unique categories
$category_query = "SELECT DISTINCT category FROM book ORDER BY category ASC";
$category_result = $conn->query($category_query);
$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Fetch books grouped by category with pagination and search
$book_by_category = [];
foreach ($categories as $category) {
    $query = "SELECT book.book_id, book.title, book.author, book.category, 
          CASE 
              WHEN EXISTS (SELECT 1 FROM borrow WHERE borrow.book_id = book.book_id AND borrow.status = 'borrowed') 
              THEN 'Borrowed' 
              ELSE 'Available' 
          END AS status
          FROM book
          WHERE (title LIKE ? OR author LIKE ? OR category LIKE ?) 
          AND category = ?
          ORDER BY title ASC
          LIMIT ? OFFSET ?";


    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bind_param("ssssii", $searchTerm, $searchTerm, $searchTerm, $category, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $book_by_category[$category] = $result->fetch_all(MYSQLI_ASSOC);
}

// Count total books for pagination
$countQuery = "SELECT COUNT(*) AS total FROM book WHERE title LIKE ? OR author LIKE ? OR category LIKE ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$countStmt->execute();
$countResult = $countStmt->get_result()->fetch_assoc();
$totalBooks = $countResult['total'];
$totalPages = ceil($totalBooks / $limit);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>📚 Library Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

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
    </style>
    <script>
        function printQR(qrPath) {
            var newWin = window.open("");
            newWin.document.write('<img src="' + qrPath + '" onload="window.print();window.close();">');
        }
    </script>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">📚 KNHS LS</h4>
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
            <h2>📋 Book List</h2>
            <a href="add_book.php" class="btn btn-success mb-3">➕ Add Book</a>
            <a href="borrow_list.php" class="btn btn-warning mb-3">📖 Borrowed Books</a>
            <a href="return_list.php" class="btn btn-danger mb-3">📖 Return Books</a>
            <a href="overdue_books.php" class="btn btn-primary mb-3">📖 Overdue Books</a>
            <a href="dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

            <form method="GET" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2"
                    placeholder="🔍 Search by Title, Author, or Category" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">🔍 Search</button>
            </form>

            <!-- Display Books by Category -->
            <?php foreach ($book_by_category as $category => $book): ?>
                <?php if (!empty($book)): ?>
                    <h3 class="bg-primary text-white p-2"><?= htmlspecialchars($category) ?></h3>
                    <table class="table table-bordered">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>🆔 Book ID</th> <!-- Added Book ID column -->
                                <th>📖 Title</th>
                                <th>✍ Author</th>
                                <th>📂 Category</th>
                                <th>📌 QR Code</th>
                                <th>📌 Status</th>
                                <th>🛠 Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($book as $book):
                                $book_id = $book['book_id'];
                                $qr_file = $qr_dir . "book_" . $book_id . ".png";

                                // Generate QR code if not exists
                                if (!file_exists($qr_file)) {
                                    QRcode::png($book['book_id'], $qr_file, QR_ECLEVEL_L, 4);
                                }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($book['book_id']); ?></td> <!-- Display Book ID -->
                                    <td><?= htmlspecialchars($book['title']); ?></td>
                                    <td><?= htmlspecialchars($book['author']); ?></td>
                                    <td><?= htmlspecialchars($book['category']); ?></td>
                                    <td>
                                        <img src="<?= $qr_file; ?>" width="80">
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= ($book['status'] == 'Borrowed') ? 'danger' : 'success' ?>">
                                            <?= $book['status'] ?>
                                        </span>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                📚 Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="edit_books.php?id=<?= $book['book_id'] ?>"><i
                                                            class="fas fa-edit"></i> ✏️ Edit</a></li>
                                                <li><a class="dropdown-item" href="delete_book.php?id=<?= $book['book_id'] ?>"
                                                        onclick="return confirm('Are you sure you want to delete this book?');"><i
                                                            class="fas fa-trash"></i> 🗑 Delete</a></li>
                                                <li><a class="dropdown-item"
                                                href="download_qr_book.php?book_id=<?= $book['book_id']; ?>" class="btn btn-sm btn-success">
                                                📥 Download QR</a></li>
                                                <li><a class="dropdown-item"  href="print_book.php?book_id=3" target="_blank">🖨 Print QR</a>
                                                t</a></li>
                                            </ul>
                                        </div>
                                    </td>


                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endforeach; ?>

            <!-- Pagination -->
            <nav>
                <ul class="pagination">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1; ?>&search=<?= urlencode($search); ?>">⬅
                            Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1; ?>&search=<?= urlencode($search); ?>">Next ➡</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</body>

</html>