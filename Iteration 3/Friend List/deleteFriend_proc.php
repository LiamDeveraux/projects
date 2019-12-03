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
    if(empty($_POST['delete_friend'])){
        header('location:friendList.php');
        exit();
    }else{
        if(filter_var($_POST['delete_friend'], FILTER_VALIDATE_INT) === false){
            header('location:friendList.php');
            exit();
        }
    }

    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    $stmt = $conn->prepare("DELETE FROM friend WHERE (id_1=? && id_2=?) || (id_1=? && id_2=?)");
    $stmt->bind_param("ssss", $_POST['delete_friend'], $_SESSION['user_id'], $_SESSION['user_id'], $_POST['delete_friend']);

    if($stmt->execute()){
    	header("Location:friendList.php");
    	exit();
    }
    // else{
    // 	$_SESSION['error'] = "There is an error with the database, please contact your adminstrator";
    // 	header("Location:schedule_page.php");
    // 	exit();
    // }

?>