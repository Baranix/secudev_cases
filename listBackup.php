<?php
	session_start();

	include 'header.php';

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

			if( mysqli_num_rows($result) > 0 )
			{
				while($row = mysqli_fetch_assoc($result))
				{
					$superuser = $row["is_superuser"];
				}
			}
			else
			{
				echo "An error has occured, please <a href=\"login.html\">log in</a> again.";
			}

			if( $superuser )
			{
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h1>List of Backup Files</h1>

		<div class="content">
			<center>
			<a class="button large" href="profile.php">Back to Profile</a>
			<br><br><br>
			<table id="files">
				<?php
					$q = "SELECT * FROM backup_message";

					$result = mysqli_query($con, $q);

					if( mysqli_num_rows($result) > 0 )
					{
						while($row = mysqli_fetch_assoc($result))
						{
							echo "<tr><td>";
							echo $row["id"];
							echo "</td><td>";
							echo "<a href=\"b_messages/backup_" . $row["id"] . ".csv\">backup_" . $row["id"] . ".csv</a>";
							echo "</td><td>";
							echo $row["created_on"];
							echo "</td></tr>";
						}
					}
					else
					{
						echo "An error has occured, please <a href=\"login.html\">log in</a> again.";
					}
				?>
			</table>
			</center>
		</div>
	</body>
</html>

<?php
			}
			else
			{
				redirect("profile.php");
			}
		}
		mysqli_close($con);
	}
	else
	{
		echo "Please <a href=\"login.html\">log in</a> again!";
	}

?>