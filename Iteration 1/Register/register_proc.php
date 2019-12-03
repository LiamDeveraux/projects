<!-- Author : Ting Kee Chung -->

<?php
    session_start();
    unset($_SESSION['error']);
    unset($_SESSION['success']);
    unset($_SESSION['reg_error']);
    
	if(empty($_POST['username']) || empty($_POST['password']) || 
		empty($_POST['email']) || empty($_POST['c_password'])){
		$_SESSION['reg_error'] = "Please fill in all the credentials";
        header('Location:registerAccount.php');
        exit();
	}
	if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    	$_SESSION['reg_error'] = "Please dont try anything funny";
    	header("Location:registerAccount.php");
    	exit();
    }
    if(!empty($_POST['password']) && !empty($_POST['c_password'])){
        if($_POST['password'] != $_POST['c_password']){
            $_SESSION['reg_error'] = "Password do not match, please try again!";
            header('Location:registerAccount.php');
            exit();
        }
    }
    if(!empty($_POST['email'])){
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $_SESSION['reg_error'] = "Email is invalid, please try again!";
            header('Location:registerAccount.php');
            exit();
        }
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    
    $stmt = $conn->prepare("INSERT INTO user (username, password, email, status, last_active) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssis", $username, $password, $email, $status, $last_active);   
    
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
    $c_password = trim(filter_var($_POST['c_password'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));

    $original_email = $_POST['email'];
    $clean_email = filter_var($original_email,FILTER_SANITIZE_EMAIL);

    if ($original_email != $clean_email || !filter_var($original_email,FILTER_VALIDATE_EMAIL)){
        $_SESSION['reg_error'] = "Email is invalid, please try again!";
        header('Location:registerAccount.php');
        exit();
    }
    $email = $clean_email;  
    $status = 1;
    $last_active = date_create('now')->format('Y-m-d H:i:s');

    $sql_1 = "SELECT * FROM user where email = '{$email}';";
    $sql_2 = "SELECT * FROM user where username = '{$username}';";

    if(mysqli_query($conn, $sql_1)->num_rows > 0){
        $_SESSION['reg_error'] = "The email already exists, please try again";
        header('Location:registerAccount.php');
        exit();
    }else if(mysqli_query($conn, $sql_2)->num_rows > 0){
        $_SESSION['reg_error'] = "The username already exists, please try again";
        header('Location:registerAccount.php');
        exit();
    }
    else{
        if($password != $c_password){
            $_SESSION['reg_error'] = "The password entered is different, please try again";
            header('Location:registerAccount.php');
            exit();
        }
        else{
            $_SESSION['success'] = "Registration successful!";
            $stmt->execute();
            header("Location:../Login/login.php");
            exit();
        }
    }
?>