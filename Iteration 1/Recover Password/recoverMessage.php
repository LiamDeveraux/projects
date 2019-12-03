<!-- Author : Toh Chen Long -->

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
?>
<!DOCTYPE html>
<html>
<head>
	<title>Forumify Login</title>
	<link rel="stylesheet" type="text/css" href="../../General files/main.css">
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
</head>
<body>
	<div class="loginContainer">
		<div class="loginForm">
			<a href="../Login/login.php.php"><img class="logoImage" src="../../General files/logo.png"></a>
			<div class="messageDisplay">
				<p>
					We sent an email to <b><?php echo $_SESSION['email']; ?></b> to help you recover your account.
					Please login into your email account and use the new password we've sent
				</p>
			</div>
			<button class="loginButton" onclick="window.location.href='../Login/login.php'">Ok</button>
		</div>
	</div>
</body>
</html>
