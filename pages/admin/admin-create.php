<?php
include "../database/db_connection.php";
require_once "../env/loadenv.php";
loadEnv('C:/Nxamp/htdocs/BlogLikho/env/.env');


$admin_username=$_ENV["ADMIN_USERNAME"];
$admin_email=$_ENV["ADMIN_EMAIL"];
$admin_password=$_ENV["ADMIN_PASSWORD"];


if (!isset($_ENV["ADMIN_USERNAME"])||!isset($_ENV["ADMIN_EMAIL"])||!isset($_ENV["ADMIN_PASSWORD"])) {
    die("ENVs not loaded from .env");
}



// Prepare data

$password = password_hash($admin_password, PASSWORD_DEFAULT);

// Prepare SQL statement
$sql = "INSERT INTO admin (username, email, password) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// Check if prepare worked
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

// Bind parameters (s = string)
mysqli_stmt_bind_param($stmt, "sss", $admin_username, $admin_email, $password);//password is the hashed password of admin


// Execute
if (mysqli_stmt_execute($stmt)) {
    echo "Admin created successfully.";
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Close
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
