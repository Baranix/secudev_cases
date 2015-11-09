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
				<a class="button large" href="cart.php"> View Cart </a>
				<br><br>

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
									echo "<td><div class=\"itemCard\">";
									echo "<input type=\"hidden\" name=\"item_id\" value=\"" . $row["id"] . "\">";
									echo "<div class=\"itemTitle\"><a href=\"item.php?id=" . $row["id"] . "\">" . $row["name"] . "</a></div>";
									echo "<div class=\"itemImage\"><img src=\"" . $row["image"] . "\"></div>";
									echo "<div class=\"itemDesc\">" . $row["description"] . "</div>";
									echo "<div class=\"itemPrice\">P" . $row["price"] . " <a href=\"addToCart.php?item=" . $row["id"] . "\" class=\"button medium cart\">Add to Cart</a></div>";
									if($superuser)
									{
										echo "<div class='admin'>";
										echo "<a href='editItem.php?id=" . $row["id"] . "' class='button medium'>Edit</a> ";
										echo "<a href='deleteItem.php?id=" . $row["id"] . "' class='button medium'>Delete</a>";
										echo "</div>";
									}
									echo "</div><br><br>";


									echo "</td>";

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
			<div id="store" class="content">
			<form action="addItem.php" method="post" enctype="multipart/form-data">
				<b>Add items</b><br><br>
				<table>
					<tr>
						<td>
							Name:
						</td>
						<td>
							<input type="text" name="name" class="itemField">
						</td>
					</tr>
					<tr>
						<td>
							Price:
						</td>
						<td>
							<input type="number" name="price" min="1.00" max="50000.00" class="itemField">
						</td>
					</tr>
					<tr>
						<td>
							Description:
						</td>
						<td>
							<textarea name="desc" rows="3" columns="30" class="itemField"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							Select image to upload:
						</td>
						<td>
							<input type="file" name="image" id="image" class="itemField">
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