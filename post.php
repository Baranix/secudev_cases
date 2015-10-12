<?php
	session_start();

	include 'header.php';

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

	$message = $purifier->purify($_POST["message"]);
	$message = mysqli_real_escape_string($con, $message);
	
	$url = "profile.php?";
	if(isset($_GET["u"]))
	{
		$u = $_GET["u"];
		$url = $url . "u=" . $u;
	}
		
	if(isset($_GET["page"]))
	{
		if(isset($u))
			$url = $url . "&";
		$page = $_GET["page"];
		$url = $url . "page=" . $page;
	}

	$url = $purifier->purify($url);
		

	$q = "INSERT INTO `message` ( user, message ) VALUES ( " . $_SESSION["user"] . ", '" . $message . "' );";

	$result = mysqli_query($con,$q);

	mysqli_close($con);

	redirect($url);
?>