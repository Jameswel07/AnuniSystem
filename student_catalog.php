<?php
include 'db_connect.php';
$records_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Fetch student list with borrow status
$query = "
    SELECT 
        s.student_id, s.firstname, s.lastname, s.year_level, s.course,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM borrow b WHERE b.student_id = s.student_id AND b.status = 'borrowed'
            ) THEN 'Borrowed'
            ELSE 'Returned'
        END AS status
    FROM student s
    ORDER BY s.year_level, s.lastname
    LIMIT $start_from, $records_per_page
";
$result = $conn->query($query);

// Get total
$total_query = "SELECT COUNT(*) AS total FROM student";
$total_result = $conn->query($total_query);
$total_students = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_students / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎓 Student Catalog</title>
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
        .badge-warning {
            background-color: #ffc107;
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
    <h2 class="text-center mb-4">🎓 Student Catalog</h2>

    <!-- Add Student Button -->
    <a href="add_student.php" class="btn btn-success mb-3">➕ Add Student</a>

    <!-- Student Table Card -->
    <div class="card">
        <div class="card-header">
            Student List
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['student_id'] ?></td>
                        <td><?= $row['firstname'] . ' ' . $row['lastname'] ?></td>
                        <td><?= $row['course'] ?></td>
                        <td><?= $row['year_level'] ?></td>
                        <td><span class="badge <?= $row['status'] == 'Borrowed' ? 'badge-warning' : 'badge-success' ?>">
                            <?= $row['status'] ?>
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
