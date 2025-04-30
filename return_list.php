<?php
include 'db_connect.php';

$search = $_GET['search'] ?? '';
$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

// Count total returned books
$countSql = "
    SELECT COUNT(*) as total 
    FROM borrow b
    JOIN student s ON b.student_id = s.student_id
    JOIN book bk ON b.book_id = bk.book_id
    WHERE b.status = 'returned'
";
if (!empty($search)) {
    $countSql .= " AND (s.firstname LIKE '%$search%' OR s.lastname LIKE '%$search%' OR bk.title LIKE '%$search%')";
}
$totalResult = $conn->query($countSql);
$totalRecords = (int) $totalResult->fetch_assoc()['total'];
$totalPages = max(1, ceil($totalRecords / $limit));
if ($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $limit;

// Fetch paginated records
$sql = "
    SELECT b.borrow_id, s.firstname, s.lastname, bk.title, b.return_date
    FROM borrow b
    JOIN student s ON b.student_id = s.student_id
    JOIN book bk ON b.book_id = bk.book_id
    WHERE b.status = 'returned'
";
if (!empty($search)) {
    $sql .= " AND (s.firstname LIKE '%$search%' OR s.lastname LIKE '%$search%' OR bk.title LIKE '%$search%')";
}
$sql .= " ORDER BY b.return_date DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Returned Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>📄 Returned Book List</h3>
    <a href="book.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <!-- 🔍 Search Bar -->
    <form class="d-flex mb-3" method="GET" action="">
        <input class="form-control me-2" type="search" name="search" placeholder="Search by student or book title..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>

    <!-- 📊 Table -->
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Book Title</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): $i = $offset + 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['return_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">No returned books found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- 🔢 Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <!-- Previous -->
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                </li>

                <!-- Page Numbers -->
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $p ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next -->
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>
</body>
</html>
