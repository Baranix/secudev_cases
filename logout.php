<?php
	session_start();

	include 'header.php';

	function message()
	{
?>

<html>
	<head>
	</head>
	<body>

		<div class="inform">You have sucessfully logged out. You will be redirected shortly.</div>

		<?php

			redirect("login.html");

		?>

	</body>
</html>

<?php

	}

	if( isset($_SESSION["user"]) )
	{
		if( isset($_POST["logout"]) )
		{
			if( $_SESSION["user"] == $_POST["logout"])
			{
				session_unset();
				session_destroy();
				message();
			}
			else
			{
				redirect("profile.php");
			}
		}
		else
		{
			redirect("profile.php");
		}
	}
	else
	{
		redirect("login.html");
	}

?>