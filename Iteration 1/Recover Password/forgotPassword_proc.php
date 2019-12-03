<!-- Author : Toh Chen Long -->

<?php
    session_start();
    unset($_SESSION['error']);
    unset($_SESSION['success']);
    unset($_SESSION['reg_error']);
    unset($_SESSION['recover_error']);
    
	if(isset($_SESSION['logged_in'])){
		if($_SESSION['logged_in'] === "user"){
			header("Location:../../Main Pages/forum.php");
			exit();
		}
		else if($_SESSION['logged_in'] === "admin"){
			header("Location:../../Main Pages/dashboard.php");
			exit();
		}
	}
	if(empty($_POST['recover_email']))
    {
        $_SESSION['recover_error'] = "Please fill in your email before continuing";
        header('Location:forgotPassword.php');
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    	$_SESSION['error'] = "Please dont try anything funny";
    	header("Location:../../Main Pages/login.php");
    	exit();
    }
    if(!filter_var($_POST['recover_email'],FILTER_VALIDATE_EMAIL)){
        $_SESSION['recover_error'] = "Email is invalid, please try again!";
        header('Location:forgotPassword.php');
        exit();
    }

    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';

    $original_email = $_POST['recover_email'];
    $clean_email = filter_var($original_email,FILTER_SANITIZE_EMAIL);

    if ($original_email != $clean_email || !filter_var($original_email,FILTER_VALIDATE_EMAIL)){
        $_SESSION['recover_error'] = "Email is invalid, please try again!";
        header('Location:forgotPassword.php');
        exit();
    }else{
        $_SESSION['email'] = $clean_email;
    }
    $pwd = bin2hex(openssl_random_pseudo_bytes(4));
    $sql = "SELECT * FROM user WHERE email = '{$clean_email}'; ";
    $result = mysqli_query($conn, $sql);

    if($result->num_rows == 0){
        $_SESSION['recover_error'] = "Email does not exist in our database, please try again!";
        header('Location:forgotPassword.php');
        exit();
    }else if($result->num_rows > 1){
        $_SESSION['recover_error'] = "There is duplicate with the email, please contact your database administrator immediately or use another email";
        header("Location:forgotPassword.php");
        exit();
    }else{
        $_SESSION['email'] = $clean_email;
        $sql = "UPDATE user SET password = '{$pwd}' WHERE email = '{$clean_email}';";
        if(mysqli_query($conn, $sql)){
            // $msg = "Here is your new password : \n
            //         {$pwd}\n
            //         You can always change your password in Account -> Settings";

            // $msg = wordwrap($msg,70);

            // mail($_SESSION['email'],"Password Reset",$msg);
            header("location:recoverMessage.php");
            exit();
        }
        else{
            $_SESSION['recover_error'] = "There is an error with the database, please contact your database administrator immediately";
            header("Location:forgotPassword.php");
            exit();
        }
    }

?>