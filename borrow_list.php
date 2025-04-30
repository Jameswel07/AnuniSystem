<?php 
include 'db_connect.php';

$search = $_GET['search'] ?? '';

// Set number of records per page
$records_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Count total filtered records
$count_query = "
    SELECT COUNT(*) AS total 
    FROM borrow b 
    JOIN student s ON b.student_id = s.student_id 
    JOIN book bk ON b.book_id = bk.book_id
    WHERE s.firstname LIKE '%$search%' 
       OR s.lastname LIKE '%$search%'
       OR bk.title LIKE '%$search%'
";
$total_result = $conn->query($count_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch current page records with search
$query = "
    SELECT b.borrow_id, s.firstname, s.lastname, bk.title, b.borrow_date, b.due_date, b.status
    FROM borrow b
    JOIN student s ON b.student_id = s.student_id
    JOIN book bk ON b.book_id = bk.book_id
    WHERE s.firstname LIKE '%$search%' 
       OR s.lastname LIKE '%$search%'
       OR bk.title LIKE '%$search%'
    ORDER BY b.borrow_date DESC
    LIMIT $start_from, $records_per_page
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrowed Book List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>📋 Borrowed Book List</h3>
    <a href="book.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <!-- Search Bar -->
    <form method="GET" class="d-flex mb-3">
        <input type="text" name="search" class="form-control me-2" placeholder="Search student or book title..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>Student Name</th>
                <th>Book Title</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= date('M d, Y', strtotime($row['borrow_date'])); ?></td>
                        <td><?= date('M d, Y', strtotime($row['due_date'])); ?></td>
                        <td>
                            <span class="badge <?= $row['status'] === 'borrowed' ? 'bg-warning' : 'bg-success'; ?>">
                                <?= ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="return_book.php?student=<?= urlencode($row['firstname'] . ' ' . $row['lastname']); ?>&book_id=<?= urlencode($row['borrow_id']); ?>" class="btn btn-sm btn-danger ms-2">
                                Return
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No borrowed books found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>
</body>
</html>
