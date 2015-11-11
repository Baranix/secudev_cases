<?php
	session_start();

	include 'header.php';

	$id = $_GET["id"];
	$id = $purifier->purify($id);
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
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
					$q = "SELECT is_superuser FROM user WHERE id=" . $_SESSION["user"];

					$result = mysqli_query($con, $q);
					if (mysqli_num_rows($result) > 0)
					{
						while($row = mysqli_fetch_assoc($result))
						{
							$superuser = $row["is_superuser"];
						}

						$q = "SELECT name, price, description, image
							FROM item
							WHERE item.id =" . $id;

						$result = mysqli_query($con, $q);

						if (mysqli_num_rows($result) > 0)
						{
							while($row = mysqli_fetch_assoc($result))
							{
								$name = $row["name"];
								$price = $row["price"];
								$desc = $row["description"];
								$image = $row["image"];

							}
						}
					}
					else
					{
						echo "An error has occurred. Please <a href=\"login.html\">log in</a> again.<br>";
					}
				}
			}
			else
			{
				echo "Please <a href=\"login.html\">log in</a> again.<br>";
			}

			if($superuser)
			{
		?>

			<div id="editItem">
				<form action="updateItem.php" method="post" enctype="multipart/form-data">
				<input type="hidden" value="<?php echo $id; ?>" name="id">
				<div class="title">Edit Item</div><br>
				<table>
					<tr>
						<td>
							Name:
						</td>
						<td>
							<input value="<?php echo $name; ?>" type="text" name="name" class="itemField">
						</td>
					</tr>
					<tr>
						<td>
							Price:
						</td>
						<td>
							<input value="<?php echo $price; ?>" type="number" name="price" min="1.00" max="50000.00" class="itemField">
						</td>
					</tr>
					<tr>
						<td>
							Description:
						</td>
						<td>
							<textarea name="desc" rows="3" columns="30" class="itemField"><?php echo $desc; ?></textarea>
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
							<input type="submit" class="button large" value="Save" name="submit">
						</td>
					</tr>
				</table>
			</form>
		</div>

		<?php
			}
			else
				echo "You cannot perform this action!<br>";

	 	?>
	 	</div>
	</body>
</html>