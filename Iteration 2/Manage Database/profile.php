<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/ProjectPHP/General files/connection.php';
    $html = '';
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
    if(!isset($_GET['user_id']) || empty($_GET['user_id'])){
        $html .= "<p class='errorMessage'>ID not set, user profile cannot be displayed</p>";
    }
    else{
        $user_id = trim(filter_var($_GET['user_id'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
        $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("s", $user_id);

        if(!$stmt->execute()){
            $html .= "<p class='errorMessage'>SQL Error, please contact your administrator</p>";
        }else{
            $result = $stmt->get_result();
            $row = mysqli_fetch_assoc($result);
            $html.='<div class="bottomPane">
                        <div class="profileLeftPane">
                            <div class="profileCard">
                                <img class="profilePicture" src="../../General files/sample.jpg">
                            </div>
                            <div class="card_content">
                                <h2 class="card_title">Username: <i>'.$row["username"].'</i></h2>
                                <p class="card_text">'.$row["description"].'</p>
                            </div>
                        </div>
                        <div class="profileRightPane">
                            <div class="center">
                                <h1>Email : <i>'.$row["email"].'</i></h1>
                                <h1>Number of posts posted : <i>'.$row['post_count'].'</i></h1>
                                <h1>Number of replies : <i>'. $row['reply_count'].'</i></h1>
                            </div>
                        </div>
                        <span class="close cross">&times;</span>
                    </div>';
        }
    }   
    // Update the user's last active time.
    if ($html !== '') echo $html;
?>