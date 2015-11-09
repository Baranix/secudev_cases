<?php

	session_start();

	include 'header.php';

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
				$dir = "img/items/";
				$image = $dir . basename($_FILES["image"]["name"]);
				$file_type = pathinfo($image, PATHINFO_EXTENSION);

				if(isset($_POST["submit"]))
				{
			    	$check = getimagesize($_FILES["image"]["tmp_name"]);
			    	if($check !== false)
			    	{
			    		move_uploaded_file($_FILES["image"]["tmp_name"], $image);

    					$name = $_POST["name"];
						$price = $_POST["price"];
						$desc = $_POST["desc"];

						$q = "INSERT INTO item (name, price, description, image) VALUES ('" . $name . "', " . $price . ", '" . $desc . "', '" . $image . "');";
						mysqli_query($con,$q);

						mysqli_close($con);

						redirect("store.php");
			    	}
			    	else
			    	{
				        redirect("store.php");
			    	}
				}
			}
			else
			{
				redirect("store.php");
			}
		}
	}
?>