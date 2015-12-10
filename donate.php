<?php
    session_start();

    include 'header.php';

    $id = $_SESSION["user"];
    $tx = $_GET["tx"];

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
            // request donation details from paypal using tx id
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://www.sandbox.paypal.com/cgi-bin/webscr");
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
                'cmd' => '_notify-synch',
                'tx' => $tx,
                'at' => '-pChIE70JdobDMnbhzMyO5NOESfp3PyYispLEgDREdely2lic6oB7MfpS58'
            )));
            $result  = curl_exec ($curl);

            // parse response string and extract donation info
            $token = strtok($result, "\n");
            while ( $token !== false )
            {
                $token = strtok("\n");

                $params = explode('=', $token);
                if ( $params[0] == 'mc_gross' )
                {
                    $amount = $params[1];
                }
                else if ( $params[0] == 'payment_status' )
                {
                    $status = $params[1];
                }
            }

            if ( $status == 'Completed' )  // if donation is succesful
            {
                $q = "INSERT INTO donation (user_id, amount) VALUES (" . $id . ", " . $amount . ");";
                mysqli_query($con,$q);
            }
        }
    }

    mysqli_close($con);

    redirect("profile.php");

?>
