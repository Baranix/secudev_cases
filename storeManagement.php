<?php
	session_start();

	include 'header.php';

?>
<html>
	<head>
		<title>Store Management</title>
		<link rel="stylesheet" type="text/css" href="style.css">
        <script src="jquery.js"></script>
	</head>
	<body>

		<h1>Store Management</h1>

		<div class="content">
			<center>
				<a class="button large" href="store.php">Back to Store</a>
				<a class="button large" href="profile.php">Back to Profile</a>
				<br><br><br>
			</center>
			<table>
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
			}

			if($superuser)
			{
				$q = "SELECT t.id as tid, u.username, u.id as uid, t.amount, t.timestamp
					FROM transaction as t, user as u, cart as c
					WHERE c.transaction_id = t.id
						AND u.id = t.user_id
					GROUP BY t.id";

				$result = mysqli_query($con, $q);

				if( mysqli_num_rows($result) > 0 )
				{
					while($row = mysqli_fetch_assoc($result))
					{
						echo "<tr><td>" . $row["tid"] . "</td>";
						echo "<td>" . $row["timestamp"] . "</td>";
						echo "<td><a href=\"profile.php?u=" . $row["uid"] . "\">" . $row["username"] . "</a></td></tr>";

						$q = "SELECT i.name, i.price, c.qty
							FROM item as i, transaction as t, cart as c
							WHERE i.id=c.item_id
								AND c.transaction_id = t.id
								AND t.id=" . $row["tid"];

							$result2 = mysqli_query($con, $q);
							if( mysqli_num_rows($result2) > 0 )
							{
								while($row2 = mysqli_fetch_assoc($result2))
								{
									echo "<tr><td><span class=\"manage_item\">" . $row2["name"] . "</span></td>";
									echo "<td>x" . $row2["qty"] . "</td>";
									echo "<td>P" . $row2["price"] . "</td></tr>";
								}
							}
						echo "<tr><td colspan=\"3\"><span class=\"manage_amount\">Paid: " . $row["amount"] . "</span></td></tr>";
					}
				}
			}
		}
	}
	else
	{

	}

?>
			</table>
		</div>
	</body>
</html>