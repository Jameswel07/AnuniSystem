<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password!";
        header("Location: login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KNHS Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Full background with image and overlay */
        body {
            background: url('aha.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Dark overlay to improve contrast */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4); /* dark overlay */
            backdrop-filter: blur(4px);
            z-index: 1;
        }

        /* Centered form container */
        .login-container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(18px);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 400px;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
            color: white;
        }

        .login-container h2 {
            font-weight: bold;
            margin-bottom: 25px;
        }

        .form-label {
            color: #fff;
            font-weight: 500;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
        }

        .form-control::placeholder {
            color: #ddd;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: 600;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            background: rgba(255, 0, 0, 0.2);
            color: #fff;
            border: 1px solid red;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>KNHS LOGIN</h2>


        <form action="check_login.php" method="POST">
            <div class="form-group mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="form-group mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

</body>
</html>
