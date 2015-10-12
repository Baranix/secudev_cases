<?php
	session_start();

	include 'header.php';
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="content">
			<br><br>
			<center><a href="profile.php" class="button large">Return to Profile</a></center>
			<div id="message_boards">

<?php
	if( isset($_SESSION["user"]) )
	{
		if( isset($_POST['message']) )
		{
			$message = trim( $_POST['message'] );
			$message = $purifier->purify($message);

			//connect to database
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

				if(isset($_GET["page"]))
				{
					$page = $_GET["page"];
				}
				else
				{
					$page = 1;
				}

				$message = mysqli_real_escape_string($con, $message);
				$q = "SELECT COUNT(id)
					FROM message
					WHERE message LIKE '%".$message."%'";

				$result = mysqli_query($con, $q);
				$row = mysqli_fetch_row($result);

				$num_messages = $row[0];
				$num_pages = ceil( $num_messages / 10 );

				// create conditions based on advanced search parameters
				$filter = "";
				foreach($_POST['searchBy'] as $k => $searchBy)
				{
					$operator = "";
					if( $_POST['operator'][$k] == 1)
					{
						$operator = "AND";
					}
					else
						if( $_POST['operator'][$k] == 2)
						{
							$operator = "OR";
						}
						else
						{
							echo "<center><i>Error: Did you mean OR '" . $_POST['username'][$k] . "'?</i></center><br><br>";
							$operator = "OR";
						}

				    if( $searchBy == 1 )
			  	    {
			  	    	$filter .= $operator . " user.username = '" . $_POST['username'][$k] . "' ";
			  	    }
			  	    else
			  	    	if( $searchBy == 2 )
			  	    	{
		  	    			$dateCondition = "";
		  	    			if( $_POST['dateCondition'][$k] == 1)
	  	    				{
	  	    					$dateCondition = ">=";
	  	    				}
	  	    				else
	  	    					if( $_POST['dateCondition'][$k] == 2)
		  	    				{
		  	    					$dateCondition = "<=";
		  	    				}
	  	    				else
	  	    					if( $_POST['dateCondition'][$k] == 3)
		  	    				{
		  	    					$dateCondition = "=";
		  	    				}
	  	    				else
	  	    					if( $_POST['dateCondition'][$k] == 4)
		  	    				{
		  	    					$dateCondition = "BETWEEN";
		  	    				}
		  	    			else
		  	    			{
		  	    				echo "<center><i>Error: Did you mean '= ". $_POST['date1'][$k] . "'?</i></center>";
		  	    			}


		  	    			if( $dateCondition != "BETWEEN" )
		  	    			{
		  	    				$filter .= $operator . " date(message.created_on) " . $dateCondition . " '" . $_POST['date1'][$k] . "' ";
		  	    			}
		  	    			else
		  	    				if( $dateCondition == "BETWEEN" )
		  	    				{
		  	    					$filter .= $operator . " date(message.created_on) " . $dateCondition . " '" . $_POST['date1'][$k] . "' AND '" . $_POST['date2'][$k] . "' ";
		  	    				}
		  	    		}
				}

				echo "<script>console.log(\"" . $message . "\"); console.log(\"" . $filter ."\");</script>";
				//to be searched in DB message
				$q = "SELECT message.id AS mid, user.id AS uid, user, message, created_on, edited_on, first_name, username, date_joined
					FROM message, user
					WHERE (message.user = user.id)
					    AND ( (message LIKE '%".$message."%') " . $filter . " )
					ORDER BY created_on DESC
					LIMIT " . ($page - 1) * 10 . " , 10";

				//echo "<script>console.log(\"" . $q . "\");</script>";

				//$q = "SELECT id, message FROM message WHERE message LIKE '%".$message."%'";
				$result = mysqli_query($con, $q);
				$found = mysqli_num_rows($result);

				if ($found==0)
				{
					echo "<center>Sorry, there are no matching results.</center>";
				}
				else
				{
					echo "<table>";
					while ($row = mysqli_fetch_assoc($result)) {
						//$message = $row['message'];
						//$id = $row['mid'];
						//display results
						//echo "<li><a href=\"displayMessage.php?id=" . $id . "\">" .$message. " </a></li>";
						echo "<tr class=\"message_row\">";
						echo "<td class=\"message_poster\">";
						echo "Post ID: <a href=\"displayMessage.php?id=" . $row["mid"] . "\">" . $row["mid"] . "</a><br>";
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
				}
			}
		}
		else
		{
			echo "<center>Error searching for posts!</center>";
			//echo "<br>Go back to <a href=\"profile.php\">profile</a>.</center>";
			//redirect("profile.php");
		}
	}
	else
	{
		//echo "Form submission error.";
		redirect("login.html");
	}

?>

			</div>
		</div>
	</body>
</html>