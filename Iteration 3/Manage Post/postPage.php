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
    if(!isset($_GET['id'])){
        $_SESSION['proc_error'] = "Please dont try anything funny";
        header('Location:../../Main Pages/forum.php');
        exit();
    }else{
        if(filter_var($_GET['id'], FILTER_VALIDATE_INT)){
            $post_id = $_GET['id'];
        }else{
            $_SESSION['proc_error'] = "Please dont try anything funny";
            header('Location:../../Main Pages/forum.php');
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
    <link rel="stylesheet" type="text/css" href="../../General Files/main.css">
</head>
<style>
	.content{
		width:100%;
	}

	.tooltiptext {
		visibility: hidden;
		min-width: 120px;
		background-color: black;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px 0;
		position: absolute;
		z-index: 1;
		top: 100%;
		left: 50%;
		margin-left: -60px;
	}

	.tooltiptext::after {
		content: "";
		position: absolute;
		bottom: 100%;
		left: 50%;
		margin-left: -5px;
		border-width: 5px;
		border-style: solid;
		border-color: transparent transparent black transparent;
	}

	.commentContainer:hover .tooltiptext {
		visibility: visible;
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
        <a class="logo" href="../../Main Pages/forum.php"><img class="logoImage" src="../../General files/logo.png"></a>
        <div class="topnav-content">
            <a href="../../Iteration 1/Search/search.php"><i class="fas fa-search"></i></a>
            <a class="links" href="../../Main Pages/forum.php">Forum</a>
            <?php
            	echo "<a class=\"links\" href=\"../../SendBird/web/chat.html?userid={$_SESSION['user_id']}&nickname={$_SESSION['username']}\">ChatRoom</a>";
            ?>
            <a class="links" href="../../Iteration 1/Schedule/schedule_page.php">Schedule</a>
            <div class="linkDiv">
                <a href=""><img class="profilePicture navPicture" src="../../General files/sample.jpg"></a>
                <div class="dropdown-content">
                    <a href="../../Iteration 3/Friend List/friendList.php">Friends</a>
                    <a href="../../Iteration 2/Manage Profile/userProfile.php">Account</a>
                    <a href="../../Iteration 1/Logout/logout.php">Logout</a>
                </div>
            </div>  
        </div>
    </div>
    <div class="postFullPane">
        <div class="card">
            <!-- <p class="postCreator"><img style=" margin-right:10px;" class="profilePicture navPicture" src="C:\Users\user\Downloads\rsz_257569-preview.jpg"> Username</p>
            <h2 class="postTitle">Lorem Ipsum</a></h2>
            <div class="content">
                <p>Lorem</p>
			</div>
            <p align="right"; style="color:white">Date Posted :</p>
			<a class="replyBtn"><p>Reply</p></a>
            <div style="position: relative;">
                <p class="comment">Comments : </p>
            </div>
            <div class="commentContainer">
                <p class="commentor"><img class="profilePicture navPicture" src="C:\Users\user\Downloads\rsz_257569-preview.jpg"> Username</p>
				<div class="content">
						
				</div>
				<span class="tooltiptext">Date Posted :</span>
            </div> -->
            <?php
                $result = mysqli_query($conn, "SELECT * FROM post WHERE id = ".$post_id);
                if($result){
                    $row = mysqli_fetch_assoc($result);
                    $temp = mysqli_query($conn, "SELECT username FROM user WHERE id = '".$row['user_id']."';");
                    $var = "Anonymous";
                    
                    if($temp){
                        $var = mysqli_fetch_assoc($temp)['username'];
                    }
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date_posted']);
                    echo"<p class='postCreator'><img style='margin-right:10px;' class='profilePicture navPicture' src='../../General Files/sample.jpg'>{$var}</p>
                    <h2 class='postTitle'>{$row['title']}</a></h2>
                    <div class='content'>
                        <p>{$row['content']}</p>
                    </div>
                    <p align='right'; style='color:white'>Date Posted : {$date->format('d/m/Y')}</p>
                    <a class='replyBtn'><p>Reply</p></a>
                    <div style='position: relative;'>
                        <p class='comment'>Comments : </p>
                    </div>";
                    
                    $temp = mysqli_query($conn, "SELECT * FROM post_reply WHERE post_id = '".$post_id."';");
                    if($temp){
                        if($temp->num_rows){
                            while($row_2 = mysqli_fetch_assoc($temp)){
                                $temp_2 = mysqli_query($conn, "SELECT * FROM reply WHERE id = '".$row_2['reply_id']."';");
                                if($temp_2->num_rows){
                                    while($row_3 = mysqli_fetch_assoc($temp_2)){
                                        $temp_3 = mysqli_query($conn, "SELECT username FROM user WHERE id = '".$row_3['user_id']."';");
                                        $var = "Anonymous";
                                        
                                        if($temp_3){
                                            $var = mysqli_fetch_assoc($temp_3)['username'];
                                        }
                                        $date = DateTime::createFromFormat('Y-m-d H:i:s', $row_3['date_posted']);
                                        
                                        echo"<div class='commentContainer'>
                                        <p class='commentor'><img class='profilePicture navPicture' src='../../General Files/sample.jpg'>{$var}</p>
                                        <div class='content'>
                                        {$row_3['content']}
                                        </div>
                                        <span class='tooltiptext'>Date Posted : {$date->format('d/m/Y')}</span>
                                        </div>";
                                    }
                                }
                            }
                        }else{
                            echo"<div class='commentContainer'>There is no replies for this post</div>";
                        }
                    }else{
                        echo"<div class='commentContainer'> SQL Error, please contact your administrator</div>";
                    }
                }else{
                    $_SESSION['proc_error'] = "Post not found";
                    header("Location:../../Main Pages/forum.php");
                    exit(); 
                }
            ?>
        </div>
    </div>
    <div id="myModal" class="modal">
        
        <!-- Modal content -->
        <div class="modal-content">
            <form action="replyPost.php" method="post">
                <input type="hidden" value="<?php echo $post_id; ?>" name="post_id">
                <div class="modal-header">
                    <span class="close cross">&times;</span>
                    <h2 style="margin:0;">Reply Message</h2>
                </div>
                <div class="modal-body">
                    <div class="formDiv" >
                        <div>
                            <label>Message : </label>
                            <textarea type="text" name="content" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="" type="submit">Send</button>
                    <button class="close" type="reset">Cancel</button>
                </div>
            </form>  
        </div>
    </div>
</body>
<script>
    var modal = document.getElementById("myModal");

    var btns = document.querySelectorAll(".replyBtn");

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
</script>
</html>