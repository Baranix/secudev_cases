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
									//echo "<script>console.log('". (int)$row['id']%4 . "');</script>";
									if( (int)$row['id']%4 == 0 )
									{
										echo "</tr><tr>";
									}
									echo "<td><form class=\"itemCard\" action=\"addToCart.php\" method=\"post\">";
									echo "<input type=\"hidden\" name=\"item_id\" value=\"" . $row["id"] . "\">";
									echo "<div class=\"itemTitle\">" . $row["name"] . "</div>";
									echo "<div class=\"itemImage\"><img src=\"" . $row["image"] . "\"></div>";
									echo "<div class=\"itemDesc\">" . $row["desc"] . "</div>";
									echo "<div class=\"itemPrice\">P" . $row["price"] . " <input type=\"submit\" class=\"button medium cart\" value=\"Add to Cart\"></div>";
									echo "</form></td>";
								}
								echo "</tr></table>";
							}
							else
							{
								echo "No items in stock!";
							}

							if($superuser)
							{
						?>
			</div>
			<div class="content">
			<form action="addItem.php" method="post" enctype="multipart/form-data">
				<b>Add items</b><br><br>
				<table>
					<tr>
						<td>
							Name:
						</td>
						<td>
							<input type="text" name="name">
						</td>
					</tr>
					<tr>
						<td>
							Price:
						</td>
						<td>
							<input type="number" name="price" min="1.00" max="50000.00">
						</td>
					</tr>
					<tr>
						<td>
							Description:
						</td>
						<td>
							<textarea name="description" rows="3" columns="30"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							Select image to upload:
						</td>
						<td>
							<input type="file" name="image" id="image">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="button large" value="Add Item" name="submit">
						</td>
					</tr>
				</table>
			</form>
						<?php
							}

							mysqli_close($con);
						} // end secure database connection

					} // end check if user is logged in
				?>
		</div>
	</body>
</html>