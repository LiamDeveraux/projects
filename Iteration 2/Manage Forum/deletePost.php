<?php
	session_start();
    if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) 
    	|| !isset($_SESSION['username'])){
    	header("location:../../Iteration 1/Login/login.php");
    	exit();
    }
	else{
		if($_SESSION['logged_in'] === "user"){
			header("location:../../Main Pages/forum.php");
    		exit();
		}
	}
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $_SESSION['proc_error'] = "Please dont try anything funny";
    	header("Location:postList.php");
    	exit();
    }
    
	if(empty($_POST['post_id']) || $_POST['post_id'] == -1)
    {
        $_SESSION['proc_error'] = "Data id is empty, please refresh the page and try again";
        header('Location:postList.php');
        exit();
    }

    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    
    $stmt = $conn->prepare("DELETE FROM post WHERE id = ?");
    $stmt->bind_param("s", $post_id);   
    
    $post_id = trim(filter_var($_POST['post_id'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
    
    if($stmt->execute()){
        // $stmt = $conn->prepare("SELECT reply_id FROM post_reply WHERE post_id = ?");
        // $stmt->bind_param("s", $post_id);   
        // $result = $stmt->execute();
        // if($result->num_rows){
        //     while($row = mysqli_fetch_assoc($result)){
        //         $stmt = $conn->prepare("DELETE FROM reply WHERE id = ?");
        //         $stmt->bind_param("s", $post_id);   
        //         $result = $stmt->execute();
        //     }
        // }
        $_SESSION['proc_message'] = 'Action Successful, post deleted.';
        header('Location:postList.php');
        exit();
    }
    else{
        $_SESSION['proc_error'] = "Action aborted, deletion unsuccessful, please contact your administrator";
        header('Location:postList.php');
        exit(); 
    }
?>