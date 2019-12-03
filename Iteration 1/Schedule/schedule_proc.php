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

	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    	$_SESSION['error'] = "Please dont try anything funny";
    	header("Location:../Login/login.php");
    	exit();
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    $stmt = $conn->prepare("INSERT INTO schedule (user_id, title, content, date)  VALUES (?,?,?,?);");
    $stmt->bind_param("ssss", $_SESSION['user_id'], $title, $content, $date);

    if(empty($_POST['title'])){
    	$title = "No Title";
    }else{
    	$title = mysqli_real_escape_string($conn, $_POST['title']);
    }
    
    if(empty($_POST['content'])){
    	$content = "No Content";
    }else{
    	$content = mysqli_real_escape_string($conn, $_POST['content']);
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