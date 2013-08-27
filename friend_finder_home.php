<?php
require_once("include/connection.php");
require_once ("friend_class.php");
session_start();
if (!$_SESSION['logged_in'])
{
	header("location: index.php");
}
$connect = new Database;

//using session variable for primary user to keep consistent across pages

$query = "SELECT * FROM friends LEFT JOIN users on friends.friend_id=users.id WHERE friends.users_id=".$_SESSION['user']->id.";";
$_SESSION['user']->friends = $connect->fetch_all($query);
foreach ($_SESSION['user']->friends  as $key => $friend)
{
	$_SESSION['user']->friends[$key] = new Friend($friend['first_name'], $friend['last_name'], $friend['email'], $friend['friend_id']);
}

$query = "SELECT * from users WHERE 1 ORDER BY users.last_name ASC;";
$new_friends = $connect->fetch_all($query);

foreach ($new_friends as $key => $new_friend)
{
	$new_friends[$key] = new Friend($new_friend['first_name'], $new_friend['last_name'], $new_friend['email'], $new_friend['id']);

}

?>
<!doctype html>
<link rel="stylesheet" type="text/css" href="css/friend_finder.css" />
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Friend Finder</title>
</head>
<body>
	<div id="wrapper">
		<h1>Friend Finder</h2>
		<h3>Welcome, <?=$_SESSION['user']->first_name?>!</h3>
		<h3><?=$_SESSION['user']->email?></h3>
		<h2>List of Friends</h2>
		<table id="friends_list">
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
		<table id="users_list">
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
					<td>
<?php
	if ($new_friend->id == $_SESSION['user']->id)
	{
		echo "This is you.";
	}
	else if ($new_friend->is_friend($_SESSION['user'])) 
	{
		echo "You are friends.";
	} 

	else
	{?>
						<form id="add_friend" action="process.php" method="post">
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

