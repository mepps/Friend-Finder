<?php
require_once("include/connection.php");
require_once ("friend_class.php");
?>
<!doctype html>
<link rel="stylesheet" type="text/css" href="css/friend_finder.css" />
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Friend Finder</title>
	<script src="jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".add_friend").submit(function(){
				$.post(
					$(this).attr('action'), $(this).serialize(), function(data){
						$('#' + data.id).text(data.users_table_action);
						$('#friends_table').append(data.friends_table_append);
					}, 'json');
				return false;
			});
		});
	</script>
</head>
<body>
	<div id="wrapper">
		<h1>Friend Finder</h2>
		<h3>Welcome, <?=$_SESSION['user']->first_name?>!</h3>
		<h3><?=$_SESSION['user']->email?></h3>
		<h2>List of Friends</h2>
		<table id="friends_table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
				</tr>
			</thead>
			<tbody>

<?php
foreach ($_SESSION['user']->friends as $friend)
{?>
				<tr>
					<td><?=$friend->first_name." ".$friend->last_name?></td>
					<td><?=$friend->email?></td>
				</tr>

<?php
}
 ?>
		</table>
		<h2>Users Subscribed to Friend Finder</h2>
		<table id="users_table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
<?php 
foreach ($new_friends as $new_friend)
{
?>
				<tr>
					<td><?=$new_friend->first_name . " " . $new_friend->last_name?></td>
					<td><?=$new_friend->email?></td>
					<td id='<?=$new_friend->id?>'>
<?php
	if ($new_friend->id == $_SESSION['user']->id)
	{
		echo "This is you.";
	}
	else if ($new_friend->is_friend($_SESSION['user'])) 
	{
		echo $friend_message;
	} 
	else
	{?>
						<form class="add_friend" action="process.php" method="post">
							<input type="hidden" name="friend" value=<?=serialize($new_friend)?> />
							<input type="hidden" name="action" value="add_friend" />
							<input type="submit" value="Add a Friend" />
						</form>
<?php
	}
?>	
					</td>
				</tr>
<?php
}
?>
		</table>

		<form action="process.php" method="post">
			<input type="hidden" name="action" value="logoff" />
			<input type="submit" id="logoff" value="Log Off">
		</form>

	</div>
</body>
</html>

