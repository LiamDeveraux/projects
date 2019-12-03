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
    if ($_SERVER["REQUEST_METHOD"] !== "POST" || empty($_POST['post_id'])) {
        $_SESSION['proc_error'] = "Please dont try anything funny";
    	header("Location:../../Main Pages/forum.php");
    	exit();
    }
    if(filter_var($_POST['post_id'], FILTER_VALIDATE_INT)){
        $post_id = $_POST['post_id'];
        if(empty($post_id)){
            $_SESSION['proc_error'] = "Please dont try anything funny";
            header('Location:../../Main Pages/forum.php');
            exit();
        }
    }else{
        $_SESSION['proc_error'] = "Please dont try anything funny";
        header('Location:../../Main Pages/forum.php');
        exit();
    }
	if(empty($_POST['content']))
    {
        $_SESSION['proc_error'] = "Reply failed! Reply cannot be blank";
        header('Location:../../Main Pages/forum.php');
        exit();
    }else{
        $content = filter_var($_POST['content'], FILTER_SANITIZE_SPECIAL_CHARS);
        if(empty($content)){
            $_SESSION['proc_error'] = "Please dont try anything funny";
            header("Location:postPage.php?id={$post_id}");
            exit();
        }
    }

    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    
    $stmt = $conn->prepare("INSERT INTO reply(content, user_id) VALUES (?,?);");
    $stmt->bind_param("ss", $content, $_SESSION['user_id']);   
    
    if($stmt->execute()){
        $result = mysqli_query($conn, "SELECT LAST_INSERT_ID() AS id;");
        if($result){
            $temp = mysqli_fetch_assoc($result);
            
            $stmt = $conn->prepare("INSERT INTO post_reply(post_id, reply_id) VALUES (?,?);");
            $stmt->bind_param("ss", $post_id, $temp['id']);   

            if($stmt->execute()){
                $temp = mysqli_query($conn, "UPDATE user SET reply_count = reply_count + 1 WHERE id = '".$_SESSION['user_id']."';");
                if($temp){
                    $_SESSION['proc_message'] = 'Reply Successful';
                    header("Location:postPage.php?id={$post_id}");
                    exit();
                }
            }
        }
    }
    $_SESSION['proc_error'] = "Action aborted, reply failed, please contact your administrator";
    header("Location:postPage?id={$post_id}.php");
    exit(); 
?>