<?php
include 'db_connect.php';
session_start();

// Set the number of records per page
$records_per_page = 10;

// Get the current page number from the URL (default to 1 if not set)
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Fetch students grouped by year level with borrow status, limited to the current page
$query = "
    SELECT 
        s.student_id, s.firstname, s.lastname, s.course, s.year_level, 
        s.email, s.phone, s.qr_code,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM borrow b 
                WHERE b.student_id = s.student_id AND b.status = 'borrowed'
            ) THEN 'Has Borrowed Books'
            ELSE 'No Borrowed Books'
        END AS status
    FROM student s
    ORDER BY s.year_level
    LIMIT $start_from, $records_per_page
";

$result = $conn->query($query);

$student_by_year = [];
while ($row = $result->fetch_assoc()) {
    $student_by_year[$row['year_level']][] = $row;
}

// Fetch the total number of students to calculate total pages
$total_students_query = "
SELECT COUNT(DISTINCT s.student_id) AS total_students
FROM student s
LEFT JOIN borrow b ON s.student_id = b.student_id AND b.status = 'borrowed'
";
$total_result = $conn->query($total_students_query);
$total_students = $total_result->fetch_assoc()['total_students'];
$total_pages = ceil($total_students / $records_per_page);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>🎓 Student List</title>
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

        .card {
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .qr-code {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center">📚 KNHS LS</h4>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="book.php">📖 Manage Books</a>
        <a href="student_list.php" class="active">🎓 Manage Students</a>
        <a href="borrow_book.php">📥 Borrow Books</a>
        <a href="return_book.php">📤 Return Books</a>
        <a href="about.php">ℹ️ About Us</a>
        <a href="logout.php" class="text-danger">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <h2 class="text-left-side">🎓 Student List</h2>
            <a href="add_student.php" class="btn btn-success mb-3">➕ Add Student</a>
            <a href="dashboard.php" class="btn btn-dark mb-3">⬅ Back</a>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']); ?>
                </div>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php foreach ($student_by_year as $year_level => $students): ?>
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><?= htmlspecialchars($year_level) ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>🆔 Student ID</th>
                                        <th>👤 Full Name</th>
                                        <th>🎓 Course</th>
                                        <th>📅 Year Level</th>
                                        <th>📧 Email</th>
                                        <th>📞 Phone</th>
                                        <th>📷 QR Code</th>
                                        <th>📦 Status</th>
                                        <th>📚 Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($student['student_id']) ?></td>
                                            <td><?= htmlspecialchars($student['firstname']) . ' ' . htmlspecialchars($student['lastname']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($student['course']) ?></td>
                                            <td><?= htmlspecialchars($student['year_level']) ?></td>
                                            <td><?= htmlspecialchars($student['email']) ?></td>
                                            <td><?= htmlspecialchars($student['phone']) ?></td>
                                            <td>
                                                <img src="<?= htmlspecialchars($student['qr_code']) ?>" alt="QR Code"
                                                    class="qr-code">
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-<?= ($student['status'] == 'Has Borrowed Books') ? 'warning' : 'success' ?>">
                                                    <?= $student['status'] ?>
                                                </span>
                                            </td>

                                            <td>
                                                <!-- Dropdown Button for Multiple Actions -->
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-cogs"></i> 📚 Actions
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <!-- Edit Option -->
                                                        <li><a class="dropdown-item"
                                                                href="edit_student.php?id=<?= $student['student_id']; ?>"><i
                                                                    class="fas fa-edit"></i> Edit</a></li>

                                                        <!-- Delete Option -->
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="delete_student.php?id=<?= urlencode($student['student_id']); ?>"
                                                                onclick="return confirm('Are you sure you want to delete this student?')">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </a>
                                                        </li>

                                                        <!-- Print Option -->
                                                        <li><a class="dropdown-item"
                                                                href="print_student.php?id=<?= $student['student_id']; ?>"><i
                                                                    class="fas fa-print"></i> Print</a></li>

                                                        <!-- Download QR Option -->
                                                        <li><a class="dropdown-item"
                                                                href="download_qr.php?file=<?= urlencode($student['qr_code']); ?>&id=<?= $student['student_id']; ?>&type=student"><i
                                                                    class="fas fa-download"></i> Download QR</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination Controls -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page == 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>"><a class="page-link"
                                href="?page=<?= $i; ?>"><?= $i; ?></a></li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($page == $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</body>

</html>