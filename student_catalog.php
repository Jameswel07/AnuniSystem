<?php
session_start();
include 'db_connect.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch total number of students
$total_query = "SELECT COUNT(*) AS total FROM student";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_students = $total_row['total'];
$total_pages = ceil($total_students / $limit);

// ✅ Fetch students with their borrow status
$query = "
    SELECT s.id AS student_id, s.firstname, s.lastname, s.course,
           CASE 
               WHEN EXISTS (
                   SELECT 1 FROM borrow 
                   WHERE borrow.student_id = s.id AND borrow.status = 'borrowed'
               ) 
               THEN 'Has Borrowed Books'
               ELSE 'No Borrowed Books'
           END AS status
    FROM student s
    ORDER BY status DESC
    LIMIT $limit OFFSET $offset
";

$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Borrowing Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>👨‍🎓 Student Borrowing Status</h2>
    <a href="student_list.php" class="btn btn-secondary mb-3">⬅ Back</a>

    <!-- Search Bar -->
    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="🔍 Search Student ID, Name, or Course">
    </div>

    <!-- Student Table -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>🆔 Student ID</th>
                <th>👤 Full Name</th>
                <th>📚 Courses</th>
                <th>📦 Status</th>
            </tr>
        </thead>
        <tbody id="student-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td>
                        <span class="badge bg-<?= ($row['status'] == 'Has Borrowed Books') ? 'warning' : 'success' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <nav>
        <ul class="pagination">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>">⬅ Previous</a>
            </li>
            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>">Next ➡</a>
            </li>
        </ul>
    </nav>
</div>

<!-- Live Search Script -->
<script>
document.getElementById('search').addEventListener('keyup', function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#student-list tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>
