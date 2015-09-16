<?php
	// Strip all inputs of possible HTML tags
	$username = strip_tags( $_POST["username"] );
	$password = strip_tags( $_POST["password"] );
	$salutation = strip_tags( $_POST["salutation"] );
	$lname = strip_tags( $_POST["lastName"] );
	$fname = strip_tags( $_POST["firstName"] );
	$gender = strip_tags( $_POST["gender"] );
	$year = strip_tags( $_POST["year"] );
	$month = strip_tags( $_POST["month"] );
	$day = strip_tags( $_POST["day"] );
	$about = strip_tags( $_POST["aboutMe"] );

	if ( isset( $_POST["status"] ) )
	{
		// Check if admin capabilities are active
		$status = strip_tags( $_POST["status"] );
	}

	function redirect($url)
	{
		// Redirect to another page when done
		ob_start();
		sleep(2);
		header("Location: " . $url);
		ob_end_flush();
		die();
	}

	function checkCharacterLimit( $x )
	{
		// Checks if input is less than 50 characters
		if( !empty( $x ) && strlen($x) <= 50 )
			return 1;
		else
			return 0;
	}

	// Flags to confirm if all necessary inputs are valid
	$flag = array("username"=>0, "password"=>0, "salutation"=>0, "lname"=>0, "fname"=>0, "gender"=>0, "birthdate"=>0);

	// Check character limit of each input, set flag to 1 if valid
	$flag["username"] = checkCharacterLimit($username);
	$flag["password"] =  checkCharacterLimit($password);
	if( !empty( $salutation ) )
		$flag["salutation"] = 1;
	$flag["lname"] = checkCharacterLimit($lname);
	$flag["fname"] = checkCharacterLimit($fname);
	$flag["gender"] = checkCharacterLimit($gender);
	if( $flag["gender"] && ( $gender!='M' && $gender!='F' ) )
	{
		$flag["gender"] = 0;
	}
	if( !empty($year) && !empty($month) && !empty($day) && preg_match("/^[0-9]+$/", $year) && preg_match("/^[0-9]+$/", $month) && preg_match("/^[0-9]+$/", $day) )
	{
		// Check if birthdate is equal to or older than 18 years
		$birthdate_compare = date( 'Y-m-d', strtotime( $year . "-" . $month . "-" . $day ) );
		$today =  date( 'Y-m-d', strtotime( "-18 years", time() ) );
		if( $today > $birthdate_compare )
			$flag["birthdate"] = 1;
	}

	if( !in_array(0, $flag) ) // if 0 is not in the array "flag"
	{
		$birthdate = date('Y-m-d', strtotime( $year . "/" . $month . "/" . $day ) );

		// Connect to the Database
		include 'connect.php';
		$con = mysqli_connect("localhost", $db_username, $db_password, $db_name);
		if( mysqli_connect_errno() )
		{
			// Connection error
			echo "<br>Database connection failed: " . mysqli_connect_error() . "<br>";
		}
		else
		{
			// Connection sucess

			// mysqli_real_escape_string() allows quotation marks, apostrophes, and such to
			// be inputted without messing with the SQL statement
			$username = mysqli_real_escape_string($con, $username);
			$salutation = mysqli_real_escape_string($con, $salutation);
			$lname = mysqli_real_escape_string($con, $lname);
			$fname = mysqli_real_escape_string($con, $fname);
			$gender = mysqli_real_escape_string($con, $gender);
			$about = mysqli_real_escape_string($con, $about);

			$q = "SELECT username FROM user WHERE username='" . $username . "';";

			$result = mysqli_query($con,$q);

			if( !$result )
			{
				// Columns to be updated
				$columns = "username, password, salutation, first_name, last_name, gender, birthdate, about";
				// Values to be inserted
				$values = "'" . $username . "' , 
						'" . md5($password) ."', 
						'" . $salutation . "', 
						'" . $fname . "', 
						'" . $lname . "', 
						'" . $gender . "', 
						'" . $birthdate . "', 
						'" . $about . "'";
				if( !empty($status) && ( (int) $status ) )
				{
					// If admin status is added
					$columns = $columns . ", is_superuser";
					$values = $values . ", 1";
				}

				$q = "INSERT INTO `user` (" . $columns . ") VALUES ( " . $values . " );";

				if( mysqli_query($con,$q) )
				{
					echo "<br>Registration successful!";
				}
				else
				{
					echo "<br>Error updating database!";

				}
			}
			else
			{
				echo "<br>Username already exists.";
			}
		}

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
	}
	
	redirect("login.html");
?>