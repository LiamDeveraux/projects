<!-- Author : Wong Sing Hua -->

<?php
	session_start();
	if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) 
    	|| !isset($_SESSION['username'])){
    	header("location:../Login/login.php");
    	exit();
    }
	else{
		if($_SESSION['logged_in'] === "admin"){
			header("location:../../Main Pages/dashboard.php");
    		exit();
		}
	}
    if(empty($_GET['id'])){
        header('location:schedule_page.php');
        exit();
    }else{
        if(filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
            header('location:schedule_page.php');
            exit();
        }
    }

    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    $stmt = $conn->prepare("UPDATE schedule SET title=?, content=?, date=? WHERE id=?");
    $stmt->bind_param("ssss", $title, $content, $date, $_GET['id']);

    if(empty($_POST['title'])){
    	$title = "No Title";
    }else{
        $title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS); 
    }
    
    if(empty($_POST['content'])){
    	$content = "No Content";
    }else{
        $content = filter_var($_POST['content'], FILTER_SANITIZE_SPECIAL_CHARS); 
    }

    if(empty($_POST['date'])){
    	$date = date_create()->format('Y-m-d');
    }else{
    	$date = $_POST['date'];
    }
    
    if($stmt->execute()){
    	header("Location:schedule_page.php");
    	exit();
    }
    // else{
    // 	$_SESSION['error'] = "There is an error with the database, please contact your adminstrator";
    // 	header("Location:schedule_page.php");
    // 	exit();
    // }

?>