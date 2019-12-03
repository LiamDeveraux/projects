<!-- Author : Toh Chen Long -->

<?php
    session_start();
    if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) 
        || !isset($_SESSION['username'])){
        header('Location:../../Iteration 1/Login/login.php');
        exit();
    }
    else{
        if($_SESSION['logged_in'] === "user"){
            header("location:../../Main Pages/forum.php");
            exit();
        }
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forumify</title>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous">
    </script>
    <link rel="stylesheet" type="text/css" href="../../General%20files/main.css">
</head>
<body>
    <div class="procMessage">
        <?php
            if(isset($_SESSION['proc_message'])){
                echo '<div style="background-color: #dff0d8; color:#3c763d; opacity:0.7;">'.$_SESSION["proc_message"].'</div>';
                unset($_SESSION['proc_message']);
            }
            else if(isset($_SESSION['proc_error'])){
                echo '<div style="background-color: #f2dede; color:#a94442; opacity:0.7;">'.$_SESSION["proc_error"].'</div>';
                unset($_SESSION['proc_error']);
            }
        ?>
    </div>
    <div class="topnav">
        <a class="logo" href=""><img class="logoImage" src="../../General files/logo.png"></a>
        <div class="topnav-content">
            <a class="links" href="../../Main Pages/dashboard.php">Dashboard</a>
            <a class="links active" href="">Forum</a>
            <a class="links" href="../Manage Database/userList.php">Users</a>
            <a class="links" href="../../Iteration 1/Logout/logout.php">Logout</a>
        </div>
    </div>
    
    <div class="bottomPane">
        <div class="postListContainer">
            <!-- <div class="searchContainer">
                <form action="postList.php" method="post">
                    <input type="text" placeholder="Search.." name="search">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div> -->
            <?php
                // if(isset($_POST['search'])){
                    
                // }
                $result = mysqli_query($conn, "SELECT * FROM post ORDER BY date_posted DESC");
                while($row = mysqli_fetch_assoc($result)){
                    $temp = mysqli_query($conn, "SELECT username FROM user WHERE id = {$row['user_id']}");
                    $var = "Anonymous";

                    if($temp){
                        $var = mysqli_fetch_assoc($temp)['username'];
                    }
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date_posted']);
                    echo '<div class="card" style="position:relative;">
                    <a data-id="'.$row['id'].'" class="deleteButton" style="color:red;"><i class="fas fa-trash-alt"></i></a>
                    <p class="postCreator"><a><img class="profilePicture navPicture" src="../../General files/sample.jpg">
                    </a><a>'.$var.'</a></p>
                    <h2 class="postTitle">'.$row['title'].'</h2>
                    <div class="content">
                        <p>'.$row['content'].'</p>
                    </div>
                    <p align="right"; style="color:white">Date Posted : '.$date->format('d/m/Y').'</p>
                    <!-- <p>Reply</p> -->
                </div>';
                }
            ?>
        </div>
    </div>
    <div id="myModal" class="modal">
        
        <!-- Modal content 1 -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close cross">&times;</span>
                <h2 style="margin:0;">Deletion Confirmation</h2>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this post?</p>
            </div>
            <div class="modal-footer">
                <form action="deletePost.php" method="post">                                    
                    <input id="delete_post_form" type="hidden" name="post_id" value="-1">
                    <button class="" type="submit">Delete</button>
                    <button class="close" type="reset">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    var modal = document.getElementById("myModal");

    var btns = document.querySelectorAll(".deleteButton");

    var close = document.querySelectorAll(".close");

    btns.forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            modal.style.display = "flex";
        });
    });

    close.forEach(function(c){
        c.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = "none";
        });
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    $('.deleteButton').click(function(){
        var id = $(this).data("id"); 
        $('#delete_post_form').attr('value', id);
    });
</script>
</html>