<?php
	session_start();

	require_once '/htmlpurifier-4.7.0/library/HTMLPurifier.auto.php';

	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', 'p,b,a[href],i,u,div,table,tr,td,span,ul,li,ol,img[src]');
	$config->set('HTML.AllowedAttributes', 'src,alt,a.href');
	$purifier = new HTMLPurifier($config);
	//$clean_html = $purifier->purify($dirty_html);

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

	$mid = $_POST["mid"];
	$mid = $purifier->purify($mid);
	$message = $purifier->purify($message);
	$message = mysqli_real_escape_string($con, $_POST["message"] );
	$url = "profile.php?";
	if(isset($_GET["u"]))
	{
		$u = $_GET["u"];
		$url = $url . "u=" . $u;
	}

	$url = $purifier->purify($url);
		
	/*if(isset($_GET["page"]))
	{
		if(isset($u))
			$url = $url . "&";
		$page = $_GET["page"];
		$url = $url . "page=" . $page;
	}*/

	$q = "UPDATE message SET message='" . $message . "' WHERE id=". (int) $mid;

	$result = mysqli_query($con,$q);

	mysqli_close($con);

	redirect($url);
?>