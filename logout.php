<?php
	session_start();
	session_unset();
	session_destroy();

	function redirect($url)
	{
		// Redirect to another page when done
		ob_start();
		sleep(2);
		header("Location: " . $url);
		ob_end_flush();
		die();
	}

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