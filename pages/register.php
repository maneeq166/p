<?php include "../database/db_connection.php"; ?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $checkEmail = "SELECT * FROM users WHERE email=?";

    $checkStmt = mysqli_prepare($conn, $checkEmail);

    if ($checkStmt) {
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $error = "Email is already registered";
            mysqli_stmt_close($checkStmt);
        } else {
            $error = "";
            mysqli_stmt_close($checkStmt);

            // Continue registration
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
            mysqli_stmt_execute($stmt);

            // Redirect to login page
            header("Location: login.php");
            exit;
        }

        mysqli_close($conn);
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
        <h2>Register</h2>
        <form method="POST">

            <label for="username">Username</label>
            <input type="text" name="username" required>

            <div style="display:flex;align-items:baseline;justify-content:space-between;">
                <label for="email">Email</label>
                <?php if (!empty($error))
                    echo "<p style='color:red; margin:4px 0 0;'>$error</p>"; ?>
            </div>

            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn">Register</button>
        </form>

        <div class="text-link">
            <p>Have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>


</body>

</html>