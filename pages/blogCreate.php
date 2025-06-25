<?php
session_start();
include "../database/db_connection.php";

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $header = $_POST['header'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $image_name = null; // default is NULL if no image

    // Only process image if uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $target_dir = "../uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Check file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<script>alert('Only JPG, JPEG, PNG, GIF files allowed.');</script>";
            $uploadOk = 0;
        }

        // Size check
        if ($_FILES["image"]["size"] > 500000) {
            echo "<script>alert('Image too large (limit 500KB).');</script>";
            $uploadOk = 0;
        }

        // File exists check
        if (file_exists($target_file)) {
            echo "<script>alert('File already exists.');</script>";
            $uploadOk = 0;
        }

        // Upload image
        if ($uploadOk === 1) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "<script>alert('Image upload failed.');</script>";
                $image_name = null; // fallback to null
            }
        } else {
            $image_name = null; // fallback to null
        }
    }

    // Final insert
    if (!empty($header) && !empty($content)) {
        $sql = "INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isss", $user_id, $header, $content, $image_name);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Blog Posted Successfully!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Error while posting.');</script>";
            }
        }
    }

    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/blogCreate.css">
    <title>Document</title>
</head>

<body>
    <div>
        <form method="POST" enctype="multipart/form-data">

            <input type="file" name="image" id="imageInput">
            <input class="heading" name="header" placeholder="header for your blog.." type="text" required>
            <textarea name="content" cols="80" rows="100" id="contents" placeholder="write some content...."
                required></textarea>
            <button type="submit">Submit Your Blog</button>
        </form>
    </div>
</body>

</html>