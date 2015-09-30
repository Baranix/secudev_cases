<?php
	session_start();
	session_unset();
	session_destroy();

	include 'header.php';

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