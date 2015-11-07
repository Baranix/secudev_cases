<?php
	session_start();

	include 'header.php';
?>

<html>
	<head>
		<title>Store</title>

		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="jquery.js"></script>
	</head>
	<body>

		<h1>Store</h1>

		<div class="content">

				<?php

					if( isset($_SESSION["user"]) )
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
							} // end check if superuser

							/* DISPLAY ITEMS IN STORE */

							$q = "SELECT * FROM item";
							$result = mysqli_query($con, $q);
							if( mysqli_num_rows($result) > 0 )
							{
								echo "<table id=\"store\"><tr>";
								while($row = mysqli_fetch_assoc($result))
								{
									echo "<script>console.log('". (int)$row['id']%4 . "');</script>";
									if( (int)$row['id']%4 == 0 )
									{
										echo "</tr><tr>";
									}
									echo "<td><div class=\"itemCard\">";
									echo "<div class=\"itemTitle\">" . $row["name"] . "</div>";
									echo "<div class=\"itemImage\"><img src=\"" . $row["image"] . "\"></div>";
									echo "<div class=\"itemDesc\">" . $row["desc"] . "</div>";
									echo "<div class=\"itemPrice\">P" . $row["price"] . " <button class=\"button medium cart\">Add to Cart</button></div>";
									echo "</div></td>";
								}
								echo "</tr></table>";
							}
							else
							{
								echo "No items in stock!";
							}

							mysqli_close($con);
						} // end secure database connection

					} // end check if user is logged in
				?>

		</div>

	</body>
</html>