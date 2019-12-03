<!-- Author : Wong Sing Hua -->

<?php
	session_start();
    if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) 
    	|| !isset($_SESSION['username'])){
    	header("location:../../Main%20Pages/login.php");
    	exit();
    }
	else{
		if($_SESSION['logged_in'] != "user"){
			header("location:../../Main%20Pages/login.php");
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
    <link rel="stylesheet" type="text/css" href="../../General%20files/main.css">
    <link rel="stylesheet" type="text/css" href="notes.css">
    <link href='https://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"
        type="text/javascript"></script>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" rel='stylesheet'
        type='text/css'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="notes.js" type="text/javascript"></script>
</head>
<style type="text/css">
    .bottomPane{
        overflow-y: scroll;
    }
</style>
<body>
    <div class="topnav">
        <a class="logo" href="../Login/login.php"><img class="logoImage" src="../../General%20files/logo.png"></a>
        <div class="topnav-content">
            <a href="../Search/search.php"><i class="fas fa-search"></i></a>
            <a class="links" href="../../Main Pages/forum.php">Forum</a>
            <?php
            	echo "<a class=\"links\" href=\"../../SendBird/web/chat.html?userid={$_SESSION['user_id']}&nickname={$_SESSION['username']}\">ChatRoom</a>";
            ?>
            <a class="links active" href="schedule_page.php">Schedule</a>
            <div class="linkDiv">
                <a href=""><img class="profilePicture navPicture" src="../../General%20files/sample.jpg"></a>
                <div class="dropdown-content">
                    <a href="">Friends</a>
                    <a href="">Account</a>
                    <a href="../Logout/logout.php">Logout</a>
                </div>
            </div>  
        </div>
    </div>
    <div class="bottomPane">
    	<!-- <?php
			if(isset($_SESSION['error'])){
				echo "<p class='errorMessage'>{$_SESSION['error']}</p>";
			}
		?> -->
        <a href="javascript:;" class="button" id="add_new">Add New Note</a>
        <div id="board">
	        <?php
	        	$sql='SELECT * FROM schedule WHERE user_id = "'.$_SESSION['user_id'].'";';
	        	$result = mysqli_query($conn, $sql);
	        	if($result->num_rows != 0){
	        		while($row = mysqli_fetch_assoc($result)){
	        			echo 	"<form class=\"note\" action=\"schedule_proc_update.php?id=".$row["id"]."\" method=\"post\">
					                <a href=\"javascript:;\" class=\"button remove\" onclick=\"\">X</a>
					                <div class=\"note_cnt\">
					                    <textarea class=\"title\" name=\"title\" placeholder=\"Enter note title\" spellcheck=\"false\">".$row["title"]."</textarea>
					                    <textarea class=\"cnt\" name=\"content\" placeholder=\"Enter note description here\" spellcheck=\"false\">".$row["content"]."</textarea>
					                </div>
					                <p>Scheduled On : ".$row["date"]."</p>
					                <span>Select date: </span>
					                <input type=\"date\" name=\"date\" min= \"1997-05-12\" max=\"2030-12-31\"> 
					                <button type=\"submit\">Save</button>
					            </form>";
	        		}
	        	}
	        ?>
        </div>
    </div>
</body>
</html>