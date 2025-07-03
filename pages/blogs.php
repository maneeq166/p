<?php
include "../database/db_connection.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$sql = "SELECT posts.title, posts.content, posts.created_at, posts.image, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.id = $id";

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/BlogLikho/styles/blog.css" />

    <title><?php echo htmlspecialchars($title); ?> | BlogLikho</title>
</head>

<body>
    <button>
        <a href="../pages/index.php">
            Back to home
        </a>
    </button>
    <div class="container">

        <h1 class="title"><?php echo htmlspecialchars($title); ?></h1>

        <?php if ($author): ?>
            <div class="meta">
                <?php echo htmlspecialchars($author); ?> | <?php echo $created_at; ?>
            </div>
        <?php endif; ?>

        <div class="content">
            <?php if ($image): ?>
                <img src="../uploads/<?php echo $image; ?>" alt="Blog Image">
            <?php endif ?>
            <?php echo htmlspecialchars($content); ?>
        </div>
    </div>
</body>

</html>