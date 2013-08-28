<?php 
require_once ("include/connection.php");
require_once ("friend_class.php");
class Process
{
	function __construct()
	{	

		if ($_POST['action']=='register')
		{
			$messages = $this->registerUser();
		}
		else if($_POST['action']=='login')
		{
			$messages = $this->loginUser();
		}
		else if($_POST['action']=='logoff')
		{
			session_destroy();
			header("location: index.php");
		}
		else if ($_POST['action']=='add_friend')
		{
			$new_friend = unserialize($_POST['friend']);
			$this->add_friend_html($new_friend, $_SESSION['user'], "You are friends.");

		}

		if (isset($messages) and count($messages)>0)
		{	
			$data = array();

			$data['messages'] = "<ul>";
			foreach ($messages as $message)
			{
				if (is_string($message))
					$data['messages'] .= "<li>".$message."</li>";
			}
			$data['messages'] .= "</ul>";
			if (isset($messages['none']) and $messages['none'])
				$data['login'] = true;

			echo json_encode($data);
		}
	}
	
	private function registerUser()
	{
		$errors = array();

		if (empty($_POST['first_name']))
		{
			$errors[] = "Please include first name.";
		}
		else if (is_numeric($_POST['first_name']))
		{
			$errors[] = "First name should not be numeric.";
		}
		if (empty($_POST['last_name']))
		{
			$errors[] = "Please include last name.";
		}
		else if (is_numeric($_POST['first_name'])||is_numeric("last_name"))
		{
			$errors[] = "Name should not be numeric.";
		}		
		if (empty($_POST['email']))
		{
			$errors[] = "Please include email address.";
		}
		else if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false)
		{
			$errors[] = "Please use valid email format.";
		}
		if (empty($_POST['password'])||empty($_POST['confirm_password']))
		{
			$errors[] = "Please confirm a password.";
		}
		else if ($_POST['password'] != $_POST['confirm_password'])
		{
			$errors[] = "Password does not match password confirmation.";
		}
		if (count($errors)==0)
		{
			$query = "INSERT INTO users (first_name, last_name, email, password, created_at) VALUES ('".mysql_real_escape_string($_POST['first_name'])."', '".mysql_real_escape_string($_POST['last_name'])."', '".$_POST['email']."', '". md5($_POST['password'])."', NOW());";
			mysql_query($query);
			$errors[] = "<span class='bold'>User successfully created.</span>";
		}

		return $errors;


	}

	private function loginUser()
	{
		$errors = array();
		if (empty($_POST['email']))
		{
			$errors[] = "Please use valid email address to login.";
		}
		else if (empty($_POST['password']))
		{
			$errors[] = "Please enter password to login.";
		}
		else
		{
			$query = "SELECT * FROM users LEFT JOIN friends on users.id=friends.users_id WHERE users.email='".mysql_real_escape_string($_POST['email'])."' AND users.password='".md5($_POST['password'])."';";
			$users = $connect->fetch_all($query);
				if(count($users)>0)
				{
					$_SESSION['logged_in'] = true;
					$_SESSION['user'] = new User($users[0]['first_name'], $users[0]['last_name'], $users[0]['email'], $users[0]['id']);
					$errors['none'] = true;
				}
				else
				{
					$errors[] = "Invalid login information";
				}
		}

		return $errors;
	}

	private function add_friend_html($added_friend, $user, $message)
	{
		$added_friend->become_friends($user);
		$data['id'] = $added_friend->id;
		$data['users_table_action'] = $message;
		$data['friends_table_append'] = 
		"		<tr>
					<td>".$added_friend->first_name." ".$added_friend->last_name."</td>
					<td>".$added_friend->email."</td>
				</tr>";
		echo json_encode($data);
	}
}

$process = new Process;


?>