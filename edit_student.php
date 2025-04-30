<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM student WHERE student_id = '$id'");
    $student = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];

    $conn->query("UPDATE student SET firstname='$firstname', lastname='$lastname', email='$email', phone='$phone', course='$course', year_level='$year_level' WHERE student_id='$id'");

    header("Location: student_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Edit Student</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $student['student_id']; ?>">

        <label>First Name:</label>
        <input type="text" name="firstname" value="<?= $student['firstname']; ?>" class="form-control" required>

        <label>Last Name:</label>
        <input type="text" name="lastname" value="<?= $student['lastname']; ?>" class="form-control" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $student['email']; ?>" class="form-control" required>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?= $student['phone']; ?>" class="form-control" required>

        <label>Course:</label>
        <input type="text" name="course" value="<?= $student['course']; ?>" class="form-control" required>

        <label>Year Level:</label>
        <select name="year_level" class="form-control" required>
            <option value="" disabled>Select year level</option>
            <option value="Grade 7" <?= $student['year_level'] == 'Grade 7' ? 'selected' : '' ?>>Grade 7</option>
            <option value="Grade 8" <?= $student['year_level'] == 'Grade 8' ? 'selected' : '' ?>>Grade 8</option>
            <option value="Grade 9" <?= $student['year_level'] == 'Grade 9' ? 'selected' : '' ?>>Grade 9</option>
            <option value="Grade 10" <?= $student['year_level'] == 'Grade 10' ? 'selected' : '' ?>>Grade 10</option>
            <option value="Grade 11" <?= $student['year_level'] == 'Grade 10' ? 'selected' : '' ?>>Grade 11</option>
            <option value="Grade 12" <?= $student['year_level'] == 'Grade 10' ? 'selected' : '' ?>>Grade 12</option>
        </select>

        <button type="submit" name="update" class="btn btn-success mt-3">✔ Update Student</button>
        <a href="student_list.php" class="btn btn-secondary mt-3">🔙 Back</a>
    </form>
</body>
</html>
