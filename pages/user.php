<?php
include "../database/db_connection.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;


$sql = "SELECT users.username , users.email ,posts.title, posts.content, posts.created_at, posts.image
        FROM users
        JOIN posts ON posts.user_id = users.id
        WHERE users.id = $id";

$result = mysqli_query($conn,$sql);




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php echo $id ?>
</body>
</html>