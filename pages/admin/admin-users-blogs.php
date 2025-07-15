<?php
include "../../database/db_connection.php";

$id = isset($_GET["user_id"]) ? (int) $_GET["user_id"] : 0;

if (isset($_GET['delete_id'])) {
  $deleteId = (int) $_GET['delete_id'];
  mysqli_query($conn, "DELETE FROM posts WHERE id = $deleteId");
  header("Location: admin-users-blogs.php?user_id=" . $id);
  exit;
}

$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, posts.image, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.user_id = $id";

$result = mysqli_query($conn, $sql);

$blogs = [];
if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $blogs[] = $row;
  }
} else {
  $no_blogs = true;
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
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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

    .delete-link {
      display: inline-block;
      margin-bottom: 15px;
      color: red;
      font-size: 14px;
      text-decoration: none;
    }

    .delete-link:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <div class="blog-container">
    <?php if (!empty($blogs)): ?>
      <?php foreach ($blogs as $blog): ?>
        <div class="blog-title"><?= htmlspecialchars($blog['title']); ?></div>
        <a class="delete-link" href="admin-users-blogs.php?user_id=<?= $id ?>&delete_id=<?= $blog['id'] ?>" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
        <div class="blog-meta">
          By <strong><?= htmlspecialchars($blog['username']); ?></strong> |
          <?= htmlspecialchars(substr($blog['created_at'], 0, 10)); ?>
        </div>
        <?php if (!empty($blog['image'])): ?>
          <img src="/BlogLikho/uploads/<?= htmlspecialchars($blog['image']); ?>" class="blog-image" alt="Blog Image">
        <?php endif; ?>
        <div class="blog-content"><?= nl2br(htmlspecialchars($blog['content'])); ?></div>
        <hr style="margin: 40px 0; border: 1px solid #eee;">
      <?php endforeach; ?>
    <?php else: ?>
      <div class="blog-title">No Blogs Found</div>
    <?php endif; ?>

    <a href="admin-users.php" class="back-link">← Back to Users</a>
  </div>

</body>

</html>
