<?php
	session_start();

	include 'header.php';

	$id = $_GET["id"];
	$id = $purifier->purify($id);

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
					$superuser = $row["is_superuser"];
				}

				if ($superuser)
				{
					$q = "DELETE FROM item WHERE id=" . $id;

					$result2 = mysqli_query($con, $q);
				}
				else
					echo "<div class=\"content\"><center>You cannot perform this action!</center></div>";
			}
			else
			{
				echo "An error has occurred. Please <a href=\"login.html\">log in</a> again.";
			}
		}
	}

	mysqli_close($con);

	redirect("store.php");

?>