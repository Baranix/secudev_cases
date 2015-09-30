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
				$q = "SELECT message.id AS mid, user.id AS uid, message, created_on, edited_on, first_name, last_name, username
				FROM message, user
				WHERE message.user = user.id
				ORDER BY created_on DESC";

				$result = mysqli_query($con, $q);

				if( mysqli_num_rows($result) > 0 )
				{
					$allMessages = array();
					$i = 0;
					while($row = mysqli_fetch_assoc($result))
					{
						$message = $row["mid"] . "," . $row["uid"] . "," . $row["username"]
							. "," . $row["first_name"] . "," . $row["last_name"]
							. "," . $row["created_on"] . "," . $row["edited_on"]
							. "," . $row["message"];

						echo "<p>" . $i . ") " . $message . "</p>";
						array_push( $allMessages, $message );
						$i++;
					}

					echo "<br>";
					print_r($allMessages);
					echo "<br><br>";

					$q = "INSERT INTO backup_message (created_on) VALUES (CURRENT_TIMESTAMP);";

					if( mysqli_query($con,$q) )
					{

						$last_id = mysqli_insert_id($con);

						$file = fopen("b_messages\\backup_" . $last_id . ".csv","w");
						$i = 0;
						foreach($allMessages as $message)
						{
							echo "<br>Message " . $i . " written in file.";
							fputcsv($file, explode(',',$message));
							$i++;
						}

						fclose($file);
						
						echo "<script>alert('Backup successful!');</script>";
					}
					else
					{
						echo "Error updating database. Backup not created.";
					}
				}
				else
				{
					echo "Error fetching database. Backup not created.";
				}
			}
			else
			{
				redirect("profile.php");
			}

		}
		mysqli_close($con);

		redirect("profile.php");
	}
	else
	{
		echo "Please <a href=\"login.html\">log in</a> again!";
	}
		
?>