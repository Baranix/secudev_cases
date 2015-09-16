<?php
	session_start();
	
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>

		<h1>Profile</h1>

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
					$q = "SELECT username, salutation, last_name, first_name, gender, birthdate, about, is_superuser
						FROM user
						WHERE id=" . $_SESSION["user"];

					$result = mysqli_query($con, $q);

					if (mysqli_num_rows($result) > 0)
					{
						while($row = mysqli_fetch_assoc($result))
						{
							echo "Username: " . $row["username"];
							echo "<br>Name: " . $row["salutation"] . " " . $row["last_name"] . ", " . $row["first_name"];
							echo "<br>Gender: " . $row["gender"];
							echo "<br>Birthdate: " . $row["birthdate"];
							echo "<br>About me:<br>";
							echo $row["about"];
							$superuser = $row["is_superuser"];
						}

						echo "<form action=\"logout.php\" method=\"POST\"><input id=\"submit\" type=\"submit\" value=\"Logout\"></form>";
					
						echo "<br><br><a href=\"editUserProfile.php\">Edit User's Profile</a>";
					
						if($superuser)
						{
							echo "<br><br><a href=\"adminRegistrationPage.php\">Admin Page</a>";
						}
						
					}
					else
					{
						echo "An error has occured, please <a href=\"login.html\">log in</a> again.";
					}
				}
				mysqli_close($con);
			}
			else
			{
				echo "Please <a href=\"login.html\">log in</a> again!";
			}
			

		?>

	</div>

		<div class="message_boards">

			<textarea name="POST" rows="8" cols="95">Enter message Here.</textarea>
			<br>
			<br>
			<input id="submit" type="submit" value="POST">
			<br>
			<br>

			<div class="user">

				
				First Name:
				<br>
				<br>
				User Name:
				<br>
				<br>
				Date Joined:
				<br>
				<br>
				<br>

			</div>	

			<div class="message">

				Date Posted: 
				<br>
				<br>
				Post:
				<br>
				<br>
				Date Edited:
				<br>
				<br>
				<u>Edit</u> <u>Delete</u>
				
			</div>
			
		</div>		






	</body>
</html>