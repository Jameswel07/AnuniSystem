
<?php
include 'db_connect.php';

// Total books and students
$total_book = $conn->query("SELECT COUNT(*) as count FROM book")->fetch_assoc()['count'];
$total_student = $conn->query("SELECT COUNT(*) as count FROM student")->fetch_assoc()['count'];

// Books by category
$book_query = $conn->query("SELECT * FROM book ORDER BY category, title");
$books_by_category = [];
while ($row = $book_query->fetch_assoc()) {
    $books_by_category[$row['category']][] = $row;
}

// Students by year level with borrow/return
$student_query = $conn->query("
    SELECT s.student_id, s.firstname, s.lastname, s.year_level,
        SUM(CASE WHEN b.status = 'borrowed' THEN 1 ELSE 0 END) AS borrowed,
        SUM(CASE WHEN b.status = 'returned' THEN 1 ELSE 0 END) AS returned
    FROM student s
    LEFT JOIN borrow b ON s.student_id = b.student_id
    GROUP BY s.student_id
    ORDER BY s.year_level, s.lastname
");
$students_by_year = [];
while ($row = $student_query->fetch_assoc()) {
    $students_by_year[$row['year_level']][] = $row;
}

// Missing books
$missing_books = $conn->query("SELECT * FROM book WHERE status = 'missing'")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>📊 Library Inventory Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
    <h3 class="mb-4 text-center">📊 Library Inventory Report</h3>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-center border-primary shadow-sm">
                <div class="card-body">
                    <h5>Total Books</h5>
                    <h2><?= $total_book ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center border-success shadow-sm">
                <div class="card-body">
                    <h5>Total Students</h5>
                    <h2><?= $total_student ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#books">📚 Books</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#students">👩‍🎓 Students</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#missing">🚫 Missing Books</a></li>
    </ul>

    <div class="tab-content">
        <!-- Books -->
        <div class="tab-pane fade show active" id="books">
            <?php foreach ($books_by_category as $category => $books): ?>
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white"><?= htmlspecialchars($category) ?></div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr><th>Title</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($books as $book): ?>
                                    <tr>
    <td><?= htmlspecialchars($book['title']) ?></td>
    <td>
        <span class="badge bg-<?= $book['status'] === 'available' ? 'success' : ($book['status'] === 'borrowed' ? 'warning' : 'danger') ?>">
            <?= ucfirst($book['status']) ?>
        </span>
        <?php if ($book['status'] !== 'available'): ?>
            <button class="btn btn-sm btn-outline-info ms-2" 
                    data-bs-toggle="modal" 
                    data-bs-target="#borrowersModal" 
                    data-book-id="<?= $book['book_id'] ?>" 
                    data-book-title="<?= htmlspecialchars($book['title']) ?>">
                View Borrowers
            </button>
        <?php endif; ?>
    </td>
</tr>

                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <!-- Students -->
        <div class="tab-pane fade" id="students">
            <?php foreach ($students_by_year as $grade => $students): ?>
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">Year Level: <?= htmlspecialchars($grade) ?></div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-hover mb-0">
                            <thead><tr><th>Name</th><th>Borrowed</th><th>Returned</th></tr></thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= $student['firstname'] . ' ' . $student['lastname'] ?></td>
                                        <td><?= $student['borrowed'] ?? 0 ?></td>
                                        <td><?= $student['returned'] ?? 0 ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <!-- Missing Books -->
        <div class="tab-pane fade" id="missing">
            <div class="card">
                <div class="card-header bg-danger text-white">🚫 Missing Books</div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead><tr><th>Title</th><th>Author</th><th>Category</th></tr></thead>
                        <tbody>
                            <?php if (count($missing_books)): ?>
                                <?php foreach ($missing_books as $book): ?>
                                    <tr>
                                        <td><?= $book['title'] ?></td>
                                        <td><?= $book['author'] ?></td>
                                        <td><?= $book['category'] ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center">No missing books.</td></tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="borrowersModal" tabindex="-1" aria-labelledby="borrowersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Borrowers of <span id="modalBookTitle" class="text-primary"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <thead>
            <tr><th>Student Name</th><th>Borrowed On</th><th>Status</th></tr>
          </thead>
          <tbody id="borrowersList">
            <tr><td colspan="3" class="text-center">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('borrowersModal').addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var bookId = button.getAttribute('data-book-id');
    var bookTitle = button.getAttribute('data-book-title');

    document.getElementById('modalBookTitle').textContent = bookTitle;
    var borrowersList = document.getElementById('borrowersList');
    borrowersList.innerHTML = '<tr><td colspan="3" class="text-center">Loading...</td></tr>';

    fetch('fetch_borrowers.php?book_id=' + bookId)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                borrowersList.innerHTML = '';
                data.forEach(row => {
                    borrowersList.innerHTML += `
                        <tr>
                            <td>${row.firstname} ${row.lastname}</td>
                            <td>${row.borrow_date}</td>
                            <td><span class="badge bg-${row.status === 'returned' ? 'success' : 'warning'}">${row.status}</span></td>
                        </tr>`;
                });
            } else {
                borrowersList.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No students have borrowed this book.</td></tr>';
            }
        })
        .catch(err => {
            borrowersList.innerHTML = '<tr><td colspan="3" class="text-danger text-center">Error loading data.</td></tr>';
        });
});
</script>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
