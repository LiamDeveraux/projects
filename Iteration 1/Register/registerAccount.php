<!-- Author : Ting Kee Chung -->

<?php 
	if(isset($_SESSION['logged_in'])){
		if($_SESSION['logged_in'] === "user"){
			header("Location:../Main%20Pages/forum.php");
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
	<link rel="stylesheet" type="text/css" href="../../General files/main.css">
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
</head>
<body>
	<div class="loginContainer">
		<form class="loginForm" method="post" action="register_proc.php">
			<a href="../Login/login.php"><img class="logoImage" src="../../General files/logo.png"></a>
			<div style="position: relative;">
				<input class="loginInput" type="text" name="email" placeholder="Email">
				<span class="focus-input"></span>
			</div>
			<div style="position: relative;">
				<input class="loginInput" type="text" name="username" placeholder="Username">
				<span class="focus-input"></span>
			</div>
			<div style="position: relative;">
				<input class="loginInput" type="password" name="password" placeholder="Password">
				<span class="focus-input"></span>
			</div>
			<div style="position: relative;">
				<input class="loginInput" type="password" name="c_password" placeholder="Confirm Password">
				<span class="focus-input"></span>
			</div>
			<?php
				if(isset($_SESSION['reg_error'])){
					echo "<p class='errorMessage'>{$_SESSION['reg_error']}</p>";
				}
			?>
			<button class="loginButton"type="submit">Register</button>
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
