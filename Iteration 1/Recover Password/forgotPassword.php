<!-- Author : Toh Chen Long -->

<?php
	session_start();
	if(isset($_SESSION['logged_in'])){
		if($_SESSION['logged_in'] === "user"){
			header("Location:../../Main%20Pages/forum.php");
			exit();
		}
		else if($_SESSION['logged_in'] === "admin"){
			header("Location:dashboard.php");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Forumify Login</title>
	<link rel="stylesheet" type="text/css" href="../../General%20files/main.css">
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
</head>
<body>
	<div class="loginContainer">
		<form class="loginForm" method="post" action="forgotPassword_proc.php">
			<a href="../Login/login.php.php"><img class="logoImage" src="../../General%20files/logo.png"></a>
			<h2>Password Reset</h2><br>
			<div style="position: relative;">
				<input class="loginInput" type="email" name="recover_email" placeholder="Email">
				<span class="focus-input"></span>
			</div>
			<?php
				if(isset($_SESSION['recover_error'])){
					echo "<p class='errorMessage'>{$_SESSION['recover_error']}</p>";
				}
			?>
			<button class="loginButton"type="submit">Submit</button>
		</form>
	</div>
</body>
<script>
	$('.loginInput').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })
</script>
</html>
