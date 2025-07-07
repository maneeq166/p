<?php 
include "../../database/db_connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $admin_email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($admin_email) || empty($password)) {
        $error = "Email and Password can't be empty.";
    } else {
        $checkAdmin = "SELECT id, password FROM admin WHERE email = ?";
        $stmt = mysqli_prepare($conn, $checkAdmin);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $admin_email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_bind_result($stmt, $admin_id, $hashed_password);
                mysqli_stmt_fetch($stmt);

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['admin_id'] = $admin_id;
                    header("Location: admin-dashboard.php");
                    exit;
                } else {
                    $error = "Invalid credentials!";
                }
            } else {
                $error = "Admin not found.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/auth.css">
    <title>Document</title>
</head>

<body>

    <div class="form-container">
        <h2>Login</h2>
        <form method="POST">

            <div style="display:flex;align-items:baseline;justify-content:space-between;">
                <label for="email">Email</label>
                <?php if (!empty($error))
                    echo "<p style='color:red; margin:4px 0 0;'>$error</p>"; ?>
            </div>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn">Login</button>
            
        </form>

    </div>

</body>

</html>