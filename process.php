<?php
	include_once("connection.php");
	session_start();

	class Process
	{
		var $connection;

		public function __construct()
		{
			$this->connection = new Database();

			if(isset($_POST['action']) and $_POST['action'] == "login")
			{
				$this->loginAction();
			}
			
			if(isset($_POST['action']) and $_POST['action'] == "create_account")
			{
				$this->create_account();
			}
			
			if(isset($_POST['action']) and $_POST['action'] == "logout")
			{
				$this->logoutAction();
			}

			if(isset($_POST['action']) and $_POST['action'] == "add_friend")
			{
				$this->addFriend();
			}

		}

		public function loginAction()
		{
			$query = "SELECT * FROM users WHERE email = '{$_POST['login_email']}'";
			$db_record = $this->connection->fetch_record($query);
			// var_dump($db_record);
			if(md5($_POST['login_password'])==$db_record['password'])
			{
				// echo "<h1>Giggity</h1>";
				$_SESSION['logged_in'] = true;
				$_SESSION['user']['first_name'] = $db_record['first_name'];
				$_SESSION['user']['last_name'] = $db_record['last_name'];
				$_SESSION['user']['email'] = $db_record['email'];
				$_SESSION['user']['id'] = $db_record['id'];
				header('location:friend.php');
			}
			else
			{	
				// echo "<h1>Fail</h1>";
				$login_fail = '<p id="password_fail" class="alert-danger">Login failure, please try again</p>';
				$_SESSION['login_fail'] = $login_fail;
				header('location:index.php');
			}
		}


		public function create_account()
		{
			$error_count = 0;

			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
				$error_email = '<p id="validate_email" class="alert-success">Email is valid</p>';
			}
			else
			{
				$error_email = '<p id="validate_email" class="alert-danger">Invalid Email</p>';
				$error_count += 1;
			}
			$_SESSION['error_email'] = $error_email;

			
			if(ctype_alpha($_POST['first_name']))
			{
				$error_first_name = '<p id="validate_first_name" class="alert-success">Name is valid</p>';
			}
			else
			{
				$error_first_name = '<p id="validate_first_name" class="alert-danger">Name cannot be blank or contain numbers</p>';
				$error_count += 1;
			}
			$_SESSION['error_first_name'] = $error_first_name;


			if(ctype_alpha($_POST['last_name']))
			{
				$error_last_name = '<p id="validate_last_name" class="alert-success">Name is valid</p>';
			}
			else
			{
				$error_last_name = '<p id="validate_last_name" class="alert-danger">Name cannot be blank or contain numbers</p>';
				$error_count += 1;
			}
			$_SESSION['error_last_name'] = $error_last_name;


			if(strlen($_POST['password'])>=6)
			{
				$error_password = '<p id="validate_password" class="alert-success">Password is valid</p>';
			}
			else
			{
				$error_password = '<p id="validate_password" class="alert-danger">Password must be at least 6 characters</p>';
				$error_count += 1;
			}
			$_SESSION['error_password'] = $error_password;


			if($_POST['password_confirm'] == $_POST['password'] && strlen($_POST['password_confirm'])>=6)
			{
				$error_password_confirm = '<p id="validate_password_confirm" class="alert-success">Password is valid</p>';
			}
			else
			{
				$error_password_confirm = '<p id="validate_password_confirm" class="alert-danger">Password must be at least 6 characters and match</p>';
				$error_count += 1;
			}
			$_SESSION['error_password_confirm'] = $error_password_confirm;


			if($error_count==0)
			{
				$query = "SELECT * FROM users WHERE email = '{$_POST['email']}'";
				$db_users = $this->connection->fetch_all($query);	
				if(count($db_users) > 0)
				{
					$error_duplicate_account =  '<p id="validate_password_confirm" class="alert-danger">This account already belongs to someone</p>';
					$_SESSION['error_duplicate_account'] = $error_duplicate_account;
				}
				else
				{
					$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('".mysql_real_escape_string("{$_POST['first_name']}")."', '".mysql_real_escape_string("{$_POST['last_name']}")."', '".mysql_real_escape_string("{$_POST['email']}")."', '".mysql_real_escape_string(md5($_POST['password']))."', NOW(), NOW())";
					mysql_query($query);

					$_SESSION['create_account_success'] = '<p id="validate_password_confirm" class="alert-success">Account was successfully created!  Please login...</p>';
				}
			}
			header('location:index.php');
		}


		public function logoutAction()
		{
			$_SESSION['logged_in'] = false;
			session_destroy();
			header('location:index.php');	
		}

		// function lists all users
		public function listFriends()
		{
			$query = "SELECT * FROM users WHERE users.id != " . $_SESSION['user']['id'] . " ORDER BY first_name ASC;";
          	return $this->connection->fetch_all($query);
        }

        // function checks if another user is a friend, returns True / False
        public function friendCheck($user_id)
        {
        	$query = "SELECT * FROM friends WHERE friend_id = " . $user_id . " AND user_id = " . $_SESSION['user']['id'];
        	$friends = $this->connection->fetch_record($query);

        	if($friends)
        		return TRUE;
        	else
        		return FALSE;
        }

        // lists friends
        public function friendCheckList()
        {
          	$query = "SELECT * FROM friends LEFT JOIN users ON users.id = friends.friend_id WHERE user_id = " . $_SESSION['user']['id'] . " ORDER BY users.first_name ASC;";
          	return $this->connection->fetch_all($query);
		}

		// adds friend for logged in user and friended user
		public function addFriend()
		{
			// Adds friend for logged in user
			$query = "INSERT INTO friends (user_id, friend_id) VALUES (" . $_POST['id'] . "," . $_POST['friend_id'] . " ), (" . $_POST['friend_id'] . "," . $_POST['id'] . " )";
			// echo $query;
			mysql_query($query);

			header('location:friend.php');	
		}

	}

	$process = new Process();

?>
