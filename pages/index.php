<?php
include "../database/db_connection.php";

$searched = false;
$search_results = [];
$error = "";

$blog_name = "";
$author_name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blog_name = trim($_POST["blog_name"]);
    $author_name = trim($_POST["author_name"]);

    if (empty($blog_name) && empty($author_name)) {
        $error = "Please enter a blog title or author name to search.";
    } else {
        $searched = true;

        $sql = "SELECT posts.*, users.username AS author 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                WHERE 1=1";

        $params = [];
        $types = "";

        if (!empty($blog_name)) {
            $sql .= " AND posts.title LIKE ?";
            $params[] = "%" . $blog_name . "%";
            $types .= "s";
        }

        if (!empty($author_name)) {
            $sql .= " AND users.username LIKE ?";
            $params[] = "%" . $author_name . "%";
            $types .= "s";
        }

        $sql .= " ORDER BY posts.created_at DESC";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $search_results[] = $row;
                }
            } else {
                $error = "No blogs found for your search.";
            }
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BlogLikho</title>
    <link rel="stylesheet" href="../styles/index.css">
</head>

<body>

    <?php include '../components/navbar.php'; ?>

    <div class="wrapper">

        <form method="POST" style="margin-bottom: 20px;display:flex;margin-right:100px;justify-content:center;">
            <div style="display: flex; flex-direction: row; gap: 10px; padding:20px; width: 300px;">
                <input name="blog_name" value="<?= htmlspecialchars($blog_name); ?>" type="text"
                    placeholder="Search by Blog Title" style="padding: 5px;">
                <input name="author_name" value="<?= htmlspecialchars($author_name); ?>" type="text"
                    placeholder="Search by Author Name" style="padding: 5px;">
                <input type="submit" value="Search" style="padding: 6px 10px;
            margin-right: 15px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;">
            </div>
        </form>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <div class="grid-container">
            <?php
            if ($searched):
                foreach ($search_results as $row): ?>
                    <div class="post">
                        <p class="title"><?= htmlspecialchars($row["title"]); ?></p>
                        <div class="meta">
                            <p class="author">By: <?= htmlspecialchars($row["author"]); ?></p>
                            <p class="time"><?= substr($row["created_at"], 0, 10); ?></p>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:baseline;">
                            <p class="content"><?= htmlspecialchars(substr($row["content"], 0, 16)); ?>...</p>
                            <a href="./blogs.php?id=<?= $row["id"]; ?>">Read more</a>
                        </div>
                    </div>
                <?php endforeach;
            else:
                $sql = "SELECT posts.*, users.username AS author 
                    FROM posts 
                    JOIN users ON posts.user_id = users.id 
                    ORDER BY posts.created_at DESC";

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="post">
                            <p class="title"><?= htmlspecialchars($row["title"]); ?></p>
                            <div class="meta">
                                <p class="author">By: <?= htmlspecialchars($row["author"]); ?></p>
                                <p class="time"><?= substr($row["created_at"], 0, 10); ?></p>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:baseline;">
                                <p class="content"><?= htmlspecialchars(substr($row["content"], 0, 16)); ?>...</p>
                                <a href="./blogs.php?id=<?= $row["id"]; ?>">Read more</a>
                            </div>
                        </div>
                    <?php endwhile;
                else:
                    echo "<p>No posts available.</p>";
                endif;
            endif;

            mysqli_close($conn);
            ?>
        </div>
    </div>

</body>

</html>