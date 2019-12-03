<!-- Author : Ting Kee Chung -->

<?php
    session_start();
    if(!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) 
    	|| !isset($_SESSION['username'])){
    	header("location:../../Iteration 1/Login/login.php");
    	exit();
    }
	else{
        if($_SESSION['logged_in'] === "admin"){
            header("Location:../../Main Pages/dashboard.php");
            exit();
        }
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $_SESSION['proc_error'] = "Please dont try anything funny";
            header("Location:userProfile.php");
            exit();
        }
    }
    if(!empty($_POST['email'])){
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $_SESSION['proc_error'] = "Email is invalid, please try again!";
            header('Location:userProfile.php');
            exit();
        }
    }
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    
    $stmt = "";
	if(empty($_POST['email']) && empty($_POST['description']))
    {
        $_SESSION['proc_message'] = "Action Successful, no record has been changed!";
        header('Location:userProfile.php');
        exit();
    }
    else if(empty($_POST['email'])){
        $stmt = $conn->prepare("UPDATE user SET description = ? WHERE id = ?");
        $stmt->bind_param("ss", $description, $_SESSION['user_id']);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);   
    }
    else if(empty($_POST['description'])){
        $original_email = $_POST['email'];
        $clean_email = filter_var($original_email,FILTER_SANITIZE_EMAIL);

        if ($original_email != $clean_email || !filter_var($original_email,FILTER_VALIDATE_EMAIL)){
            $_SESSION['proc_error'] = "Email is invalid, please try again!";
            header('Location:userProfile.php');
            exit();
        }
        $email = $clean_email; 
        
        //check for duplicate
        $check = mysqli_query($conn, "SELECT 1 FROM user WHERE email = '{$email}'");
        if(!$check){
            $_SESSION['proc_error'] = "SQL Error, please contact your administrator";
            header('Location:userProfile.php');
            exit();
        }else{
            if(mysqli_num_rows($check) > 0){
                $_SESSION['proc_error'] = "Email entered is in use, please use a unique email";
                header('Location:userProfile.php');
                exit();
            }
            else{
                $stmt = $conn->prepare("UPDATE user SET email = ? WHERE id = ?");
                $stmt->bind_param("ss", $email, $_SESSION['user_id']);
            }
        }
    }
    else{
        $original_email = $_POST['email'];
        $clean_email = filter_var($original_email,FILTER_SANITIZE_EMAIL);

        if ($original_email != $clean_email || !filter_var($original_email,FILTER_VALIDATE_EMAIL)){
            $_SESSION['proc_error'] = "Email is invalid, please try again!";
            header('Location:userProfile.php');
            exit();
        }
        $email = $clean_email; 
        $check = mysqli_query($conn, "SELECT 1 FROM user WHERE email = '{$email}'");
        if(!$check){
            $_SESSION['proc_error'] = "SQL Error, please contact your administrator";
            header('Location:userProfile.php');
            exit();
        }else{
            if(mysqli_num_rows($check) > 0){
                $_SESSION['proc_error'] = "Email entered is in use, please use a unique email";
                header('Location:userProfile.php');
                exit();
            }
            else{
                $stmt = $conn->prepare("UPDATE user SET description = ?, email = ? WHERE id = ?");
                $stmt->bind_param("sss", $description, $email, $_SESSION['user_id']);
                $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }

    if($stmt == ""){
        $_SESSION['proc_error'] = "SQL Error, please contact your administrator";
        header('Location:userProfile.php');
        exit();
    }
    $stmt->execute();
    if(mysqli_affected_rows($conn) > 0){
    	$_SESSION['proc_message'] = "Action Successful! Record Updated";
    	header("Location:userProfile.php");
        exit();
    }
    else{
        $_SESSION['proc_error'] = "SQL Error, please contact your administrator";
        header('Location:userProfile.php');
        exit();
    }
?>