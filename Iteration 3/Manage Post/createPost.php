<?php
	session_start();
    if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) 
    	|| !isset($_SESSION['username'])){
    	header("location:../../Iteration 1/Login/login.php");
    	exit();
    }
	else{
		if($_SESSION['logged_in'] === "admin"){
			header("location:../../Main Pages/dashboard.php");
    		exit();
		}
	}
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $_SESSION['proc_error'] = "Please dont try anything funny";
    	header("Location:../../Main Pages/forum.php");
    	exit();
    }
    
	if(empty($_POST['title']) || empty($_POST['content']))
    {
        $_SESSION['proc_error'] = "Post creation failed, please make sure both title and content are not empty";
        header('Location:../../Main Pages/forum.php');
        exit();
    }

    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    
    $stmt = $conn->prepare("INSERT INTO post(title, content, user_id) VALUES (?,?,?);");
    $stmt->bind_param("sss", $title, $content, $_SESSION['user_id']);   

    $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);   
    $content = filter_var($_POST['content'], FILTER_SANITIZE_SPECIAL_CHARS);   
    
    if($stmt->execute()){
        $temp = mysqli_query($conn, "UPDATE user SET post_count = post_count + 1 WHERE id = '".$_SESSION['user_id']."';");
        if($temp){
            $_SESSION['proc_message'] = 'Action Successful, post created.';
            header('Location:../../Main Pages/forum.php');
            exit();
        }
    }
    else{
        $_SESSION['proc_error'] = "Action aborted, post creation failed, please contact your administrator";
        header('Location:../../Main Pages/forum.php');
        exit(); 
    }
?>