<?php
	session_start();

	function redirect($url)
	{
		// Redirect to another page when done
		ob_start();
		sleep(2);
		header("Location: " . $url);
		ob_end_flush();
		die();
	}

	if(isset($_SESSION["user"]))
	{
		include 'connect.php';
		$con = mysqli_connect("localhost", $db_username, $db_password, $db_name);
		if( mysqli_connect_errno() )
		{
			mysqli_close($con);
			redirect("login.html");
		}
		else
		{
			$q = "SELECT username
				FROM user
				WHERE id=" . $_SESSION["user"];

			$result = mysqli_query($con, $q);

			if( mysqli_num_rows($result) == 0 )
			{
				mysqli_close($con);
				redirect("login.html");
			}
		}
	}
	else
	{
		mysqli_close($con);
		redirect("login.html");
	}

	$message = mysqli_real_escape_string($con, $_POST["message"] );

	$q = "INSERT INTO `message` ( user, message ) VALUES ( " . $_SESSION["user"] . ", '" . $message . "' );";

	$result = mysqli_query($con,$q);

	mysqli_close($con);

	redirect("loginLandingPage.php");
?>