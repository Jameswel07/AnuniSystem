<?php
include 'db_connect.php';
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch books with availability status
$query = "
    SELECT 
        b.book_id, b.title, b.author, b.category,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM borrow br WHERE br.book_id = b.book_id AND br.status = 'borrowed'
            ) THEN 'Borrowed'
            ELSE 'Available'
        END AS availability
    FROM book b
    ORDER BY b.category, b.title
    LIMIT $offset, $limit
";
$result = $conn->query($query);

// Count total
$total_query = "SELECT COUNT(*) AS total FROM book";
$total_result = $conn->query($total_query);
$total_book = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_book / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Book Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding-top: 30px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-success {
            background-color: #28a745;
        }
        .btn {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">📚 Book Catalog</h2>

    <!-- Add Book Button -->
    <a href="add_book.php" class="btn btn-success mb-3">➕ Add Book</a>

    <!-- Book Table Card -->
    <div class="card">
        <div class="card-header">
            Book List
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['book_id'] ?></td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['author'] ?></td>
                        <td><?= $row['category'] ?></td>
                        <td><span class="badge <?= $row['availability'] == 'Borrowed' ? 'badge-danger' : 'badge-success' ?>">
                            <?= $row['availability'] ?>
                        </span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page == $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
