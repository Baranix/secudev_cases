<?php
	session_start();

	require_once '/htmlpurifier-4.7.0/library/HTMLPurifier.auto.php';

	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	//$clean_html = $purifier->purify($dirty_html);

	$mid = $_GET["message"];
	$mid = $purifier->purify($mid);

?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="content">
		<?php
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

						$q = "SELECT message
							FROM message
							WHERE message.id =" . $mid;

						if(!$is_superuser)
							$q = $q . " AND message.user=" . $_SESSION["user"];

						$result = mysqli_query($con, $q);

						if (mysqli_num_rows($result) > 0)
						{
							while($row = mysqli_fetch_assoc($result))
							{
								$message = $row["message"];
							}
						}
					}
					else
					{
						echo "An error has occurred. Please <a href=\"login.html\">log in</a> again.<br>";
					}
				}
			}
			else
			{
				echo "Please <a href=\"login.html\">log in</a> again.<br>";
			}

			if(isset($message))
			{
		?>
			<div id="post_msg">
				<form action="postUpdate.php" method="POST" id="form_post_msg">
					Edit Message:<br>
					<textarea rows="4" cols="100" name="message"><?php echo $message; ?></textarea>
					<input type="hidden" value="<?php echo $mid; ?>" name="mid">
					<br>
					<input id="submit" class="button large" type="Submit" value="Post">
				</form>
			</div>

		<?php
			}
			else
				echo "You cannot edit a message that is not yours!<br>";

	 	?>
	 	</div>
	</body>
</html>