<?php
session_start();
include 'db_connect.php'; // Connect to database

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        if (md5($password) === $row['password']) { // Password check
            $_SESSION['admin'] = $row['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password!";
        }
    } else {
        $_SESSION['error'] = "Username not found!";
    }
}

header("Location: login.php");
exit();
?>
