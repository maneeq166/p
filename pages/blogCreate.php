<?php 
    session_start();
    include "../database/db_connection.php";

    if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

    if($_SERVER['REQUEST_METHOD']==="POST"){
        $header = $_POST['header'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];
        if(!empty($header) && !empty($content) && !empty($user_id) ){
            $sql = "INSERT into posts (user_id,title,content) VALUES (?,?,?)";
            $stmt = mysqli_prepare($conn,$sql);
            if($stmt){
                mysqli_stmt_bind_param($stmt,"iss",$user_id,$header,$content);                
            if(mysqli_stmt_execute($stmt)){
                echo "<script>alert('Blog Posted'); window.location.href='index.php';</script>";
                // header("Location : blogs.php");
            }else{
                echo "<script>alert('Something went Wrong!')</script>";
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
    <div >
        <form method="POST">
            <input class="heading" name="header" placeholder="header for your blog.." type="text" required>
            <textarea name="content"  cols="80" rows="100" id="contents"  placeholder="write some content...." required></textarea>
            <button type="submit">Submit Your Blog</button>
        </form>
    </div>
</body>
</html>