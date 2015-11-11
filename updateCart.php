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
			$num_items = count($_POST['item_id']);

			for($i = 0; $i < $num_items; $i++)
			{
				if ($_POST['qty'][$i] > 0)
				{
					$q = "UPDATE cart SET qty=" . $_POST['qty'][$i] . " WHERE checkout=false AND item_id=". $_POST['item_id'][$i] ." AND user_id=" . $_SESSION["user"];
				}
				else
				{
					$q = "DELETE FROM cart WHERE checkout=false AND item_id =" . $_POST['item_id'][$i] . " AND user_id =" . $_SESSION["user"];
				}
				mysqli_query($con,$q);
			}

		}
	}

	mysqli_close($con);

	redirect("cart.php");

?>