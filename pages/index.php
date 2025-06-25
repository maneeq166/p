<?php
include "../database/db_connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlogLikho</title>
    <link rel="stylesheet" href="../styles/index.css">
</head>

<body>

    <?php include '../components/navbar.php'; ?>
    <div class="wrapper">



        <div class="grid-container">
            <?php
            $sql = "SELECT posts.*, users.username AS author 
                    FROM posts 
                    JOIN users ON posts.user_id = users.id 
                    ORDER BY posts.created_at DESC";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <div class="post">
                        <p class="title"><?php echo htmlspecialchars($row["title"]); ?></p>
                        <div class="meta">
                            <p class="author">By: <?php echo htmlspecialchars($row["author"]); ?></p>
                            <p class="time"><?php echo substr($row["created_at"], 0, 10); ?></p>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:baseline;">

                            <p class="content">
                                <?php echo htmlspecialchars(substr($row["content"], 0, 16)); ?>...
                            </p>
                            <a  href="./blogs.php?id=<?= $row["id"]?>" >Read more</a>

                        </div>

                    </div>
                    <?php
                endwhile;
            else:
                echo "<p>No posts available.</p>";
            endif;

            mysqli_close($conn);
            ?>
        </div>
    </div>

</body>

</html>