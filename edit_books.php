<?php
include 'db_connect.php';
session_start();

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM book WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);
    $stmt = $conn->prepare("UPDATE book SET title=?, author=?, category=? WHERE book_id=?");
    $stmt->bind_param("sssi", $title, $author, $category, $book_id);
    $stmt->execute();
    $_SESSION['success'] = "Book updated successfully!";
    header("Location: book.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>✏ Edit Book</h2>
    <form method="POST">
        <label>📖 Book Title:</label>
        <input type="text" name="title" class="form-control" value="<?= $book['title']; ?>" required>
        <label class="mt-2">✍ Author:</label>
        <input type="text" name="author" class="form-control" value="<?= $book['author']; ?>" required>
        <label class="mt-2">📂 Category:</label>
        <select name="category" class="form-control">
            <option value="Filipino" <?= $book['category'] == 'Filipino' ? 'selected' : ''; ?>>Filipino</option>
            <option value="English" <?= $book['category'] == 'English' ? 'selected' : ''; ?>>English</option>
        </select>
        <button type="submit" class="btn btn-primary mt-3">✅ Update</button>
    </form>
</div>
</body>
</html>
