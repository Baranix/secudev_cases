<?php
	session_start();

	include 'header.php';

	$item_id = $_GET["item"];
	$item_id = $purifier->purify($item_id);

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
			$q = "SELECT id FROM cart WHERE paid=false AND user_id=" . $_SESSION["user"] . " AND item_id=". $item_id;
			$result = mysqli_query($con,$q);
			if( mysqli_num_rows($result) == 0 )
            {
            	$q = "INSERT INTO cart (user_id, item_id, qty) VALUES (" . $_SESSION["user"] . ", " . $item_id . ", 1);";
            	mysqli_query($con,$q);
            }
		}
	}

	mysqli_close($con);

	redirect("cart.php");

?>