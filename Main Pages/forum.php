<!-- Author : Toh Chen Long -->

<?php
    session_start();
    if(!isset($_SESSION['logged_in'])){
        header('Location:../Iteration 1/Login/login.php');
        exit();
    }else{
        if($_SESSION['logged_in'] === "admin"){
			header("Location:../../Main Pages/dashboard.php");
			exit();
		}
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/OnlineFriends.php';
  
    $app = new OnlineFriends();
    $online_friends = $app->getOnlineFriends($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forumify</title>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" type="text/css" href="../General files/main.css">
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous">
    </script>
</head>
<style>
    .plus{
        position:absolute;
        right:1rem;
        bottom:1rem;
        color: #00ff22;
        font-weight:500;
        font-size:50px;
    }
    .plus:hover,
    .card:hover{
        cursor:pointer;
    }
</style>
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
        <a class="logo" href="forum.php"><img class="logoImage" src="../General files/logo.png"></a>
        <div class="topnav-content">
            <a href="../Iteration 1/Search/search.php"><i class="fas fa-search"></i></a>
            <a class="links active" href="forum.php">Forum</a>
            <?php
            	echo "<a class=\"links\" href=\"../SendBird/web/chat.html?userid={$_SESSION['user_id']}&nickname={$_SESSION['username']}\">ChatRoom</a>";
            ?>
            <a class="links" href="../Iteration 1/Schedule/schedule_page.php">Schedule</a>
            <div class="linkDiv">
                <a href=""><img class="profilePicture navPicture" src="../General files/sample.jpg"></a>
                <div class="dropdown-content">
                    <a href="../Iteration 3/Friend List/friendList.php">Friends</a>
                    <a href="../Iteration 2/Manage Profile/userProfile.php">Account</a>
                    <a href="../Iteration 1/Logout/logout.php">Logout</a>
                </div>
            </div>  
        </div>
    </div>
    <div class="bottomPane">
        <div class="leftPane" style="position:relative">
            <a class="plus"><i class="far fa-plus-square"></i></a>
            <div class="postListContainer">
            <!-- <div>
                <div class="card">
                    <p>Username</p>
                    <div class="content">
                        Lorem ipsum
                    </div>
                    <a href="">Reply</a>
                </div>
            </div> -->
            
            <?php
                $sql = "SELECT * FROM post ORDER BY date_posted DESC;";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $temp = mysqli_query($conn, "SELECT username FROM user WHERE id = {$row['user_id']}");
                    $var = "Anonymous";

                    if($temp){
                        $var = mysqli_fetch_assoc($temp)['username'];
                    }

                    echo   '<div class="card" data-id='.$row['id'].'><div>
                    <p class="postCreator"><img class="profilePicture navPicture" src="../General Files/sample.jpg">'.$var.'</p>
                    <h2 class="postTitle">'.$row['title'].'</h2>
                    <div class="content">
                        <p>'.$row['content'].'</p>
                    </div>
                    <p align="left"; style="color:white">Reply :</p>
                    </div></div>';          
                }
            ?>
            </div>
        </div>
        <div class="rightPane">
            <div class="schedulePane">
                <p class="text">Up Coming Schedule</p>
            </div>
            <div class="friendPane">
                <div class="friendsDiv">
                    <p class="text">Online Friends</p>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal">
        
        <!-- Modal content -->
        <div class="modal-content">
            <form action="../Iteration 3/Manage Post/createPost.php" method="post">
                <div class="modal-header">
                    <span class="close cross">&times;</span>
                    <h2 style="margin:0;">Create Post</h2>
                </div>
                <div class="modal-body">
                    <div class="formDiv" >
                        <div>
                            <label>Title : </label>
                            <input type="text" name="title">
                        </div>
                    </div>
                    <div class="formDiv" >
                        <div>
                            <label>Content : </label>
                            <textarea type="text" name="content"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="" type="submit">Post</button>
                    <button class="close" type="reset">Cancel</button>
                </div>
            </form>  
        </div>
    </div>
</body>
<script type="text/javascript">
    var modal = document.getElementById("myModal");

    var close = document.querySelectorAll(".close");

    $('.card').on('click', function(){
        var id = $(this).data("id");
        window.location.href="../Iteration 3/Manage Post/postPage.php?id=" + id;
    });

    $('.plus').on('click', function(){
        modal.style.display = "flex";
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

    $(function() {
        function updateOnlineFriends() {
          $.ajax({
            url: 'ajax/update_stats.php',
            dataType: 'html',
            success: function(data, status) {
              if (data.length > 0) {
                $('.friendsDiv').html(data);
              } else {
                $('.friendsDiv').html('No friends online.');
              }
            }
          });
        }
        
        // Update friends list every 3 seconds.
        setInterval(updateOnlineFriends, 5000);
    });
</script>
</html>