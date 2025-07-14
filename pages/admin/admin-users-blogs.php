<?php
include "../../database/db_connection.php";

$id = isset($_GET["user_id"]) ? (int) $_GET["user_id"] : 0;

$sql = "SELECT posts.title, posts.content, posts.created_at, posts.image, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.user_id = $id";

$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $title = $row['title'];
    $content = $row['content'];
    $author = $row['username'];
    $image = $row['image'];
    $created_at = substr($row['created_at'], 0, 10);
} else {
    $title = "Blog Not Found";
    $content = "";
    $author = "";
    $created_at = "";
    $image = "";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - View Blog</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      margin: 0;
      padding: 20px;
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      color: #333;
    }

    .blog-container {
      max-width: 800px;
      margin: 30px auto;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      padding: 30px;
    }

    .blog-title {
      font-size: 28px;
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .blog-meta {
      font-size: 14px;
      color: #777;
      margin-bottom: 20px;
    }

    .blog-image {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .blog-content {
      font-size: 16px;
      line-height: 1.7;
      color: #444;
      white-space: pre-line;
    }

    .back-link {
      display: inline-block;
      margin-top: 30px;
      text-decoration: none;
      color: #1abc9c;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="blog-container">
    <div class="blog-title"><?= htmlspecialchars($title); ?></div>
    <?php if ($author): ?>
      <div class="blog-meta">By <strong><?= htmlspecialchars($author); ?></strong> | <?= htmlspecialchars($created_at); ?></div>
    <?php endif; ?>

    <?php if (!empty($image)): ?>
      <img src="/BlogLikho/uploads/<?= htmlspecialchars($image); ?>" alt="Blog Image" class="blog-image">
    <?php endif; ?>

    <div class="blog-content"><?= nl2br(htmlspecialchars($content)); ?></div>

    <a href="admin-users.php" class="back-link">← Back to Users</a>
  </div>

</body>
</html>
