<?php
	session_start();

	function redirect($url)
	{
		// Redirect to another page when done
		ob_start();
		sleep(2);
		header("Location: " . $url);
		ob_end_flush();
		exit();
	}

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
			$q = "SELECT is_superuser
				FROM user
				WHERE id=" . $_SESSION["user"];

			$result = mysqli_query($con, $q);
		
			if( mysqli_query($con,$q) )
			{
				while($row = mysqli_fetch_assoc($result))
				{
					$_SESSION["admin"] = $row["is_superuser"];
				}
			}
			else
			{
				echo "Error: User does not exist.";
			}
		}

		if(isset($_SESSION["admin"]))
		{
			if( $_SESSION["admin"]==0 )
			{
				redirect("logout.php");
			}
		}
	}
	else
	{
		redirect("login.html");
	}
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>

		<h1>Register</h1>

		<div class="content">

			<form action="validate.php" method="post">
			<br> <br>
				FIRST NAME: <input id="fname" type="text" name="firstName" maxlength="50" onchange="validate(this)" required>
				<br>
				<span class="warning" id="warning_fname"></span>
				<br> <br>
				LAST NAME: <input id="lname" type="text" name="lastName" maxlength="50" onchange="validate(this)" required>
				<br>
				<span class="warning" id="warning_lname"></span>
				<br> <br>
				GENDER: 
						<input type="radio" id="male" name="gender" value="M" onchange="genderChange(this)" checked>
						<label for="male">Male</label>
						<input type="radio" id="female" name="gender" value="F" onchange="genderChange(this)">
						<label for="female">Female</label>
				<br> <br>
				SALUTATION: <select id="salutation" name="salutation">
						<option value="Mr.">Mr.</option>
						<option value="Sir">Sir</option>
						<option value="Se&ntilde;or">Se&ntilde;or</option>
						<option value="Count">Count</option>
						</select>
				<br> <br>

				BIRTHDATE: <br>
					Month: 	<select id="month" name="month" onchange="calculateAge('month', this)">
								<option value="1">January</option>
								<option value="2">February</option>
								<option value="3">March</option>
								<option value="4">April</option>
								<option value="5">May</option>
								<option value="6">June</option>
								<option value="7">July</option>
								<option value="8">August</option>
								<option value="9">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>
					   
					Day:	<select id="day" name="day" onchange="calculateAge('day', this)">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
							</select>
					   
					Year: 
							<select id="year" name="year" onchange="calculateAge('year', this)">
							</select>
					<br>
					<span class="warning" id="warning_birthdate"></span>
					<br> <br>
				USERNAME:  <input id="username" type="text" name="username" maxlength="50" onchange="validate(this)" required>
				<br>
				<span class="warning" id="warning_username"></span>
				<br> <br>
				PASSWORD:  <input id="password" type="password" name="password" maxlength="50" onchange="validate(this)" required>
				<br>
				<span class="warning" id="warning_password"></span>
				<br> <br>
				ABOUT ME:  <br> <br>
						   <textarea name="aboutMe" rows="8" cols="50">Describe yourself.</textarea>
				<br> <br>
				USER STATUS:
						<br><input type="radio" id="status_user" name="status" value="0" checked>
						<label for="status_user">Regular User</label>
						<br><input type="radio" id="status_admin" name="status" value="1">
						<label for="status_admin">Admin User</label>
				<br> <br>

					<input id="submit" type="submit" value="Register">
			</form>
		</div>

		<script src="registration.js"></script>

	</body>
</html>