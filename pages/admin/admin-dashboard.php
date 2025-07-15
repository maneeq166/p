<?php
session_start();
include "../../database/db_connection.php";

$adminId = $_SESSION['admin_id'] ?? null;

if (!$adminId) {
    header("Location: login.php"); // or your login path
    exit;
}

// Get admin details from database
$adminQuery = "SELECT username, email FROM admin WHERE id = $adminId";
$adminResult = mysqli_query($conn, $adminQuery);
$adminData = mysqli_fetch_assoc($adminResult);

$adminName = $adminData['username'];
$adminEmail = $adminData['email'];


// Count users
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = mysqli_query($conn, $userQuery);
$userData = mysqli_fetch_assoc($userResult);

// Count blogs
$blogQuery = "SELECT COUNT(*) AS total_blogs FROM posts";
$blogResult = mysqli_query($conn, $blogQuery);
$blogData = mysqli_fetch_assoc($blogResult);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/BlogLikho/styles/adminSidebar.css" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .dashboard-container {
            margin-left:300px;
            padding: 30px;
        }

        .dashboard-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #ffffff;
            border-left: 5px solid #1abc9c;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 250px;
        }

        .card h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #34495e;
        }

        .card p {
            font-size: 16px;
            color: #2c3e50;
            margin: 0;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin-left: 0;
                padding: 15px;
            }

            .card {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include "../../components/sidebar.php"; ?>

    <div class="dashboard-container">
        <div class="dashboard-header">Welcome to Admin Dashboard</div>
        <p><strong>Name:</strong> <?= htmlspecialchars($adminName); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($adminEmail); ?></p>

        <div class="card-container">
            <div class="card">
                <h2>Total Users</h2>
                <p><?= $userData['total_users']; ?></p>
            </div>

            <div class="card">
                <h2>Total Blogs</h2>
                <p><?= $blogData['total_blogs']; ?></p>
            </div>





        </div>
    </div>
</body>

</html>