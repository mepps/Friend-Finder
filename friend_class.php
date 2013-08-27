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