<?php
session_start();
include "../../database/db_connection.php";

// Handle delete user
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $deleteId");
    header("Location: admin-users.php");
    exit;
}

// Handle update username
if (isset($_POST['edit_user'])) {
    $userId = intval($_POST['user_id']);
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    mysqli_query($conn, "UPDATE users SET username = '$newUsername' WHERE id = $userId");
    header("Location: admin-users.php");
    exit;
}

// Get all users
$usersResult = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="/BlogLikho/styles/adminSidebar.css" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .container {
            margin-left: 250px;
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #1abc9c;
            color: white;
        }

        a.button {
            padding: 6px 10px;
            margin-right: 5px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        a.button.delete {
            background-color: #e74c3c;
        }

        form.inline-form {
            display: inline-block;
        }

        input[type="text"] {
            padding: 0;
            width: auto;
            margin-right: 10px;
            border: none;
            background: transparent;
            font-size: 16px;
            color: #2c3e50;
            font-family: inherit;
            outline: none;
        }


        input[type="submit"] {
            padding: 6px 10px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .container {
                margin-left: 0;
                padding: 15px;
            }

            table {
                font-size: 14px;
            }

            input[type="text"] {
                width: 80px;
            }
        }
    </style>
</head>

<body>
    <?php include "../../components/sidebar.php"; ?>

    <div class="container">
        <h1>Manage Users</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                    <tr>
                        <td>
                            <a href="admin-user-blogs.php?user_id=<?= $user['id']; ?>">
                                <?= $user['id']; ?>
                            </a>
                        </td>
                        <td>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>"
                                required>
                                <input type="submit" name="edit_user" value="Update">
                                <a href="admin-user-blogs.php?user_id=<?= $user['id']; ?>">
                                    blogs
                                </a>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td>
                            <a href="admin-users.php?delete_id=<?= $user['id']; ?>" class="button delete"
                                onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>