<?php
include "../database/db_connection.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

// Fetch user information
$userQuery = "SELECT username, email FROM users WHERE id = $id";
$userResult = mysqli_query($conn, $userQuery);

if ($userRow = mysqli_fetch_assoc($userResult)) {
    $username = $userRow['username'];
    $email = $userRow['email'];
} else {
    // User not found
    die("User not found.");
}

// Fetch posts for the user
$postQuery = "SELECT title, content, created_at, image, id FROM posts WHERE user_id = $id";
$postResult = mysqli_query($conn, $postQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/user.css">
    <title>User Profile</title>
</head>
<body>
    <div class="container">
        <div class="user-info">
            <h2><?php echo htmlspecialchars($username); ?></h2>
            <p><?php echo htmlspecialchars($email); ?></p>
        </div>

        <div class="grid-container">
            <?php if (mysqli_num_rows($postResult) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($postResult)): ?>
                <div class="post">
                    <p class="title"><?php echo htmlspecialchars($row["title"]); ?></p>
                    <div class="meta">
                        <p class="time"><?php echo substr($row["created_at"], 0, 10); ?></p>
                    </div>
                    <div class="post-bottom">
                        <p class="content">
                            <?php echo htmlspecialchars(substr($row["content"], 0, 60)); ?>...
                        </p>
                        <a href="./blogs.php?id=<?= $row["id"] ?>" class="read-more">Read more</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-posts">No posts available.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php mysqli_close($conn); ?>
</body>
</html>
