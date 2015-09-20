<?php
	session_start();

?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>

		<h1>Your Profile</h1>

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

						echo "<br><br>";
						echo "<a class=\"button medium\" href=\"editProfilePage.php\">Edit Profile</a> ";
						echo "<a class=\"button medium\" href=\"logout.php\">Logout</a>";

						if($superuser)
						{
							echo "<br><br><a href=\"adminRegistrationPage.php\">Admin Page</a>";
						}
		?>

	</div>
		<div id="message_boards" class="content">

			<div id="post_msg">
				<form action="post.php" method="POST" id="form_post_msg">
					Post a Message:<br>
					<textarea rows="4" cols="100" name="message"></textarea>
					<br>
					<input id="submit" class="button medium" type="Submit" value="Post">
				</form>
			</div>
			<div id="messages">
		<?php
						if(isset($_GET["page"]))
						{
							$page = $_GET["page"];
						}
						else
						{
							$page = 1;
						}

						$q = "SELECT COUNT(id)
							FROM message";

						$result = mysqli_query($con, $q);
						$row = mysqli_fetch_row($result);

						$num_messages = $row[0];
						$num_pages = ceil( $num_messages / 10 );

						$q = "SELECT message.id, user, message, created_on, edited_on, first_name, username, date_joined
							FROM message, user
							WHERE message.user = user.id
							ORDER BY created_on DESC
							LIMIT " . ($page - 1) * 10 . " , 10";

						$result = mysqli_query($con, $q);

						while($row = mysqli_fetch_assoc($result))
						{
							echo "<div class=\"message_row\">";
							echo "<div class=\"message_poster\">";
							echo "Post ID: " . $row["id"] . "<br>";
							echo "First Name: " . $row["first_name"] . "<br>";
							echo "Username: " . $row["username"] . "<br>";
							echo "Date Joined: " . $row["date_joined"] . "<br>";
							echo "</div><div class=\"message_content\">";
							echo "<span class=\"message_dateposted\">Date Posted: " . $row["created_on"] . "</span>";
							echo "<span class=\"message\">" . $row["message"] . "</span>";
							if ( $row["edited_on"] != NULL )
								echo "<span class=\"message_datedited\">Date Edited: " . $row["edited_on"] . "</span>";
							if ( $row["user"] == $_SESSION["user"] || $superuser )
							{
								echo "<div class=\"edit_delete\">";
								echo "<a class=\"button small\" href=\"updateMessage.php?message=" . $row["id"] . "\">Edit</a> ";
								echo "<a class=\"button small\" href=\"deleteMessage.php?message=" . $row["id"] . "\">Delete</a>";
								echo "</div>";
							}
							echo "</div></div>";

						}
						echo "<div id=\"pagination\">";
						if ( $page > 1)
						{
							echo "<< ";

						}

						for($i=1; $i <= $num_pages; $i++)
						{
							if ( $i == $page )
							{
								echo $i . " ";
							}
							else
							{
								echo "<a class=\"button round\" href=\"loginLandingPage.php?page=" . $i . "\">" . $i . "</a> ";
							}

						}

						if ( $page < $num_pages && $page != 0 )
						{
							echo " >>";

						}
						echo "</div>";
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
		</div>

	</body>
</html>