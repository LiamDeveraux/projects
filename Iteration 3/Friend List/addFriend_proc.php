<!-- Author : Wong Sing Hua -->

<?php
	session_start();
	if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) 
    	|| !isset($_SESSION['username'])){
    	header("location:../../Iteration 1/Login/login.php");
    	exit();
    }
	else{
		if($_SESSION['logged_in'] != "user"){
			header("location:../../Iteration 1/Login/login.php");
    		exit();
		}
	}
    if(empty($_POST['friend_id'])){
        header('location:../../Iteration 1/Search/search.php');
        exit();
    }else{
        if(filter_var($_POST['friend_id'], FILTER_VALIDATE_INT) === false){
            header('location:../../Iteration 1/Search/search.php');
            exit();
        }
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';

    $msg = "";
    //Check redundancy of friend TABLE
    $sql='SELECT * FROM friend WHERE id_1 = "'.$_SESSION['user_id'].'";';
    $result = mysqli_query($conn, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            if($row['id_2']==$_POST['friend_id']){
                $_SESSION['add_friend_error'] = "The friend is existing!";
                header("location:../../Iteration 1/Search/search.php");
                exit();
            }
        }
    }
    //Check redundancy of friend TABLE
    $sql='SELECT * FROM friend WHERE id_2 = "'.$_SESSION['user_id'].'";';
    $result = mysqli_query($conn, $sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            if($row['id_1']==$_POST['friend_id']){
                $_SESSION['add_friend_error'] = "The friend is existing!";
                header("location:../../Iteration 1/Search/search.php");
                exit();
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO friend (id_1, id_2) VALUES (?, ?);");
    $stmt->bind_param("ss", $user, $friend);

    $user = trim(filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT, FILTER_FLAG_STRIP_HIGH));
    $friend = trim(filter_var($_POST['friend_id'], FILTER_VALIDATE_INT, FILTER_FLAG_STRIP_HIGH));

    if($stmt->execute()){
    	//header("Location:friendList.php");
    	//exit();
    }
    // else{
    // 	$_SESSION['error'] = "There is an error with the database, please contact your adminstrator";
    // 	header("Location:schedule_page.php");
    // 	exit();
    // }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="refresh" content="3;url=../../Iteration%203/Friend%20List/friendList.php">
        <style>
            div {
            height: 90px;
            line-height: 90px;
            text-align: center;
            border: 2px dashed black;
            }
        </style>
    </head>
    <body>
    <script type='text/javascript'>
        alert('Your friend is added!!');
        var seconds_left = 3;
        var interval = setInterval(function() {
            document.getElementById('timer_div').innerHTML = --seconds_left;

            if (seconds_left <= 0)
            {
            //document.getElementById('timer_div').innerHTML = "You are Ready!";
            clearInterval(interval);
            }
        }, 1000);
    </script>
    <div>Redirect to friend lists page in <span id="timer_div">3</span> seconds</div>
    </body>
</html>