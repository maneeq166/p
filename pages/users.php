<?php
include "../database/db_connection.php";
include "../components/navbar.php";

$sql = "SELECT users.username, users.email,users.id FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="../styles/users.css">
</head>
<body>

<div class="container">
    <div class="users-wrapper">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $username = $row["username"];
                $email = $row["email"];
                $id = $row["id"];
                ?>
                <div class="user-card">
                    <div class="user-pfp"></div>

                    <div class="user-info-wrapper">

                        <div class="user-info">
                            <a href="./user.php?id=<?= $id?>"><h1><?php echo htmlspecialchars($username); ?></h1></a>
                            <p><?php echo htmlspecialchars($email); ?></p>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No users found.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</div>

</body>
</html>
