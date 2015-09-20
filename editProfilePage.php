<?php
	session_start();

?>


<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>

		<h1>Edit Profile</h1>

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
						$q = "SELECT username, password, salutation, last_name, first_name, gender, birthdate, about, is_superuser
							FROM user
							WHERE id=" . $_SESSION["user"];

						$result = mysqli_query($con, $q);

						if (mysqli_num_rows($result) > 0)
						{
							$row = mysqli_fetch_assoc($result);

							$username = $row["username"];
							$salutation = $row["salutation"];
							$lname = $row["last_name"];
							$fname = $row["first_name"];
							$gender = $row["gender"];
							$birthdate = $row["birthdate"];
							$month = date("m", strtotime($birthdate));
							$day = date("d", strtotime($birthdate));
							$year = date("Y", strtotime($birthdate));
							$about = $row["about"];
							$superuser = $row["is_superuser"];
			?>

			<form action="updateProfile.php" method="post">
			<br> <br>
			    USERNAME: <?php echo $username;?>
			    <br><br>
				FIRST NAME: <input id="fname" value="<?php echo $fname;?>" type="text" name="firstName" maxlength="50" onchange="validate(this)" required>
				<br>
				<span class="warning" id="warning_fname"></span>
				<br> <br>
				LAST NAME: <input id="lname" value="<?php echo $lname;?>" type="text" name="lastName" maxlength="50" onchange="validate(this)" required>
				<br>
				<span class="warning" id="warning_lname"></span>
				<br> <br>
				GENDER:
						<input type="radio" id="male" name="gender" value="1" onchange="genderChange(this.value)" <?php if ($gender == 1) echo 'checked';?>>
						<label for="male">Male</label>
						<input type="radio" id="female" name="gender" value="2" onchange="genderChange(this.value)" <?php if ($gender == 2) echo 'checked';?>>
						<label for="female">Female</label>
				<br> <br>
				SALUTATION: <select id="salutation" name="salutation">
						<option value="1" selected>Mr.</option>
						<option value="2">Sir</option>
						<option value="3">Se&ntilde;or</option>
						<option value="4">Count</option>
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
				ABOUT ME:  <br> <br>
						   <textarea name="aboutMe" rows="8" cols="50"><?php echo $about;?></textarea>
				<br> <br>
				<a href="#change_password" onclick="showChangePassword()">Change Password</a>
				<script type="text/javascript">
					function showChangePassword()
					{
						document.getElementById("change_password").style.display = "block";
						document.getElementById("old_password").required = true;
						document.getElementById("new_password").required = true;
					}
				</script>
				<div id="change_password" style="display:none">
					<br>
					OLD PASSWORD:  <input id="old_password" type="password" name="old_password" maxlength="50" onchange="validate(this)">
					<br>
					<span class="warning" id="warning_oldpassword"></span>
					<br>
					NEW PASSWORD:  <input id="new_password" type="password" name="new_password" maxlength="50" onchange="validate(this)">
					<br>
					<span class="warning" id="warning_newpassword"></span>
				</div>
				<br> <br>

				<input id="submit" class="button medium" type="submit" value="Update"> <a class="button medium" href="logout.php">Logout</a>
			</form> 
			<?php
						if($superuser)
							{
								echo "<br><br><a href=\"adminRegistrationPage.php\">Admin Page</a>";
							}

						}
						else
						{
							echo "An error has occured, please <a href=\"login.html\">log in</a> again.";
						}
					}
					mysqli_close($con);
				}
				else
				{
					echo "Please <a href=\"login.html\">log in</a> again!";
				}


			?>

		</div>

		<script src="registration.js" onload="genderChange(<?php echo $gender;?>);birthdateChange(<?php echo $month . ',' . $day . ',' . $year;?>);"></script>

	</body>
</html>