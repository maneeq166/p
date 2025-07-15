<?php
include "../../database/db_connection.php";

// Handle blog deletion
if (isset($_GET['delete_id'])) {
  $deleteId = (int) $_GET['delete_id'];
  mysqli_query($conn, "DELETE FROM posts WHERE id = $deleteId");
  header("Location: admin-blogs.php");
  exit;
}

// Fetch all blogs with user info
$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, posts.image, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";

$result = mysqli_query($conn, $sql);
$blogs = [];

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $blogs[] = $row;
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - All Blogs</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      margin: 0;
      padding: 20px;
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      color: #333;
    }

    .container {
      max-width: 1000px;
      margin: 30px auto;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      padding: 30px;
    }

    .blog {
      margin-bottom: 40px;
    }

    .blog-title {
      font-size: 24px;
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .blog-meta {
      font-size: 14px;
      color: #777;
      margin-bottom: 10px;
    }

    .blog-image {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    .blog-content {
      font-size: 15px;
      line-height: 1.6;
      color: #444;
      white-space: pre-line;
      margin-bottom: 10px;
    }

    .action-links a {
      margin-right: 15px;
      text-decoration: none;
      font-size: 14px;
      color: #1abc9c;
      font-weight: bold;
    }

    .action-links a.delete {
      color: red;
    }

    .action-links a:hover {
      text-decoration: underline;
    }

    h1 {
      text-align: center;
      color: #1e272e;
    }

    .no-blogs {
      text-align: center;
      font-size: 18px;
      color: #999;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>All Blogs</h1>

    <?php if (!empty($blogs)): ?>
      <?php foreach ($blogs as $blog): ?>
        <div class="blog">
          <div class="blog-title"><?= htmlspecialchars($blog['title']) ?></div>
          <div class="blog-meta">
            By <strong><?= htmlspecialchars($blog['username']) ?></strong> |
            <?= htmlspecialchars(substr($blog['created_at'], 0, 10)) ?>
          </div>
          <?php if (!empty($blog['image'])): ?>
            <img src="/BlogLikho/uploads/<?= htmlspecialchars($blog['image']) ?>" alt="Blog Image" class="blog-image">
          <?php endif; ?>
          <div class="blog-content"><?= nl2br(htmlspecialchars($blog['content'])) ?></div>

          <div class="action-links">
            <a class="delete" href="admin-blogs.php?delete_id=<?= $blog['id'] ?>" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
            <a href="update-blog.php?id=<?= $blog['id'] ?>">Update</a>
          </div>
          <hr>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="no-blogs">No blogs found.</div>
    <?php endif; ?>
  </div>

</body>
</html>
