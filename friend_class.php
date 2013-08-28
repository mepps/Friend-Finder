<?php


class User 
{

	var $first_name;
	var $last_name;
	var $email;
	var $id;
	var $friend_id;
	var $friends = array();

	function __construct($new_first_name, $new_last_name, $new_email, $new_id)
	{
		$this->first_name = $new_first_name;
		$this->last_name = $new_last_name;
		$this->email = $new_email;
		$this->id = $new_id;
	}

}

//felt class friend should perform these functions
Class Friend extends User
{

	function is_friend($user)
	{
		$connect = new Database;
		$query = "SELECT * FROM friends WHERE users_id=".$user->id." AND friend_id=".$this->id.";";
		$friend_relationship = $connect->fetch_record($query);
		if ($friend_relationship)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function become_friends($user)
	{
		$query = "INSERT INTO friends (users_id, friend_id) VALUES (".$user->id.", ".$this->id.");";
		mysql_query($query);
		$query = "INSERT INTO friends (users_id, friend_id) VALUES (".$this->id.", ".$user->id.");";
		mysql_query($query);
	}
}

$connect = new Database;

$friend_message = "You are friends.";

session_start();
if (!$_SESSION['logged_in'])
{
	header("location: index.php");
}
//using session variable for primary user to keep consistent

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

