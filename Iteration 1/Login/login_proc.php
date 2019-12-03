<!-- Author : Ting Kee Chung -->

<?php
	session_start();
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
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    	$_SESSION['error'] = "Please dont try anything funny";
    	header("Location:login.php");
    	exit();
    }
    
	if(empty($_POST['username']) || empty($_POST['password']))
    {
        $_SESSION['error'] = "Please fill in all the credentials";
        header('Location:login.php');
        exit();
    }

    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ?;");
    $stmt->bind_param("ss", $username, $password);   
    
    $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
    
    $stmt->execute();
    $result = $stmt->get_result();
    // $all = $resultSet->fetch_all();

    if($result->num_rows > 1){
    	$_SESSION['error'] = "There is duplicate with the account, please contact your database administrator immediately or login with another account";
    	header("Location:login.php");
        exit();
    }
    elseif ($result->num_rows == 1) {
        $row = mysqli_fetch_assoc($result);

        if($row['ban_status'] == 1){
            $_SESSION['error'] = "Your Accont has been banned, please contact your administrator";
            header('Location:login.php');
            exit();
        }
    	$_SESSION['logged_in'] = "user";
    	$_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;

        $sql = "UPDATE `user` SET `last_active` = NOW() WHERE `id` = \"{$_SESSION['user_id']}\";";
        if(mysqli_query($conn, $sql)){
            header('Location:../../Main Pages/forum.php');
            exit();
        }
    }
    else{
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?;");
        $stmt->bind_param("ss", $username, $password);

        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $row = mysqli_fetch_assoc($result);
            $_SESSION['logged_in'] = "admin";
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            header('Location:../../Main Pages/dashboard.php');
            exit();
        }else{
            $_SESSION['error'] = "Wrong password or username!";
            header('Location:login.php');
            exit();
        }
    }
?>