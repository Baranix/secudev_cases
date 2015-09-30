<?php
	session_start();

	include 'header.php';
	
	if(isset($_SESSION["user"]))
	{
		include 'connect.php';
		$con = mysqli_connect("localhost", $db_username, $db_password, $db_name);
		if( mysqli_connect_errno() )
		{
			mysqli_close($con);
			redirect("login.html");
		}
		else
		{
			$q = "SELECT username
				FROM user
				WHERE id=" . $_SESSION["user"];

			$result = mysqli_query($con, $q);

			if( mysqli_num_rows($result) == 0 )
			{
				mysqli_close($con);
				redirect("login.html");
			}
		}
	}
	else
	{
		mysqli_close($con);
		redirect("login.html");
	}

	// Strip all inputs of possible HTML tags and surrounding white spaces
	$gender = strip_tags( trim( $_POST["gender"] ) );
	if ( empty( $_POST["salutation"] ) )
	{
		if ( $gender == 1 )
		{
			$salutation = 1;
		}
		else
		{
			$salutation = 5;
		}
	}
	else
	{
		$salutation = strip_tags( trim( $_POST["salutation"] ) );
	}
	$lname = strip_tags( trim( $_POST["lastName"] ) );
	$fname = strip_tags( trim( $_POST["firstName"] ) );
	$year = strip_tags( trim( $_POST["year"] ) );
	$month = strip_tags( trim( $_POST["month"] ) );
	$day = strip_tags( trim( $_POST["day"] ) );
	$about = strip_tags( trim( $_POST["aboutMe"] ) );
	$old_password = strip_tags( trim( $_POST["old_password"] ) );
	$new_password = strip_tags( trim( $_POST["new_password"] ) );

	function checkCharacterLimit( $x )
	{
		// Checks if input is less than 50 characters
		if( !empty( $x ) && strlen($x) <= 50 )
			return 1;
		else
			return 0;
	}

	// Flags to confirm if all necessary inputs are valid
	//$flag = array("username"=>0, "password"=>0, "salutation"=>0, "lname"=>0, "fname"=>0, "gender"=>0, "birthdate"=>0);
	$flag = array("salutation"=>0, "lname"=>0, "fname"=>0, "gender"=>0, "birthdate"=>0, "old_password"=>0, "new_password"=>0);

	// Check character limit of each input, set flag to 1 if valid
	if( !empty( $salutation ) )
		$flag["salutation"] = 1;
	$flag["lname"] = checkCharacterLimit($lname);
	$flag["fname"] = checkCharacterLimit($fname);
	if( $gender=='1' || $gender=='2' )
	{
		$flag["gender"] = 1;
	}
	if( !empty($year) && !empty($month) && !empty($day) && preg_match("/^[0-9]+$/", $year) && preg_match("/^[0-9]+$/", $month) && preg_match("/^[0-9]+$/", $day) )
	{
		// Check if birthdate is equal to or older than 18 years
		$birthdate_compare = date( 'Y-m-d', strtotime( $year . "-" . $month . "-" . $day ) );
		$today =  date( 'Y-m-d', strtotime( "-18 years", time() ) );
		if( $today > $birthdate_compare )
			$flag["birthdate"] = 1;
	}
	$flag["old_password"] = 1;
	$flag["new_password"] = 1;
	if ( !empty($old_password) || !empty($new_password) )
	{
		$flag["old_password"] = checkCharacterLimit($old_password);
		$flag["new_password"] = checkCharacterLimit($new_password);

		$q = "SELECT username
			FROM user
			WHERE id=" . $_SESSION["user"] . " and password='" . md5($old_password) ."'";

		$result = mysqli_query($con, $q);

		if (mysqli_num_rows($result) == 0)
		{
			$flag["old_password"] = 0;
		}
	}

	if( !in_array(0, $flag) ) // if 0 is not in the array "flag"
	{
		$birthdate = date('Y-m-d', strtotime( $year . "/" . $month . "/" . $day ) );

		// mysqli_real_escape_string() allows quotation marks, apostrophes, and such to
		// be inputted without messing with the SQL statement
		$salutation = mysqli_real_escape_string($con, $salutation);
		$lname = mysqli_real_escape_string($con, $lname);
		$fname = mysqli_real_escape_string($con, $fname);
		$gender = mysqli_real_escape_string($con, $gender);
		$about = mysqli_real_escape_string($con, $about);

		if ( !empty($new_password) )
		{
			$q = "UPDATE user SET password='" . md5($new_password) . "', salutation='" . $salutation . "', first_name='" . $fname . "', last_name='" . $lname . "', gender=" . $gender . ", birthdate='" . $birthdate . "', about='" . $about . "'
				WHERE id=" . $_SESSION["user"];
		}
		else
		{
			$q = "UPDATE user SET  salutation='" . $salutation . "', first_name='" . $fname . "', last_name='" . $lname . "', gender=" . $gender . ", birthdate='" . $birthdate . "', about='" . $about . "'
				WHERE id=" . $_SESSION["user"];
		}

		$result = mysqli_query($con,$q);

		mysqli_close($con);
	}
	else
	{
		// Display error message if one or more flags returned 0
		echo "<br>Error: One or more inputs are invalid!";
		/*foreach($flag as $key => $value)
		{
			echo "<br>" . $key . ": " . $value;
		}*/

		redirect("editProfilePage.php");
	}

	redirect("profile.php");
?>