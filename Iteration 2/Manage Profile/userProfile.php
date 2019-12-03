<!-- Author : Ting Kee Chung -->

<?php
	session_start();
    if(!isset($_SESSION['logged_in'])){
        header('Location:../../Iteration 1/Login/login.php');
        exit();
    }
    else if($_SESSION['logged_in'] === 'admin'){
        header('Location:../../Main Pages/dashboard.php');
        exit();
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Forumify</title>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" type="text/css" href="../../General files/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    
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
        <a class="logo" href="../Login/login.php"><img class="logoImage" src="../../General%20files/logo.png"></a>
        <div class="topnav-content">
            <a href="../../Iteration 1/Search/search.php"><i class="fas fa-search"></i></a>
            <a class="links" href="../../Main Pages/forum.php">Forum</a>
            <?php
            	echo "<a class='links' href=\"../../SendBird/web/chat.html?userid={$_SESSION['user_id']}&nickname={$_SESSION['username']}\">ChatRoom</a>";
            ?>
            <a class="links" href="../../Iteration 1/Schedule/schedule_page.php">Schedule</a>
            <div class="linkDiv">
                <a href=""><img class="profilePicture navPicture" src="../../General files/sample.jpg"></a>
                <div class="dropdown-content">
                    <a href="../../Iteration 3/Friend List/friendList.php">Friends</a>
                    <a href="">Account</a>
                    <a href="../../Iteration 1/Logout/logout.php">Logout</a>
                </div>
            </div>  
        </div>
    </div>
    <div class="bottomPane">
    	<div class="profileLeftPane">
			<?php
	        	$sql='SELECT * FROM user WHERE id = '.$_SESSION['user_id'];
				$result = mysqli_query($conn, $sql);
				if (!$result) {
					trigger_error('Invalid query: ' . $conn->error);
				}
	        	if($result->num_rows != 0){
	        		while($row = mysqli_fetch_assoc($result)){
						//$pass = $row["password"];
						$email = $row["email"];
						$desc = $row["description"];
	        			echo    "<div class='profileCard'>
                                    <div class='card_image'>
                                        <img class='profilePicture' src='../../General files/sample.jpg'>
                                    </div>
                                    <div class='card_content'>
                                        <h2 class='card_title'>"."Username: <i>".$row['username']."</i></h2>
                                        <p class='card_text'>".$row['description']."</p>
                                    </div>
                                </div>
                                ";
                        $post_count = $row['post_count'];
                        $reply_count = $row['reply_count'];
	        		}
				}
	        ?>
    		
    	</div>	
    	<div class="profileRightPane">
    		<div class="center">
	    		<h1>Email : <i><?php echo $email ?></i></h1>
	    		<h1>Number of posts posted : <i><?php echo $post_count ?></i></h1>
	    		<h1>Number of replies : <i><?php echo $reply_count ?></i></h1>
	    		<div class="buttonGroup">
                    <p class='center_no_margin editBtn'><input type='submit' value='Edit Info' class='btn card_btn'/></p>
				</div>
			</div>
    	</div>
    </div>
    
    <div id="myModal" class="modal">
        
        <!-- Modal content -->
        <div class="modal-content">
            <form action="updateProfile.php" method="post">
                <div class="modal-header">
                    <span class="close cross">&times;</span>
                    <h2 style="margin:0;">Edit Account Info</h2>
                </div>
                <div class="modal-body">
                    <div class="formDiv" >
                        <label>Username : <?php echo $_SESSION['username']?></label>    
                    </div>
                    <div class="formDiv" >
                        <div>
                            <label>Email : </label>
                            <input type="email" name="email">
                        </div>
                        <i><small style="color:#3c763d;">Current Email : <?php echo $email?></small></i>   
                    </div>
                    <div class="formDiv" >
                        <div>
                            <label>Description : </label>
                            <textarea type="text" name="description"></textarea>
                        </div>
                        <i><small style="color:#3c763d;">Current Description : <?php echo $desc?></small></i>   
                    </div>
                    <div>
                        <b><i>Note : Keep the field empty, if you do not wish to change the field</i></b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="" type="submit">Save</button>
                    <button class="close" type="reset">Cancel</button>
                </div>
            </form>  
        </div>
    </div>
</body>
<script>
    var modal = document.getElementById("myModal");

    var btns = document.querySelectorAll(".editBtn");

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