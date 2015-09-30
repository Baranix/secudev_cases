<?php
	session_start();

	require_once '/htmlpurifier-4.7.0/library/HTMLPurifier.auto.php';

	$config = HTMLPurifier_Config::createDefault();
	$config->set('HTML.Allowed', 'p,b,a[href],i,u,div,table,tr,td,span,ul,li,ol,img[src]');
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

	$mid = $_GET["message"];
	$mid = $purifier->purify($mid);

	if(isset($_SESSION["user"]))
	{
		include 'connect.php';
		$con = mysqli_connect("localhost", $db_username, $db_password, $db_name);
		if( mysqli_connect_errno() )
		{
			echo "<br>Database connection failed: " . mysqli_connect_error() . "<br>";
		}
		else
		{
			$q = "SELECT is_superuser FROM user WHERE id=" . $_SESSION["user"];

			$result = mysqli_query($con, $q);
			if (mysqli_num_rows($result) > 0)
			{
				while($row = mysqli_fetch_assoc($result))
				{
					$is_superuser = $row["is_superuser"];
				}

				$q = "SELECT id
					FROM message
					WHERE id =" . $mid;

				if(!$is_superuser)
					$q = $q . " AND message.user=" . $_SESSION["user"];

				$result = mysqli_query($con, $q);

				if (mysqli_num_rows($result) > 0)
				{
					while($row = mysqli_fetch_assoc($result))
					{
						
						$q = "DELETE FROM message WHERE id=" . $row["id"];

						$result2 = mysqli_query($con, $q);

					}
				}
				else
					echo "<div class=\"content\"><center>You cannot edit a message that is not yours!</center></div>";
			}
			else
			{
				echo "An error has occurred. Please <a href=\"login.html\">log in</a> again.";
			}
		}
	}

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

	mysqli_close($con);

	redirect($url);

?>