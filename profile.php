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
				if(isset($_GET["u"]))
					$u = $_GET["u"];
				else
					$u = $_SESSION["user"];

				include 'connect.php';
				$con = mysqli_connect("localhost", $db_username, $db_password, $db_name);
				if( mysqli_connect_errno() )
				{
					echo "<br>Database connection failed: " . mysqli_connect_error() . "<br>";
				}
				else
				{
					echo "<div id=\"logout_form\"><form action=\"logout.php\" method=\"POST\">";
					echo "<input type=\"hidden\" name=\"logout\" value=\"" . $_SESSION["user"] . "\"><input type=\"submit\" class=\"button medium\" value=\"Logout\">";
					echo "</form></div>"; /*href=\"logout.php\"*/

					$q = "SELECT username, s.salutation AS salutation, last_name, first_name, g.gender AS gender, birthdate, about
						FROM user, salutation AS s, gender AS g
						WHERE user.id=" . $u . " AND s.id = user.salutation AND g.id = user.gender;";

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
						}

						$q = "SELECT is_superuser FROM user WHERE id=" . $_SESSION["user"];
						$result2 = mysqli_query($con, $q);

						if( mysqli_num_rows($result2) > 0 )
						{
							while($row = mysqli_fetch_assoc($result2))
							{
								$superuser = $row["is_superuser"];
							}
						}

						echo "<br><br>";
						if( $u == $_SESSION["user"] )
							echo "<a class=\"button large\" href=\"editProfilePage.php\">Edit Profile</a>";
						else
							echo "<a class=\"button large\" href=\"?u=" . $_SESSION["user"] . "\">Back to Your Profile</a> ";
						if( $superuser )
						{
							echo "<a class=\"button large\" href=\"adminRegistrationPage.php\">Admin Registration</a>";
							echo "<a class=\"button large\" href=\"listBackup.php\">List of Backups</a>";
						}
						echo "<br><br><br>";

		?>

	</div>
		<div id="message_boards" class="content">

			<div id="post_msg">
				<form action="post.php" method="POST" id="form_post_msg">
					Post a Message:<br>
					<textarea rows="4" cols="100" name="message"></textarea>
					<br>
					<input id="submit" class="button large" type="Submit" value="Post">
				</form>
			</div>

			<!--search bar-->
			<div id="search_bar">
				<form class="searchform">
 			 		<input type="text" placeholder="search here :)">
 					<input id="basicSearch" class="button medium" type="Submit" value="Search">
 					<input id="advSearch" class="button medium" type="Submit" value="Advance Search">
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

						$q = "SELECT message.id AS mid, user.id AS uid, user, message, created_on, edited_on, first_name, username, date_joined
							FROM message, user
							WHERE message.user = user.id
							ORDER BY created_on DESC
							LIMIT " . ($page - 1) * 10 . " , 10";

						$result = mysqli_query($con, $q);

						echo "<table>";
						while($row = mysqli_fetch_assoc($result))
						{
							echo "<tr class=\"message_row\">";
							echo "<td class=\"message_poster\">";
							//echo "Post ID: " . $row["mid"] . "<br>";
							echo "First Name: <a href=\"?u=" . $row["uid"] . "\">" . $row["first_name"] . "</a><br>";
							echo "Username: <a href=\"?u=" . $row["uid"] . "\">" . $row["username"] . "</a><br>";
							echo "Date Joined: " . $row["date_joined"] . "<br>";
							echo "</td><td class=\"message_content\">";
							echo "<span class=\"message_dateposted\">Date Posted: " . $row["created_on"] . "</span>";
							if ( $row["edited_on"] != NULL )
								echo "<span class=\"message_datedited\">Date Edited: " . $row["edited_on"] . "</span>";
							echo "<span class=\"message\">" . $row["message"] . "</span>";
							if ( $row["user"] == $_SESSION["user"] || $superuser )
							{
								echo "<div class=\"edit_delete\">";
								echo "<a class=\"button small\" href=\"updateMessage.php?message=" . $row["mid"] . "\">Edit</a> ";
								echo "<a class=\"button small\" href=\"deleteMessage.php?message=" . $row["mid"] . "\">Delete</a>";
								echo "</div>";
							}
							echo "</td></tr>";

						}
						echo "</table>";

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
								echo "<a class=\"button round\" href=\"?u=" . $u . "&page=" . $i . "\">" . $i . "</a> ";
							}

						}

						if ( $page < $num_pages && $page != 0 )
						{
							echo " >>";

						}
						echo "</div>";

						if( $superuser )
						{
							echo "<a class=\"button large\" href=\"backupMessages.php\">Backup Messages</a>";
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
		</div>

	</body>
</html>