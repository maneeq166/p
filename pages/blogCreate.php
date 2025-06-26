<?php
session_start();
require_once "../env/loadenv.php";
loadEnv('C:/Nxamp/htdocs/BlogLikho/env/.env');
include "../database/db_connection.php";

$img_api_key = $_ENV["IMGBB_API_KEY"];

if (!isset($_ENV["IMGBB_API_KEY"])) {
    die("IMGBB API key not loaded from .env");
}


if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $header = $_POST['header'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $image_url = null; // default is NULL if no image

    // Only process image if uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {

        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowed_types)) {
            $error = "Only JPG, JPEG, PNG, GIF files allowed.";
        } elseif ($_FILES["image"]["size"] > 500000) {
            $error = "Image too large (limit 500KB).";
        } else {
            $image = $_FILES['image']['tmp_name'];
            $image_data = base64_encode(file_get_contents($image));
            $url = "https://api.imgbb.com/1/upload?key=$img_api_key";
            $data = ['image' => $image_data];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if (isset($result['data']['url'])) {
                $image_url = $result['data']['url'];
            } else {
                echo "<pre>";
                print_r($result);
                echo "</pre>";
                exit;

            }
        }

    }

    // Final insert
    if (!empty($header) && !empty($content)) {
        $sql = "INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isss", $user_id, $header, $content, $image_url);
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