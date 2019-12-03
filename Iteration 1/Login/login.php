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
		<form class="loginForm" method="post" action="login_proc.php">
			<a href="../Login/login.php"><img class="logoImage" src="../../General files/logo.png"></a>
			<div style="position: relative;">
				<input class="loginInput" type="text" name="username" placeholder="Username">
				<span class="focus-input"></span>
			</div>
			<div style="position: relative;">
				<input class="loginInput" type="password" name="password" placeholder="Password">
				<span class="focus-input"></span>
			</div>
			<button class="loginButton"type="submit">Submit</button>
			<?php
				if(isset($_SESSION['error'])){
					echo "<p class='errorMessage'>{$_SESSION['error']}</p>";
				}
				if(isset($_SESSION['success'])){
					echo "<p class='successMessage'>{$_SESSION['success']}</p>";
				}
			?>
			<p><a href="../Recover Password/forgotPassword.php">Forgot Password?</a></p>
			<p>Don't have an account? <a href="../Register/registerAccount.php">Create one</a></p>
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
