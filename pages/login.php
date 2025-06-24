<?php
include "../database/db_connection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Input fields are empty.";
    } else {
        $checkUser = "SELECT id, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkUser);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_bind_result($stmt, $user_id, $hashed_password);
                mysqli_stmt_fetch($stmt);

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $user_id;
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Invalid credentials!";
                }
            } else {
                $error = "No user found with that email!";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Database query failed.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/auth.css">
    <title>Document</title>
</head>

<body>

    <div class="form-container">
        <h2>Login</h2>
        <form method="POST">

            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="text-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

</body>

</html>