
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
    	header("Location:userList.php");
    	exit();
    }
    
	if(empty($_POST['user_id']))
    {
        $_SESSION['proc_error'] = "Data id is empty, please refresh the page and try again";
        header('Location:userList.php');
        exit();
    }

    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    
    $stmt = $conn->prepare("UPDATE user SET ban_status = 0 WHERE id = ?");
    $stmt->bind_param("s", $user_id);   
    
    $user_id = trim(filter_var($_POST['user_id'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
    
    if($stmt->execute()){
        $_SESSION['proc_message'] = 'Action Successful, user unbanned.';
        header('Location:userList.php');
        exit();
    }
    else{
        $_SESSION['proc_error'] = "Action aborted, status lifting unsuccessful, please contact your administrator";
        header('Location:userList.php');
        exit(); 
    }
?>