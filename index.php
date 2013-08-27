<?php
session_start();
if (isset($_SESSION['logged_in']) and $_SESSION['logged_in'])
{
	header("location: friend_finder_home.php");
}?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Welcome to Friend Finder</title>
	<link rel="stylesheet" type="text/css" href="css/friend_finder.css" />
	<script src="jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".check_errors").submit(function(){
				$.post(
					$(this).attr('action'), $(this).serialize(), function(data){
						$('#messages').html(data.messages);
						if (data.login == true)
						{
							window.location.replace



							("friend_finder_home.php");
						}
					}, "json")
				return false;
			});
		});
	</script>
</head>
<body>
	<div id="wrapper">
		<h1>Friend Finder</h1>
		<form id="register" class="check_errors" action="process.php" method="post">
			<h2>Registration</h2>
			<label for="first_name">First Name</label>
			<input type="text" name="first_name" id="first_name" />
			<label for="last_name">Last Name</label>
			<input type="text" name="last_name" id="last_name" />
			<label for="email">Email</label>
			<input type="text" name="email" id="email" />
			<label for="password">Password</label>
			<input type="password" name="password" id="password" />
			<label for="confirm_password">Confirm Password</label>
			<input type="password" name="confirm_password" id="confirm_password" />
			<input type="hidden" name="action" value="register" />
			<input type="submit" value="Register" />
		</form>
		<img id="welcome" src="images/welcome-friends.jpg" />
		<form id="login" class="check_errors" action="process.php" method="post">
			<h2>Login</h2>
			<label for="login_email">Email</label>
			<input type="text" name="email" id="login_email" />
			<label for="login_password">Password</label>
			<input type="password" name="password" id="login_password" />
			<input type="hidden" name="action" value="login" />
			<input type="submit" value="Log In" />
		</form>
		<div id="messages"></div>
		<div class="clear"></div>
	</div>
</body>
</html>