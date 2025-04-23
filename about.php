<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📚 About Us - KNHS Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f4f4;
        }
        .about-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin-top: 60px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="about-container">
        <h1 class="text-center mb-4">📖 About KNHS Library System</h1>
        
        <p>
            Welcome to the <strong>KNHS Library Management System</strong>! This system is developed to help manage books, students, and borrowing/returning activities efficiently.
        </p>

        <h4 class="mt-4">🎯 Our Mission</h4>
        <p>
            To promote a culture of reading and learning by providing fast, reliable, and accessible library services to all students and faculty of Kabankalan National High School.
        </p>

        <h4 class="mt-4">🛠️ System Purpose</h4>
        <p>
            This system automates library tasks such as book cataloging, student management, book borrowing, and returning using the scanner can scan Qr Code. It enhances the speed and accuracy of library transactions, reduces manual work, and ensures proper book tracking.
        </p>

        <h4 class="mt-4">📍 Location</h4>
        <p>
            KNHS Library, Kabankalan City, Negros Occidental, Philippines.
        </p>

        <h4 class="mt-4">📞 Contact Us</h4>
        <p>
            Email: <a href="mailto:library@knhs.edu.ph">library@knhs.edu.ph</a><br>
            Phone: (034) 123-4567
        </p>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-primary">⬅️ Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
