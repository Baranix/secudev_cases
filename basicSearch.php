<?php
	if(isset($_POST['submit'])){
		if(isset($_GET['go'])){
			// to limit variable inputs
			if(preg_match("^/[A-Za-z]+/", $_POST['message'])){
				$message=$_POST['message'];
				//connect to database
				include 'connect.php';
				$con = mysqli_connect("localhost", $db_username, $db_password, $db_name);
				if( mysqli_connect_errno() )
				{
					echo "<br>Database connection failed: " . mysqli_connect_error() . "<br>";
				}
				else
				{
					//to be searched in DB message
					$sql="SELECT id, message FROM message WHERE message LIKE '%".$message."%'";
					$result=mysql_query($sql);

					$found = mysql_num_rows($result);

					if ($found==0)
					echo "Sorry, there are no matching results";
					while ($row=mysql_fetch_array($result)) {
						$message = $row['message'];
						$id = $row['id'];
						//display results
						echo "<ul>\n";
						echo "<li>"."<a href=\"search.php?id=$id\">" .$message. " </a> </li>\n";
						echo "</ul>";
					}
				}
			}
		}
	}
?>