<?php
	session_start();
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="jquery.js"></script>
	</head>
	<body>

		<h1>Profile</h1>


		<div class = "content">
			<table><tr>
			<td>
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
			</td>

			<td>
				<?php
					// badge for posts
					$q = "SELECT COUNT(id) as posts FROM message WHERE user=". $_SESSION["user"];
					$result = mysqli_query($con, $q);

					$num_posts = 0;
					if( mysqli_num_rows($result) > 0 )
	                {
	                	$row = mysqli_fetch_assoc($result);
	                	$num_posts = $row['posts'];
	                }

	                $post_badge = "";
	                if ( $num_posts >= 10 )
	                {
	                	$post_badge = "Socialite";
	                }
	                else if ( $num_posts >= 5 )
	                {
	                	$post_badge = "Chatter";
	                }
	                else if ( $num_posts >= 3 )
	                {
	                	$post_badge = "Participant";
	                }
	                echo "Post Badge: " . $post_badge . "<br>";


	                // badge for donations
	                $q = "SELECT SUM(amount) as total_amount FROM donation WHERE user_id=". $_SESSION["user"];
					$result = mysqli_query($con, $q);

					$donation = 0;
					if( mysqli_num_rows($result) > 0 )
	                {
	                	$row = mysqli_fetch_assoc($result);
	                	$donation = $row['total_amount'];
	                }

	                $donation_badge = "";
	                if ( $donation >= 100 )
	                {
	                	$donation_badge = "Pillar";
	                }
	                else if ( $donation >= 20 )
	                {
	                	$donation_badge = "Contributor";
	                }
	                else if ( $donation >= 5 )
	                {
	                	$donation_badge = "Supporter";
	                }
	                echo "Donation Badge: " . $donation_badge . "<br>";

	                // badge for purchases
	                $q = "SELECT SUM(amount) as total_amount FROM transaction WHERE status=1 AND user_id=". $_SESSION["user"];
					$result = mysqli_query($con, $q);

					$total_amount = 0;
					if( mysqli_num_rows($result) > 0 )
	                {
	                	$row = mysqli_fetch_assoc($result);
	                	$total_amount = $row['total_amount'];
	                }

	                $store_badge = "";
	                if ( $total_amount >= 100 )
	                {
	                	$store_badge = "Elite";
	                }
	                else if ( $total_amount >= 20 )
	                {
	                	$store_badge = "Promoter";
	                }
	                else if ( $total_amount >= 5 )
	                {
	                	$store_badge = "Shopper";
	                }
	                echo "Store Badge: " . $store_badge . "<br>";


	                // badge collection
	                $collection_badge = "";
	                if ( $num_posts >= 10 && $donation >= 100 && $total_amount >= 100 )
	        		{
	        			$collection_badge = "Evangelist";
	        		}
	            	else if ( $donation >= 20 && $total_amount >= 20 )
	        		{
	        			$collection_badge = "Backer";
	        		}
	        		else if ( $num_posts >= 3 && $donation >= 5 && $total_amount >= 5 )
	            	{
	            		$collection_badge = "Explorer";
	            	}
	            	echo "Collection Badge: " . $collection_badge . "<br>";
				?>
			</td>
			</tr></table>
		</div>

		<div id="donations" class="content">
			<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="HG88GZKQQ52YC">
				<input type="hidden" name="currency_code" value="PHP">
				<Input type="hidden" name="tx" value="-pChIE70JdobDMnbhzMyO5NOESfp3PyYispLEgDREdely2lic6oB7MfpS58"/>
				<input type="hidden" name="return" value="http://localhost:81/secudev/cases/donate.php?user=<?php echo $_SESSION["user"]; ?>">
				<input type="image" src="http://i.imgur.com/YVGmin4.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
			<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="736WK2XKHW2TL">
				<input type="hidden" name="currency_code" value="PHP">
				<Input type="hidden" name="tx" value="-pChIE70JdobDMnbhzMyO5NOESfp3PyYispLEgDREdely2lic6oB7MfpS58"/>
				<input type="hidden" name="return" value="http://localhost:81/secudev/cases/donate.php?user=<?php echo $_SESSION["user"]; ?>">
				<input type="image" src="http://i.imgur.com/jvCgtjB.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
			<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="XAA8X3HJ7B35C">
				<input type="hidden" name="currency_code" value="PHP">
				<Input type="hidden" name="tx" value="-pChIE70JdobDMnbhzMyO5NOESfp3PyYispLEgDREdely2lic6oB7MfpS58"/>
				<input type="hidden" name="return" value="http://localhost:81/secudev/cases/donate.php?user=<?php echo $_SESSION["user"]; ?>">
				<input type="image" src="http://i.imgur.com/S5gWMUQ.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
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
				<div id="basicSearchField">
					<form class="searchform" action="basicSearch.php" method="POST">
	 			 		<input type="text" placeholder="search here :)" name="message">
	 					<input id="search" class="button medium" type="Submit" value="Search">
					</form>
					<button id="advSearch" class="button medium" onclick="toggleSearch(1)">Advanced Search</button>
				</div>

				<div id="advSearchField">
					<form class="searchform" action="advSearch.php" method="POST">
						<input type="text" placeholder="Post" name="message">
						<input id="search" class="button medium" type="Submit" value="Search">
						<button type="button" id="basicSearch" class="button medium" onclick="toggleSearch(0)">Back to Basic Search</button>
						<br/><br/>
						<span>
							<select class="operator" name="operator[]"><option value="1">And</option><option value="2">Or</option></select>
							<select class="searchBy" name="searchBy[]" onchange="toggleField(this.parentNode)"><option value="1">Username</option><option value="2">Date</option></select>
							<span class="usernameSearch">
								<input type="text" placeholder="Username" name="username[]">
							</span>
							<!--<span class="postSearch" style="display:none;">
								<input type="text" placeholder="Post" name="post[]">
							</span>-->
							<span class="dateSearch" style="display:none;">
								<select class="dateCondition" name="dateCondition[]" onchange="toggleDates(this.parentNode)"><option value="1">&gt;=</option><option value="2">&lt;=</option><option value="3">=</option><option value="4">Between</option></select>
								<input type="date" class="date1" name="date1[]">
								<input type="date" class="date2" name="date2[]" style="display:none;">
							</span>
						</span>
						<button type="button" class="button medium" onclick="addSearchParams(this)">Add</button>
					</form>
				</div>
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

		<script src="advSearch.js"></script>
	</body>
</html>