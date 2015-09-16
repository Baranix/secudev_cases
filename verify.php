<?php
	session_start();

	// Strip all inputs of possible HTML tags
	$username = strip_tags( $_POST["userName"] );
	$password = md5( $_POST["password"] );

	function redirect($url)
	{
		// Redirect to another page when done
		ob_start();
		sleep(2);
		header("Location: " . $url);
		ob_end_flush();
		exit();
	}
	
	include 'connect.php';
	$con = mysqli_connect("localhost", $db_username, $db_password, $db_name);
	if( mysqli_connect_errno() )
	{
		// Connection error
		echo "<br>Database connection failed: " . mysqli_connect_error() . "<br>";
	}
	else
	{
		// Connection success

		// mysqli_real_escape_string() allows quotation marks, apostrophes, and such to
		// be inputted without messing with the SQL statement
		$username = mysqli_real_escape_string($con, $username);
		$q = "SELECT id FROM user WHERE username='" . $username . "' AND password='" . $password . "'";
		
		$result = mysqli_query($con, $q);
		
		if( mysqli_query($con,$q) )
		{
			while($row = mysqli_fetch_assoc($result))
			{
				$_SESSION["user"] = $row["id"];
			}
		}
		else
		{
			echo "Wrong username or password!";
			echo $username;
			echo $password;
			sleep(5);
		}
	}
	mysqli_close($con);
	
	redirect("loginLandingPage.php");
?>